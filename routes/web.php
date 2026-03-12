<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataBankController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
    'testAccounts' => [
        ['email' => 'test@example.com', 'role' => 'User'],
        ['email' => 'other@example.com', 'role' => 'User'],
        ['email' => 'admin@example.com', 'role' => 'Admin'],
    ],
    'testPassword' => 'password',
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('data-bank', [DataBankController::class, 'index'])->name('data-bank.index');
    Route::get('data-bank/export', [DataBankController::class, 'export'])->name('data-bank.export');
});

require __DIR__.'/settings.php';
