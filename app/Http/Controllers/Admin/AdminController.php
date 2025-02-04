<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    // Function to fetch total reserved books count
    public function getTotalReservedBooks()
    {
        return DB::table('Reservations')->count(); // Return count only
    }

    // Admin dashboard function
    public function adminDashboard()
    {
        $totalReservedBooks = $this->getTotalReservedBooks(); // Get count
        return view('admin.dashboard', compact('totalReservedBooks')); // Pass to view
    }

    // API route for AJAX request
    public function fetchTotalReservedBooks()
    {
        $totalReservedBooks = $this->getTotalReservedBooks();
        return response()->json(['totalReservedBooks' => $totalReservedBooks]);
    }

    public function getTotalAvailableBooks()
    {
        $totalAvailableBooks = DB::connection('sqlsrv') // Specify connection if needed
            ->table('Books_Inventory.dbo.Books') // Explicitly reference database and schema
            ->where('Status', 'Active')
            ->count();

        return response()->json(['totalAvailableBooks' => $totalAvailableBooks]);
    }

    // Updated function name for admin dashboard data
    public function loadAdminDashboard()
    {
        $totalReservedBooks = $this->getTotalReservedBooks(); // Fetch reserved books count
        $totalAvailableBooks = DB::connection('sqlsrv')
            ->table('Books_Inventory.dbo.Books')
            ->where('Status', 'Active')
            ->count();

        return view('admin.dashboard', compact('totalReservedBooks', 'totalAvailableBooks'));
    }

    public function getWeeklyReservations()
    {
        $weeklyReservations = DB::table('Reservations')
            ->select(DB::raw("FORMAT(ReservationDate, 'yyyy-MM-dd') as date"), DB::raw("COUNT(*) as count"))
            ->whereYear('ReservationDate', 2025)
            ->whereRaw("DATEPART(WEEKDAY, ReservationDate) BETWEEN 2 AND 7") // 2=Monday, 7=Saturday
            ->groupBy(DB::raw("FORMAT(ReservationDate, 'yyyy-MM-dd')"))
            ->orderBy(DB::raw("FORMAT(ReservationDate, 'yyyy-MM-dd')"), 'asc')
            ->get();

        return response()->json($weeklyReservations);
    }
    public function userManagement()
    {
        $admins = Admin::all();
        return view('admin.user-management', compact('admins'));
    }

    public function addAdmin(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'Email' => 'required|email|unique:Admins,Email',
                'School_ID' => [
                    'required',
                    'regex:/^20[0-9]{2}-[0-9]{5}-CM-2$/',
                    'unique:Admins,School_ID'
                ],
                'LastName' => 'required|string|max:100',
                'FirstName' => 'required|string|max:100',
                'MiddleName' => 'nullable|string|max:100',
                'Suffix' => 'nullable|string|in:Jr.,Sr.,II,III,IV,V',
                'Gender' => 'required|in:Male,Female,Other,Prefer not to say',
                'ContactNumber' => [
                    'required',
                    'regex:/^09[0-9]{9}$/'
                ],
                'Password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[A-Z]/',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*?&#]/',
                    'confirmed'
                ],
            ]);

            // Hash the password before storing
            $hashedPassword = Hash::make($validatedData['Password']);

            // Execute the Stored Procedure to insert the new admin
            DB::statement('EXEC AddAdminAccount ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
                $validatedData['Email'],
                $validatedData['School_ID'],
                $validatedData['LastName'],
                $validatedData['FirstName'],
                $validatedData['MiddleName'],
                $validatedData['Suffix'] ?? null,
                $validatedData['Gender'],
                $validatedData['ContactNumber'],
                $hashedPassword,
                'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin added successfully!'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add admin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $admin = Admin::findOrFail($id);
            $admin->Status = strtoupper($request->status); // Convert status to uppercase for consistency
            $admin->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();

            // Initialize rules and update data arrays
            $rules = [];
            $updateData = [];

            // Validate regular fields if provided
            if ($request->filled('email')) {
                $rules['email'] = 'email|unique:Admins,Email,' . $admin->Admin_ID . ',Admin_ID';
                $updateData['Email'] = $request->email;
            }

            if ($request->filled('lastname')) {
                $rules['lastname'] = 'string|max:100';
                $updateData['LastName'] = $request->lastname;
            }

            if ($request->filled('firstname')) {
                $rules['firstname'] = 'string|max:100';
                $updateData['FirstName'] = $request->firstname;
            }

            if ($request->filled('middlename')) {
                $rules['middlename'] = 'string|max:100';
                $updateData['MiddleName'] = $request->middlename;
            }

            if ($request->filled('contactnum')) {
                $rules['contactnum'] = ['regex:/^09[0-9]{9}$/'];
                $updateData['ContactNumber'] = $request->contactnum;
            }

            // Add password validation if password change is attempted
            if ($request->filled('current_password')) {
                $rules['current_password'] = 'required';
                $rules['new_password'] = [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                    'confirmed'
                ];
                $rules['new_password_confirmation'] = 'required';

                // Verify current password
                if (!Hash::check($request->current_password, $admin->Password)) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['current_password' => ['Current password is incorrect']]
                    ], 422);
                }
            }

            // Validate the submitted data
            $validatedData = $request->validate($rules);

            // Handle profile update if there are changes
            if (!empty($updateData)) {
                // Prepare parameters for stored procedure
                $params = [
                    $admin->Admin_ID,
                    $updateData['Email'] ?? $admin->Email,
                    $updateData['LastName'] ?? $admin->LastName,
                    $updateData['FirstName'] ?? $admin->FirstName,
                    $updateData['MiddleName'] ?? $admin->MiddleName,
                    $updateData['ContactNumber'] ?? $admin->ContactNumber
                ];

                // Execute the stored procedure for profile update
                DB::statement('EXEC UpdateAdminProfile ?, ?, ?, ?, ?, ?', $params);
            }

            // Handle password update if requested
            if ($request->filled('new_password')) {
                // You might need to create a new stored procedure for password update
                DB::statement('EXEC UpdateAdminPassword ?, ?', [
                    $admin->Admin_ID,
                    Hash::make($request->new_password)
                ]);
            }

            // Determine appropriate success message
            $message = '';
            if (!empty($updateData) && $request->filled('new_password')) {
                $message = 'Profile and password updated successfully!';
            } elseif (!empty($updateData)) {
                $message = 'Profile updated successfully!';
            } elseif ($request->filled('new_password')) {
                $message = 'Password updated successfully!';
            } else {
                $message = 'No changes were made to the profile.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAdminProfile()
    {
        try {
            $admin = Auth::guard('admin')->user();
            return response()->json([
                'success' => true,
                'data' => $admin
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();

            // Validate the password data
            $validated = $request->validate([
                'current_password' => 'required',
                'new_password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                    'confirmed'
                ]
            ]);

            // Verify current password
            if (!Hash::check($request->current_password, $admin->password)) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'current_password' => ['Current password is incorrect.']
                    ]
                ], 422);
            }

            // Update password
            $admin->password = Hash::make($validated['new_password']);
            $admin->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentsList()
    {
        $students = DB::select('EXEC sp_GetStudentsList');
        return response()->json($students);
    }

    public function getAdminDetails($schoolId)
    {
        $admin = DB::select('
            SELECT School_ID, Email, FirstName, MiddleName, LastName,
                Suffix, Gender, ContactNumber, Status
            FROM Admins
            WHERE School_ID = ?
        ', [$schoolId]);

        if (count($admin) > 0) {
            return response()->json($admin[0]);
        }

        return response()->json(null, 404);
    }

    public function getAdmins()
    {
        $admins = DB::select('SELECT * FROM vw_AdminUsers');
        return response()->json($admins);
    }

    public function changeStudentPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:Students,Email',
            'newPassword' => 'required|min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/',
            'confirmNewPassword' => 'required|same:newPassword',
        ]);

        try {
            // Find the student by email
            $updated = DB::table('Students')
                ->where('Email', $request->email)
                ->update(['Password' => bcrypt($request->newPassword)]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Password updated successfully!']);
            }

            return response()->json(['success' => false, 'message' => 'Student not found or no changes made.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }
}
