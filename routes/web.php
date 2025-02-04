<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookInventoryController;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\AdminReservationController;

// Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Student Welcome Page Route
Route::get('/student/welcome', function () {
    return view('student.student-welcome'); // Path to your Blade file
})->name('student.welcome');

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'getLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'postLogin'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

    // Default Login Route (Redirect to Student Login)
    Route::get('/login', function () {
        return redirect()->route('student.login');
    })->name('login'); // Ensures default login route exists

// Student Authentication Routes
Route::prefix('student')->group(function () {
    // Login Routes
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('student.login.post');

    // Logout Route
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

    // Signup Routes
    Route::get('/signup', [StudentAuthController::class, 'showSignupForm'])->name('student.signup');
    Route::post('/signup', [StudentAuthController::class, 'signup'])->name('student.signup.post');

    // Forgot Password Routes
    Route::get('/password/request', [StudentAuthController::class, 'showForgotPasswordForm'])->name('student.password.request');
    Route::post('/password/request', [StudentAuthController::class, 'sendResetLink'])->name('student.password.email');

    // Dashboard (requires authentication)
    Route::middleware('auth:student')->group(function () {
        Route::get('/dashboard', function () {
            return view('student.dashboard'); // resources/views/student/dashboard.blade.php
        })->name('student.dashboard');
    });
});

Route::middleware('auth:student')->group(function () {
    // Library and Book Routes
    Route::get('/library', [BookController::class, 'index'])->name('student.library');
    Route::get('/library/search', [BookController::class, 'search'])->name('library.search');

Route::middleware('auth:student')->group(function () {
    Route::get('/reservation', function () {
        return view('student.reservation'); // Ensure this blade file exists
    })->name('student.reservation');
});

Route::middleware('auth:student')->get('/student/dashboard', function () {
    $student = Auth::guard('student')->user(); // Fetch the logged-in student's record
    return view('student.dashboard', compact('student')); // Pass the student data to the view
})->name('student.dashboard');

Route::middleware('auth:student')->group(function () {
    Route::get('/profile', function () {
        return view('student.profile'); // Ensure this blade file exists
    })->name('student.profile');
});

Route::prefix('student')->middleware('auth:student')->group(function () {
    Route::get('/profile/edit', function () {
        return view('student.edit-profile'); // Blade file for editing the profile
    })->name('student.edit.profile');
});

// Group routes for authenticated students under a single middleware
Route::middleware('auth:student')->group(function () {
    // Profile routes
    Route::get('/student/profile', [StudentAuthController::class, 'showProfile'])->name('student.profile');

Route::middleware('auth:student')->group(function () {
    Route::put('/student/profile', [StudentAuthController::class, 'updateProfile'])->name('student.updateProfile');
});

    // Change Password route
    Route::post('/student/change-password', [StudentAuthController::class, 'updatePassword'])->name('student.updatePassword');
});

Route::middleware('auth:student')->post('/student/delete-account', [StudentAuthController::class, 'deleteAccount'])->name('student.deleteAccount');


    // Reservation Page
    Route::get('/reservation', function () {
        $student = Auth::guard('student')->user();
        return view('student.reservation', compact('student'));
    })->name('student.reservation');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login'); // Admin login form
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post'); // Admin login action
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout'); // Admin logout action
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/user-management', [AdminController::class, 'userManagement'])->name('admin.user-management');
});

Route::get('/reports', function () {
    return view('admin.report-page');
})->name('reports.page');

Route::get('/reservation-handling', function () {
    return view('admin.reservation-handling');
})->name('reservation-handling.page');

Route::get('/book-inventory', function () {
    return view('admin.book-inventory');
})->name('book-inventory.page');

Route::get('/books', [BookController::class, 'index'])->name('books.index');

//reserve-book
Route::middleware('auth:student')->group(function () {
    // Book reservation route
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
});

