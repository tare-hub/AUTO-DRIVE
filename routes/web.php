<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\PurchaseController as CustomerPurchaseController;
use App\Http\Controllers\Customer\TicketController as CustomerTicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Rental\RentalDashboardController;
use App\Http\Controllers\Sales\SalesDashboardController;
use App\Http\Controllers\Support\SupportDashboardController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Automotive Website Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

Route::get('/rent', [VehicleController::class, 'rentIndex'])->name('vehicles.rent');
Route::get('/buy', [VehicleController::class, 'buyIndex'])->name('vehicles.buy');
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

/*
|--------------------------------------------------------------------------
| Authentication & Role Switcher Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/demo-login/{role}', [AuthController::class, 'demoLogin'])->name('demo.login');

/*
|--------------------------------------------------------------------------
| Authenticated Customer & Account Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('customer.profile');
    Route::put('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('customer.profile.update');

    // Rentals
    Route::get('/my-bookings', [CustomerDashboardController::class, 'bookings'])->name('customer.bookings');
    Route::post('/bookings', [CustomerBookingController::class, 'store'])->name('customer.bookings.store');
    Route::post('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('customer.bookings.cancel');

    // Purchases
    Route::get('/my-purchases', [CustomerDashboardController::class, 'purchases'])->name('customer.purchases');
    Route::post('/purchase-requests', [CustomerPurchaseController::class, 'store'])->name('customer.purchases.store');

    // Payments & Receipts
    Route::get('/my-payments', [CustomerDashboardController::class, 'payments'])->name('customer.payments');
    Route::post('/payments/booking/{booking}', [PaymentController::class, 'payBooking'])->name('payments.booking');
    Route::post('/payments/transaction/{transaction}', [PaymentController::class, 'payTransaction'])->name('payments.transaction');
    Route::get('/receipts/{payment}', [PaymentController::class, 'showReceipt'])->name('payments.receipt');

    // Support Tickets
    Route::get('/my-tickets', [CustomerTicketController::class, 'index'])->name('customer.tickets.index');
    Route::get('/my-tickets/create', [CustomerTicketController::class, 'create'])->name('customer.tickets.create');
    Route::post('/my-tickets', [CustomerTicketController::class, 'store'])->name('customer.tickets.store');
    Route::get('/my-tickets/{ticket}', [CustomerTicketController::class, 'show'])->name('customer.tickets.show');
    Route::post('/my-tickets/{ticket}/reply', [CustomerTicketController::class, 'reply'])->name('customer.tickets.reply');
});

/*
|--------------------------------------------------------------------------
| Administrator Management Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Vehicles CRUD
    Route::resource('vehicles', AdminVehicleController::class);
    Route::patch('/vehicles/{vehicle}/status', [AdminVehicleController::class, 'toggleStatus'])->name('vehicles.status');

    // Users & Roles Management
    Route::resource('users', AdminUserController::class);
    Route::patch('/users/{user}/status', [AdminUserController::class, 'toggleStatus'])->name('users.status');

    // Reports & CSV Export
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');
});

/*
|--------------------------------------------------------------------------
| Rental Manager Workspace Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:rental_manager'])->prefix('rental')->name('rental.')->group(function () {
    Route::get('/dashboard', [RentalDashboardController::class, 'index'])->name('dashboard');
    Route::post('/bookings/{booking}/approve', [RentalDashboardController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [RentalDashboardController::class, 'reject'])->name('bookings.reject');

    // Inspections
    Route::get('/bookings/{booking}/pickup', [RentalDashboardController::class, 'showPickupInspection'])->name('inspections.pickup');
    Route::post('/bookings/{booking}/pickup', [RentalDashboardController::class, 'storePickupInspection'])->name('inspections.pickup.store');
    Route::get('/bookings/{booking}/return', [RentalDashboardController::class, 'showReturnInspection'])->name('inspections.return');
    Route::post('/bookings/{booking}/return', [RentalDashboardController::class, 'storeReturnInspection'])->name('inspections.return.store');
});

/*
|--------------------------------------------------------------------------
| Sales Manager Workspace Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:sales_manager'])->prefix('sales')->name('sales.')->group(function () {
    Route::get('/dashboard', [SalesDashboardController::class, 'index'])->name('dashboard');
    Route::post('/requests/{request}/review', [SalesDashboardController::class, 'review'])->name('requests.review');
    Route::get('/requests/{request}/finalize', [SalesDashboardController::class, 'showFinalize'])->name('requests.finalize');
    Route::post('/requests/{request}/finalize', [SalesDashboardController::class, 'finalize'])->name('requests.finalize.store');
});

/*
|--------------------------------------------------------------------------
| Customer Support Workspace Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer_support'])->prefix('support')->name('support.')->group(function () {
    Route::get('/dashboard', [SupportDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tickets/{ticket}', [SupportDashboardController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [SupportDashboardController::class, 'reply'])->name('tickets.reply');
    Route::patch('/tickets/{ticket}/status', [SupportDashboardController::class, 'updateStatus'])->name('tickets.status');
});
