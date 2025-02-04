<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminReservationController extends Controller
{
    /**
     * Display all confirmed reservations
     */
    public function index()
    {
        try {
            Log::info('Fetching reservations for reservation-handling view.');

            $reservations = DB::select("
                SELECT
                    r.ReservationID,
                    CONCAT(s.FirstName, ' ', s.LastName) AS StudentName,
                    b.Title AS BookTitle,
                    r.ReservationDate,
                    r.TimeSlot,
                    b.BookID,
                    r.Resrv_Status
                FROM [NewReservationSystem].[dbo].[Reservations] r
                JOIN [NewReservationSystem].[dbo].[Students] s ON r.StudentID = s.Student_ID
                JOIN [Books_Inventory].[dbo].[Books] b ON r.BookID = b.BookID
                WHERE r.Resrv_Status = 'Confirmed'
                ORDER BY r.ReservationDate DESC
            ");

            Log::info('Reservations Retrieved', ['count' => count($reservations)]);

            return view('admin.reservation-handling', compact('reservations'));

        } catch (\Exception $e) {
            Log::error('Error fetching reservations: ' . $e->getMessage());
            return back()->with('error', 'Failed to fetch reservations.');
        }
    }

    /**
     * Confirm a reservation
     */
    public function confirmReservation($id)
    {
        try {
            DB::beginTransaction();
            Log::info("Confirming reservation: $id");

            // Update reservation status
            DB::update("UPDATE [NewReservationSystem].[dbo].[Reservations] SET Resrv_Status = 'Confirmed' WHERE ReservationID = ?", [$id]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Reservation confirmed successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming reservation: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to confirm reservation.'], 500);
        }
    }

    /**
     * Cancel a reservation
     */
    public function cancelReservation($id)
    {
        try {
            DB::beginTransaction();
            Log::info("Cancelling reservation: $id");

            DB::update("UPDATE [NewReservationSystem].[dbo].[Reservations] SET Resrv_Status = 'Canceled' WHERE ReservationID = ?", [$id]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Reservation cancelled successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling reservation: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to cancel reservation.'], 500);
        }
    }

    /**
     * Delete a reservation
     */
    public function deleteReservation($id)
    {
        try {
            DB::beginTransaction();
            Log::info("Deleting reservation: $id");

            DB::delete("DELETE FROM [NewReservationSystem].[dbo].[Reservations] WHERE ReservationID = ?", [$id]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Reservation deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting reservation: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete reservation.'], 500);
        }
    }

    /**
     * Mark a reservation as returned
     */
    public function returnBook($id)
    {
        try {
            DB::beginTransaction();
            Log::info("Marking reservation as returned: $id");

            DB::update("UPDATE [NewReservationSystem].[dbo].[Reservations] SET Resrv_Status = 'Returned' WHERE ReservationID = ?", [$id]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Book returned successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error marking reservation as returned: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to return book.'], 500);
        }
    }

    /**
     * Mark a reservation as overdue
     */
    public function markOvertime($id)
    {
        try {
            DB::beginTransaction();
            Log::info("Marking reservation as overdue: $id");

            DB::update("UPDATE [NewReservationSystem].[dbo].[Reservations] SET Resrv_Status = 'Overtime' WHERE ReservationID = ?", [$id]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Reservation marked as overtime']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error marking reservation as overdue: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to mark reservation as overtime.'], 500);
        }
    }
}
