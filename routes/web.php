<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\EventController as AdminEventController;

// ─── Public Auth Routes ───────────────────────────────────────
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// Default redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── User Routes (auth protected) ────────────────────────────
Route::middleware(['auth.check'])->group(function () {
    // Events listing + detail
    Route::get('/events',          [BookingController::class, 'index'])->name('events.index');
    Route::get('/events/{id}',     [BookingController::class, 'show'])->name('events.show');
    // Real-time seat count API endpoint
    Route::get('/events/{id}/seats', [BookingController::class, 'getSeatCount'])->name('events.seats');

    // Booking
    Route::post('/events/{id}/book', [BookingController::class, 'book'])->name('events.book');
    Route::get('/my-bookings',       [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// ─── Admin Routes ─────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth.check', 'is.admin'])->group(function () {
    Route::get('/dashboard',        [AdminEventController::class, 'index'])->name('admin.dashboard');
    Route::get('/events',           [AdminEventController::class, 'index'])->name('admin.events.index');
    Route::get('/events/create',    [AdminEventController::class, 'create'])->name('admin.events.create');
    Route::post('/events',          [AdminEventController::class, 'store'])->name('admin.events.store');
    Route::get('/events/{id}/edit', [AdminEventController::class, 'edit'])->name('admin.events.edit');
    Route::post('/events/{id}',     [AdminEventController::class, 'update'])->name('admin.events.update');
    Route::post('/events/{id}/delete', [AdminEventController::class, 'destroy'])->name('admin.events.destroy');
});