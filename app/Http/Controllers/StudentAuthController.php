<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student; // Import the Student model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class StudentAuthController extends Controller
{
    public function dashboard()
    {
        $student = Auth::guard('student')->user(); // Fetch the logged-in student
        return view('student.dashboard', compact('student'));
    }
    /**
     * Handle the login request for students.
     */
    public function login(Request $request)
    {
        // Validate login inputs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Fetch the student record by email
        $student = DB::table('Students')->where('Email', $credentials['email'])->first();

        if ($student && Hash::check($credentials['password'], $student->Password)) {
            // Log in the student
            Auth::guard('student')->loginUsingId($student->Student_ID);

            // Redirect to the dashboard
            return redirect()->route('student.dashboard');
        }

        // If authentication fails, return with an error
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    /**
     * Show the login form for students.
     */
    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    /**
     * Show the sign-up form for students.
     */
    public function showSignupForm()
    {
        return view('auth.student-signup'); // Ensure this Blade file exists in resources/views/auth/
    }

    /**
     * Handle the signup request for students.
     */
    public function signup(Request $request)
    {
        // Validate the signup form inputs
        $validatedData = $request->validate([
            'school_id' => 'required|unique:Students,School_ID',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'suffix' => 'nullable|string|max:10',
            'gender' => 'required|in:male,female,other,prefer-not-to-say',
            'program_id' => 'required|exists:Programs,Program_ID',
            'contact_number' => 'required|regex:/^09[0-9]{9}$/',
            'email' => 'required|email|unique:Students,Email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Call the stored procedure to insert the student
            DB::statement('EXEC AddStudentAccount 
                @Email = :email, 
                @School_ID = :school_id,    
                @Program_ID = :program_id, 
                @LastName = :last_name, 
                @FirstName = :first_name, 
                @MiddleName = :middle_name, 
                @Suffix = :suffix, 
                @Gender = :gender, 
                @ContactNumber = :contact_number, 
                @Password = :password', [
                'email' => $validatedData['email'],
                'school_id' => $validatedData['school_id'],
                'program_id' => $validatedData['program_id'],
                'last_name' => $validatedData['last_name'],
                'first_name' => $validatedData['first_name'],
                'middle_name' => $validatedData['middle_name'],
                'suffix' => $validatedData['suffix'],
                'gender' => $validatedData['gender'],
                'contact_number' => $validatedData['contact_number'],
                'password' => bcrypt($validatedData['password']), // Hash the password
            ]);

            // Redirect to login page with success message
            return redirect()->route('student.login')->with('success', 'Account created successfully! Please log in.');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Student Registration Error: ' . $e->getMessage());

            // Redirect back with error message
            return back()->with('error', 'An error occurred during registration. Please try again.');
        }
    }

    /**
     * Log out the student and destroy their session.
     */
    public function logout(Request $request)
    {
        // Log the user out
        Auth::guard('student')->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect()->route('student.login');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/|exists:students,Email',
        ]);

        // Use the "students" broker
        $status = Password::broker('students')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showProfile()
    {
        $student = Auth::guard('student')->user(); // Get the logged-in student's data
        return view('student.profile', compact('student')); // Pass it to the blade template
    }

    public function updateProfile(Request $request)
    {
        $student = Auth::guard('student')->user();

        // Validate the provided fields
        $validatedData = $request->validate([
            'last_name' => 'nullable|string|max:100',
            'first_name' => 'nullable|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'suffix' => 'nullable|string|in:None,Jr.,Sr.,II,III,IV,V',
            'program_id' => 'nullable|exists:Programs,Program_ID',
            'contact_number' => 'nullable|regex:/^09[0-9]{9}$/',
            'email' => 'nullable|email|unique:Students,Email,' . $student->Student_ID . ',Student_ID',
        ]);

        try {
            // Update only the provided fields
            if ($request->has('last_name')) {
                $student->LastName = $request->input('last_name');
            }
            if ($request->has('first_name')) {
                $student->FirstName = $request->input('first_name');
            }
            if ($request->has('middle_name')) {
                $student->MiddleName = $request->input('middle_name') ?: null;
            }
            if ($request->has('suffix')) {
                $student->Suffix = $request->input('suffix') ?: null;
            }
            if ($request->has('program_id')) {
                $student->Program_ID = $request->input('program_id');
            }
            if ($request->has('contact_number')) {
                $student->ContactNumber = $request->input('contact_number');
            }
            if ($request->has('email')) {
                $student->Email = $request->input('email');
            }
    
            // Update the UpdatedAt field manually
            $student->UpdatedAt = now(); // `now()` provides the current timestamp
    
            // Save the changes to the database
            $student->save();
    
            // Redirect back with a success message
            return redirect()->route('student.dashboard')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Profile Update Error: ' . $e->getMessage());
    
            // Redirect back with an error message
            return back()->with('error', 'An error occurred while updating your profile. Please try again.');
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $student = Auth::guard('student')->user();

        if (!Hash::check($request->current_password, $student->Password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $student->Password = Hash::make($request->new_password);
        $student->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $student = Auth::guard('student')->user(); // Get the authenticated student

        if ($student) {
            // Perform the deletion
            $student->delete();

            // Logout the user
            Auth::guard('student')->logout();

            // Redirect to the welcome page with a success message
            return redirect()->route('student.login')->with('success', 'Your account has been successfully deleted.');
        }

        return redirect()->back()->with('error', 'Unable to delete your account. Please try again.');
    }

}
