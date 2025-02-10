<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminReservationController extends Controller
{
    public function showReservation_Handling_Page()
    {
        return view('admin.reservation-handling'); // Refers to resources/views/auth/admin-login.blade.php
    }
    /**
     * Display all active reservations that need admin action
     */
    public function index()
    {
        die('Test - Controller reached');

        try {
            // Log the current date for debugging
            $currentDate = date('Y-m-d');
            \Log::info('Current date: ' . $currentDate);

            // Get all reservations without date filter first
            $allReservations = DB::select("
                SELECT TOP 10
                r.ReservationID,
                r.ReservationDate,
                r.TimeSlot,
                r.Resrv_Status,
                CONCAT(s.FirstName, ' ', s.LastName) AS StudentName,
                b.Title AS BookTitle,
                b.BookID
                FROM [NewReservationSystem].[dbo].[Reservations] r
                INNER JOIN [NewReservationSystem].[dbo].[Students] s ON r.StudentID = s.Student_ID
                INNER JOIN [Books_Inventory].[dbo].[Books] b ON r.BookID = b.BookID
                ORDER BY r.CreatedAt DESC"
            );

            \Log::info('All recent reservations:', ['count' => count($allReservations), 'data' => $allReservations]);

            // Now get today's reservations
            $todayReservations = DB::select("
                SELECT
                r.ReservationID,
                CONCAT(s.FirstName, ' ', s.LastName) AS StudentName,
                b.Title AS BookTitle,
                r.ReservationDate,
                r.TimeSlot,
                b.BookID,
                r.Resrv_Status,
                r.CreatedAt
                FROM [NewReservationSystem].[dbo].[Reservations] r
                INNER JOIN [NewReservationSystem].[dbo].[Students] s ON r.StudentID = s.Student_ID
                INNER JOIN [Books_Inventory].[dbo].[Books] b ON r.BookID = b.BookID
                ORDER BY r.ReservationDate DESC, r.TimeSlot ASC"
            );

            \Log::info('All reservations with dates:', [
                'count' => count($todayReservations), 
                'data' => $todayReservations,
                'current_date' => date('Y-m-d'),
                'timezone' => date_default_timezone_get()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in reservation query: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Failed to load reservations. Error: ' . $e->getMessage());
        }
    }
    /**
     * Cancel a reservation (when student doesn't show up)
     */
    public function cancelReservation($id)
    {
        try {
            DB::beginTransaction();
            Log::info("Cancelling reservation due to no-show: $id");

            // Get the reservation details first
            $reservation = DB::selectOne("
                SELECT BookID, ReservationDate, TimeSlot 
                FROM [NewReservationSystem].[dbo].[Reservations] 
                WHERE ReservationID = ?", [$id]);

            if (!$reservation) {
                throw new \Exception('Reservation not found');
            }

            // Only allow cancellation if the reservation is for today
            $today = Carbon::now()->format('Y-m-d');
            if ($reservation->ReservationDate != $today) {
                throw new \Exception('Can only cancel today\'s reservations');
            }

            // Update reservation status
            DB::update("
                UPDATE [NewReservationSystem].[dbo].[Reservations] 
                SET Resrv_Status = 'Canceled',
                    CancelReason = 'Student no-show',
                    CanceledAt = GETDATE()
                WHERE ReservationID = ?", [$id]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Reservation cancelled due to no-show']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling reservation: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark a book as returned
     */
    public function returnBook($id)
    {
        try {
            DB::beginTransaction();
            Log::info("Processing book return for reservation: $id");

            // Get the reservation details first
            $reservation = DB::selectOne("
                SELECT BookID, ReservationDate, TimeSlot 
                FROM [NewReservationSystem].[dbo].[Reservations] 
                WHERE ReservationID = ?", [$id]);

            if (!$reservation) {
                throw new \Exception('Reservation not found');
            }

            // Update reservation status
            DB::update("
                UPDATE [NewReservationSystem].[dbo].[Reservations] 
                SET Resrv_Status = 'Returned',
                    ReturnedAt = GETDATE()
                WHERE ReservationID = ?", [$id]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Book marked as returned successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing book return: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a reservation (administrative cleanup)
     */
    public function deleteReservation($id)
    {
        try {
            DB::beginTransaction();
            Log::info("Deleting reservation: $id");

            // Only allow deletion of cancelled or returned reservations
            $affected = DB::delete("
                DELETE FROM [NewReservationSystem].[dbo].[Reservations] 
                WHERE ReservationID = ? 
                AND Resrv_Status IN ('Canceled', 'Returned')", [$id]);

            if ($affected === 0) {
                throw new \Exception('Can only delete cancelled or returned reservations');
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Reservation deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting reservation: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}