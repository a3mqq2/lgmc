<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Common\VaultController;
use App\Http\Controllers\Finance\HomeController;
use App\Http\Controllers\TotalInvoiceController;
use App\Http\Controllers\Common\DoctorController;
use App\Http\Controllers\Common\ReportController;
use App\Http\Controllers\Common\TicketController;
use App\Http\Controllers\VaultTransferController;
use App\Http\Controllers\Common\InvoiceController;
use App\Http\Controllers\FinancialCategoryController;
use App\Http\Controllers\Common\TransactionController;
use App\Http\Controllers\Common\TransactionTypeController;

Route::prefix('finance')->name('finance.')->middleware('auth', 'check.finance.permission')->group(function () {
   Route::get('/home', [HomeController::class, 'home'])->name('home');
   Route::patch('vaults/{vault}/close-custody', [VaultController::class, 'closeCustody'])->name('vaults.close-custody');
   Route::resource("vaults", VaultController::class);
   Route::resource('financial-categories', FinancialCategoryController::class);

   Route::resource("transactions", TransactionController::class)->except('show');
   Route::get('/transactions/report', [TransactionController::class, 'report'])->name('transactions.report');
   Route::get('/transactions/report/print', [TransactionController::class, 'reportPrint'])->name('transactions.report.print');
   Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');

    Route::post('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices/{invoice}/received', [InvoiceController::class, 'received'])->name('invoices.received');
    Route::post('invoices/{invoice}/relief', [InvoiceController::class, 'relief'])->name('invoices.relief');
    Route::get('invoices/print-list', [InvoiceController::class, 'print_list'])->name('invoices.print-list');
    Route::get('/doctors/print-list', [DoctorController::class, 'printListFinance'])->name('doctors.print_list');
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
    Route::get('total-invoices/{doctor}/create', [TotalInvoiceController::class, 'show'])->name('total_invoices.create');
    Route::post('total-invoices/{doctor}/store', [TotalInvoiceController::class, 'store'])->name('total_invoices.store');
    Route::get('total-invoices', [TotalInvoiceController::class, 'index'])->name('total_invoices.index');
    Route::get('total-invoices/{invoice}/show', [TotalInvoiceController::class, 'show_invoice'])->name('total_invoices.show');
    Route::get('total-invoices/{invoice}/print', [TotalInvoiceController::class, 'print'])->name('total_invoices.print');


        // ================ REPORTS ================ //
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
        Route::get('/reports/licences', [ReportController::class, 'licences'])->name('reports.licences');
        Route::get('/reports/licences/print', [ReportController::class, 'licences_print'])->name('reports.licences_print');
        Route::get('/reports/transactions/print', [ReportController::class, 'transactions_print'])->name('reports.transactions_print');


        // =============== REPORTS ================ //
    
});