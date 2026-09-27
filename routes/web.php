<?php

use App\Http\Controllers\BillPaymentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EloadController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoreOnboardingController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('store/onboarding', [StoreOnboardingController::class, 'index'])->name('store.onboarding');
    Route::post('store/onboarding', [StoreOnboardingController::class, 'store'])->name('store.onboarding.store');

    Route::get('dashboard', function () {
        $store = auth()->user()?->stores()->first();

        if (! $store) {
            return redirect()->route('store.onboarding');
        }

        return app(DashboardController::class)->index(request());
    })->name('dashboard');

    Route::get('products', [ProductController::class, 'index'])->name('products');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');

    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::get('customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('employees', [EmployeeController::class, 'index'])->name('employees');
    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::post('employees/{employee}/payments', [EmployeeController::class, 'storePayment'])->name('employees.payments.store');
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('eload', [EloadController::class, 'index'])->name('eload');
    Route::post('eload', [EloadController::class, 'store'])->name('eload.store');
    Route::get('bills', [BillPaymentController::class, 'index'])->name('bills');
    Route::post('bills', [BillPaymentController::class, 'store'])->name('bills.store');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('reports', [ReportController::class, 'index'])->name('reports');
    Route::get('pos', [PosController::class, 'index'])->name('pos');
    Route::post('pos', [PosController::class, 'store'])->name('pos.store');
});

require __DIR__.'/settings.php';
