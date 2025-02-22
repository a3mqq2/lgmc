<?php

use App\Http\Controllers\Common\DoctorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Common\VaultController;
use App\Http\Controllers\Finance\HomeController;
use App\Http\Controllers\Common\TicketController;
use App\Http\Controllers\VaultTransferController;
use App\Http\Controllers\Common\InvoiceController;
use App\Http\Controllers\Common\TransactionController;
use App\Http\Controllers\Common\TransactionTypeController;
use App\Http\Controllers\TotalInvoiceController;

Route::prefix('finance')->name('finance.')->middleware('auth','permission:financial-branch,financial-administration')->group(function () {
   Route::get('/home', [HomeController::class, 'home'])->name('home');
   Route::resource("vaults", VaultController::class)->middleware('permission:financial-administration');
    Route::resource("transaction-types", TransactionTypeController::class);
    Route::resource("transactions", TransactionController::class);
    Route::post('/invoices/{invoice}/received', [InvoiceController::class, 'received'])->name('invoices.received');
    Route::post('invoices/{invoice}/relief', [InvoiceController::class, 'relief'])->name('invoices.relief');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::resource('invoices', InvoiceController::class)->only(['index', 'edit', 'update','destroy','create','store']);
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::resource('tickets', TicketController::class);
    Route::patch('/vault-transfers/{vaultTransfer}/approve', [VaultTransferController::class, 'approve'])->name('vault-transfers.approve');
    Route::patch('/vault-transfers/{vaultTransfer}/reject', [VaultTransferController::class, 'reject'])->name('vault-transfers.reject');
    Route::resource('vault-transfers', VaultTransferController::class);
    Route::get('profile/change-password', [ProfileController::class, 'change_password'])->name('profile.change-password');
    Route::post('profile/change-password', [ProfileController::class, 'change_password_store'])->name('profile.change-password');
    Route::get('doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('total-invoices/{doctor}/create', [TotalInvoiceController::class, 'show'])->name('total_invoices.create')->middleware('permission:total_invoices');
    Route::post('total-invoices/{doctor}/store', [TotalInvoiceController::class, 'store'])->name('total_invoices.store')->middleware('permission:total_invoices');
    Route::get('total-invoices', [TotalInvoiceController::class, 'index'])->name('total_invoices.index')->middleware('permission:total_invoices');
    Route::get('total-invoices/{invoice}/show', [TotalInvoiceController::class, 'show_invoice'])->name('total_invoices.show')->middleware('permission:total_invoices');
    Route::get('total-invoices/{invoice}/print', [TotalInvoiceController::class, 'print'])->name('total_invoices.print')->middleware('permission:total_invoices');
});