<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    protected const ERROR_MESSAGES = [
        '50001' => 'The selected book is not available for reservation.',
        '50003' => 'Invalid time slot format.',
        '50004' => 'Invalid time slot.',
        '50005' => 'This book is already reserved for the selected date and time slot.',
        'DEFAULT' => 'An error occurred while processing your reservation.'
    ];

    protected const TIME_SLOTS = [
        '7AM-9AM',
        '9AM-11AM',
        '11AM-13PM',
        '13PM-15PM',
        '15PM-17PM'
    ];
    public function store(Request $request)
    {
        try {
            Log::info('Reservation request received', ['data' => $request->all()]);

            // Validate request
            $validated = $request->validate([
                'bookId' => 'required|integer|exists:books_db.dbo.Books,BookID',
                'reservationDate' => 'required|date|after_or_equal:today',
                'timeSlot' => ['required', 'string', 'in:'.implode(',', self::TIME_SLOTS)]
            ]);

            Log::info('Validated data', ['validated' => $validated]);

            // Format the date
            $formattedDate = \Carbon\Carbon::parse($validated['reservationDate'])->format('Y-m-d');

            // Call stored procedure
            Log::info('Calling stored procedure...');
            DB::statement(
                "EXEC dbo.AddReservations @BookID = ?, @StudentID = ?, @ReservationDate = ?, @TimeSlot = ?",
                [
                    $validated['bookId'],
                    Auth::guard('student')->id(),
                    $formattedDate,
                    $validated['timeSlot']
                ]
            );

            Log::info('Stored procedure executed successfully');
            return response()->json(['success' => true, 'message' => 'Book reserved successfully']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Reservation error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            // Extract SQL error code/message if it exists
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, '50001') !== false) {
                return response()->json(['success' => false, 'message' => 'The selected book is not available for reservation due to maximum limit reached.'], 400);
            } elseif (strpos($errorMessage, '50003') !== false) {
                return response()->json(['success' => false, 'message' => 'Invalid time slot format. Please choose a valid time slot.'], 400);
            } elseif (strpos($errorMessage, '50004') !== false) {
                return response()->json(['success' => false, 'message' => 'The provided time slot is invalid.'], 400);
            } elseif (strpos($errorMessage, '50005') !== false) {
                return response()->json(['success' => false, 'message' => 'This book is already reserved for the selected date and time slot.'], 400);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'This book seems reach its limit or has problem with your reservation. Please try again later.',
                ], 500);
            }
        }
    }




    public function checkAvailability(Request $request)
    {
        try {
            $validated = $request->validate([
                'bookId' => 'required|integer|exists:books_db.dbo.Books,BookID',
                'reservationDate' => 'required|date',
                'timeSlot' => ['required', 'string', 'in:'.implode(',', self::TIME_SLOTS)]
            ]);

            $formattedDate = date('Y-m-d', strtotime($validated['reservationDate']));

            Log::info('Checking availability', [
                'bookId' => $validated['bookId'],
                'date' => $formattedDate,
                'timeSlot' => $validated['timeSlot']
            ]);

            // Check if the book has reached max reservations
            $bookAvailability = DB::connection('books_db')->select("
                SELECT
                    CASE
                        WHEN b.Status != 'Active' THEN 'unavailable'
                        WHEN b.ReservationCount >= b.MaxReservations THEN 'limit_reached'
                        ELSE 'available'
                    END as status
                FROM [Books_Inventory].[dbo].[Books] b
                WHERE b.BookID = ?",
                [$validated['bookId']]
            );

            if (!empty($bookAvailability) && $bookAvailability[0]->status === 'limit_reached') {
                return $this->errorResponse('This book has reached the maximum reservation limit and cannot be reserved.', 400);
            }

            if (!empty($bookAvailability) && $bookAvailability[0]->status === 'unavailable') {
                return $this->errorResponse('This book is currently unavailable for reservation.', 400);
            }

            // Check if the time slot is already reserved
            $timeSlotTaken = DB::connection('new_reservation_db')->select("
                SELECT 1
                FROM [NewReservationSystem].[dbo].[Reservations]
                WHERE BookID = ?
                AND ReservationDate = ?
                AND TimeSlot = ?",
                [$validated['bookId'], $formattedDate, $validated['timeSlot']]
            );

            if (!empty($timeSlotTaken)) {
                return $this->errorResponse('The selected time slot is already taken. Please choose another time slot.', 400);
            }

            return $this->successResponse('Availability checked successfully', ['available' => true]);

        } catch (\Exception $e) {
            Log::error('Availability check error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse('Error checking availability');
        }
    }


    protected function getErrorMessage($errorString)
    {
        foreach (self::ERROR_MESSAGES as $code => $message) {
            if (str_contains($errorString, (string)$code)) {
                return $message;
            }
        }

        return self::ERROR_MESSAGES['DEFAULT'];
    }

    protected function successResponse($message, $data = null)
    {
        $response = ['success' => true, 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        return response()->json($response);
    }

    protected function errorResponse($message, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    public function getStudentReservations()
    {
        $studentID = Auth::guard('student')->user()->Student_ID;

        // Fetch reservations from the view
        $reservations = DB::select("
            SELECT * FROM vw_StudentConfirm WHERE Student_ID = ?
        ", [$studentID]);

        return response()->json($reservations);
    }

    public function getConfirmedReservations()
    {
        $studentID = Auth::guard('student')->user()->Student_ID;

        // Call the stored procedure
        $result = DB::select('EXEC CountStudConfirmRes ?', [$studentID]);

        // Since DB::select returns an array, get the first item
        $confirmedReservations = $result[0]->ConfirmedReservations ?? 0;

        return response()->json(['confirmedReservations' => $confirmedReservations]);
    }

    public function countConfirmedReservations()
        {
            $studentID = Auth::guard('student')->user()->Student_ID;

            // Fetch the count of confirmed reservations for the logged-in student
            $confirmedReservations = DB::select('
                SELECT COUNT(*) AS ConfirmedReservations
                FROM Reservations
                WHERE StudentID = ? AND Resrv_Status = "Confirmed"
            ', [$studentID]);

            return response()->json([
                'confirmedReservations' => $confirmedReservations[0]->ConfirmedReservations ?? 0,
            ]);
        }

    public function countOvertimeReservations()
        {
            $studentID = Auth::guard('student')->user()->Student_ID;

            // Fetch the count of overtime reservations for the logged-in student
            $overtimeCount = DB::select('EXEC COUNT_OT ?', [$studentID]);

            return response()->json([
                'overtimeReservations' => $overtimeCount[0]->OvertimeCount ?? 0,
            ]);
        }

        public function getAllReservations()
        {
            try {
                $studentId = Auth::guard('student')->id();

                // Execute the stored procedure
                $reservations = DB::select('EXEC GetAllStudRes_Student ?', [$studentId]);

                // Transform the data for different tabs
                $transformedData = [
                    'all' => [],
                    'reserved' => [],
                    'returned' => [],
                    'overtime' => []
                ];

                foreach ($reservations as $reservation) {
                    // Base reservation data
                    $reservationData = [
                        'title' => $reservation->BookTitle,
                        'researcher' => Auth::guard('student')->user()->name,
                        'reservationDate' => date('m-d-Y', strtotime($reservation->ReservationDate)),
                        'reservedUntil' => date('m-d-Y', strtotime($reservation->ReservedUntil)),
                        'timeSlot' => $reservation->TimeSlot,
                        'status' => $reservation->Resrv_Status
                    ];

                    // Add to all reservations
                    $transformedData['all'][] = $reservationData;

                    // Categorize based on status
                    switch ($reservation->Resrv_Status) {
                        case 'Reserved':
                            $transformedData['reserved'][] = $reservationData;
                            break;
                        case 'Returned':
                            $transformedData['returned'][] = $reservationData;
                            break;
                        case 'Overtime':
                            // Calculate days overdue
                            $dueDate = strtotime($reservation->ReservedUntil);
                            $today = strtotime('now');
                            $daysOverdue = floor(($today - $dueDate) / (60 * 60 * 24));
                            $reservationData['daysOverdue'] = $daysOverdue;
                            $transformedData['overtime'][] = $reservationData;
                            break;
                    }
                }

                return response()->json($transformedData);

            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to fetch reservations',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        public function getReservedBooks()
        {
            try {
                $studentId = Auth::guard('student')->id();

                // Execute the stored procedure for reserved books
                $reservations = DB::select('EXEC GetStudentReservations ?', [$studentId]);

                // Transform the data for reserved books
                $transformedData = array_map(function($reservation) {
                    return [
                        'title' => $reservation->BookTitle ?? $reservation->Title,  // Handle different column names
                        'researcher' => Auth::guard('student')->user()->name,
                        'reservationDate' => date('m-d-Y', strtotime($reservation->ReservationDate)),
                        'timeSlot' => $reservation->TimeSlot,
                        'reservedUntil' => date('m-d-Y', strtotime($reservation->ReservedUntil)),
                        'status' => 'Reserved'
                    ];
                }, $reservations);

                return response()->json([
                    'success' => true,
                    'data' => $transformedData
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to fetch reserved books',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        public function getReturnedBooks($studentId)
        {
            try {
                // Call the stored procedure with studentId
                $returnedBooks = DB::select('EXEC GetReturnedBooksByStudent :studentId', ['studentId' => $studentId]);

                // Return the data as JSON
                return response()->json(['success' => true, 'data' => $returnedBooks]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        public function getReservationReport()
        {
            try {
                $reservations = DB::select('EXEC sp_GetReservationReport');
                return response()->json($reservations);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error fetching reservation data'], 500);
            }
        }
}
