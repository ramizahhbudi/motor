<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\AdminCashierController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\MechanicDashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserDataController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DecryptionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController; // Tambahkan ini di atas

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login'); // Redirect root to login
});
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'mekanik') {
        return redirect()->route('mechanic.dashboard');
    } else {
        return redirect()->route('user_home'); // Arahkan ke user_home
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes for Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminHomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings');
    Route::get('/admin/inventory', [InventoryController::class, 'index'])->name('admin.inventory');
    Route::patch('/admin/inventory/{id}', [InventoryController::class, 'updateStock'])->name('admin.inventory.update');
    Route::get('/admin/cashier', [AdminCashierController::class, 'index'])->name('admin.cashier');
    Route::post('/admin/cashier/process', [AdminCashierController::class, 'process'])->name('admin.cashier.process');
    Route::post('/admin/cashier/finalize/{id}', [AdminCashierController::class, 'finalizePayment'])->name('admin.cashier.finalize');
    Route::get('/admin/cashier/receipt/{id}', [AdminCashierController::class, 'receipt'])->name('admin.cashier.receipt');
});

// Routes for Mechanic
Route::middleware(['auth', 'role:mekanik'])->group(function () {
    Route::get('/mekanik', [MechanicDashboardController::class, 'index'])->name('mechanic.dashboard');
});

// Routes for General User
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/', function () {
        return view('user.home');
    })->name('user_home');

    // Motor Routes
    Route::prefix('motor')->group(function () {
        Route::get('/', [MotorController::class, 'index'])->name('motor.index'); // Daftar Motor
        Route::get('/create', [MotorController::class, 'create'])->name('motor.create'); // Tambah Motor
        Route::post('/', [MotorController::class, 'store'])->name('motor.store'); // Simpan Motor
        Route::get('/{motor}/edit', [MotorController::class, 'edit'])->name('motor.edit'); // Edit Motor
        Route::put('/{motor}', [MotorController::class, 'update'])->name('motor.update'); // Update Motor
        Route::delete('/{motor}', [MotorController::class, 'destroy'])->name('motor.destroy'); // Hapus Motor
        Route::post('/{motor}/restore/{id}', [MotorController::class, 'restore'])->name('motor.restore');
        Route::delete('/motor/force-delete/{id}', [MotorController::class, 'forceDelete'])->name('motor.forceDelete');
    });

    // Services Routes
    Route::prefix('services')->group(function () {
        Route::get('/', function () {
            return view('user.services');
        })->name('services');
    });

    // Booking Routes
    Route::get('/booking', [BookingController::class, 'index'])->name('user.services'); // Halaman daftar layanan
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store'); // Store booking
    Route::get('/booking/confirmation/{id}', [BookingController::class, 'confirmation'])->name('user.bookingconfirmation'); // Booking confirmation

    // AJAX Routes for Bookings and Specifications
    Route::get('/services/{id}/specifications', [BookingController::class, 'getSpecifications']); // Get specifications of a service (AJAX)
    Route::get('/user/bookings', [BookingController::class, 'getUserBookings'])->name('user.bookings'); // Get user's bookings (AJAX)
});

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   Route::get('/lihat-data', [DecryptionController::class, 'showForm'])
         ->name('user.view_data.form');
    
    Route::post('/lihat-data', [DecryptionController::class, 'decryptData'])
         ->name('user.view_data.decrypt');
});

// Include Authentication Routes
require __DIR__.'/auth.php';

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1');