Route::middleware('auth:student')->get('/student/reservations', [ReservationController::class, 'getStudentReservations'])
    ->name('student.reservations.list');


//COUNT
Route::middleware('auth:student')->get('/student/confirmed-reservations', [ReservationController::class, 'getConfirmedReservations'])
    ->name('student.confirmed.reservations');

    Route::middleware('auth:student')->get('/student/overtime-reservations',
    [ReservationController::class, 'countOvertimeReservations']
)->name('student.overtime.reservations');

//LIST
// Add this route with your other student routes
Route::middleware('auth:student')->get('/student/reservations/all',
    [ReservationController::class, 'getAllReservations'])
    ->name('student.reservations.all');
// Add this with your other student routes
Route::middleware('auth:student')->get('/student/reservations/reserved',
    [ReservationController::class, 'getReservedBooks'])
    ->name('student.reservations.reserved');
Route::middleware('auth:student')->get('/student/returned-books/{studentId}',
    [ReservationController::class, 'getReturnedBooks'])
    ->name('student.reservations.returned');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/book-inventory', [BookInventoryController::class, 'index'])->name('admin.book-inventory');
});

Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/user-management', [AdminController::class, 'userManagement'])->name('admin.user-management');
    Route::post('/add-admin', [AdminController::class, 'addAdmin'])->name('admin.add');
    Route::post('/admin/update-status/{id}', [AdminController::class, 'updateStatus']);
});

Route::middleware(['auth:admin'])->group(function () {
    // ... your existing routes ...

    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/admin/profile/data', [AdminController::class, 'getAdminProfile'])->name('admin.profile.data');
    Route::put('/admin/profile/password', [AdminController::class, 'updatePassword'])->name('admin.profile.password.update');
});

Route::get('/get-students-list', [AdminController::class, 'getStudentsList'])->name('admin.students.list');

Route::get('/get-admin-details/{schoolId}', [AdminController::class, 'getAdminDetails'])->name('admin.details');

Route::get('/admin/get-admins', [AdminController::class, 'getAdmins'])->name('admin.getAdmins');

Route::get('/admin/reservation-report', [ReservationController::class, 'getReservationReport'])
    ->name('admin.reservation.report')
    ->middleware('auth:admin');

Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
        // Reservation handling routes
    Route::get('/admin/reservations', [AdminReservationController::class, 'index'])
            ->name('admin.reservations.index');

    Route::post('/reservations/{id}/confirm', [AdminReservationController::class, 'confirmReservation'])
            ->name('admin.reservations.confirm')
            ->where('id', '[0-9]+');

    Route::post('/reservations/{id}/cancel', [AdminReservationController::class, 'cancelReservation'])
            ->name('admin.reservations.cancel')
            ->where('id', '[0-9]+');

    Route::delete('/reservations/{id}', [AdminReservationController::class, 'deleteReservation'])
            ->name('admin.reservations.delete')
            ->where('id', '[0-9]+');

    Route::get('/reservations/filtered', [AdminReservationController::class, 'getFilteredReservations'])
            ->name('admin.reservations.filtered');

    Route::post('/reservations/{id}/return', [AdminReservationController::class, 'returnBook'])
            ->name('admin.reservations.return');

    Route::post('/reservations/{id}/overtime', [AdminReservationController::class, 'markOvertime'])
            ->name('admin.reservations.overtime');
});

Route::get('/admin/check-session', 'AdminController@checkSession')->name('admin.check-session');

Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('/admin/total-reserved-books', [AdminController::class, 'fetchTotalReservedBooks']);

Route::get('/admin/total-available-books', [AdminController::class, 'getTotalAvailableBooks']);
Route::get('/admin/dashboard', [AdminController::class, 'loadAdminDashboard'])->name('admin.dashboard');

Route::get('/admin/weekly-reservations', [AdminController::class, 'getWeeklyReservations']);

Route::post('/admin/change-student-password', [AdminController::class, 'changeStudentPassword']);
