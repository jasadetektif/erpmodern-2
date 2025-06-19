<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Master\Analysis\Form;

// Route untuk mengganti bahasa
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Halaman utama
Route::view('/', 'welcome');

// Route yang membutuhkan autentikasi
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard

    Route::get('dashboard', \App\Livewire\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    
    // Profil Pengguna
    Route::view('profile', 'profile')->name('profile');

    // Manajemen Proyek (dilindungi dengan 'view projects')
    Route::get('projects', \App\Livewire\Projects\Index::class)
        ->middleware('can:view projects')
        ->name('projects.index');

    Route::get('projects/{project}', \App\Livewire\Projects\Show::class)
        ->middleware('can:view projects')
        ->name('projects.show');

    // Pengadaan (dilindungi dengan 'view procurement' dan 'manage suppliers')
    Route::get('procurement/purchase-requests', \App\Livewire\Procurement\PurchaseRequest\Index::class)
        ->middleware('can:view procurement')
        ->name('procurement.pr.index');

    Route::get('procurement/purchase-orders', \App\Livewire\Procurement\PurchaseOrder\Index::class)
        ->middleware('can:view procurement')
        ->name('procurement.po.index');

    Route::get('procurement/suppliers', \App\Livewire\Procurement\Supplier\Index::class)
        ->middleware('can:manage suppliers')
        ->name('procurement.suppliers.index');
    
    // Manajemen Pengguna (dilindungi dengan 'manage users')
    Route::get('users', \App\Livewire\Users\Index::class)
        ->middleware('can:manage users')
        ->name('users.index');

    Route::get('procurement/goods-receipts', \App\Livewire\Procurement\GoodsReceipt\Index::class)
        ->middleware('can:view procurement')
        ->name('procurement.gr.index');

    Route::get('inventory', \App\Livewire\Inventory\Index::class)
        ->middleware(['auth', 'verified', 'can:view procurement']) // Kita gunakan hak akses yang sama
        ->name('inventory.index');

    Route::get('inventory/usage', \App\Livewire\Inventory\Usage\Index::class)
        ->middleware(['auth', 'verified', 'can:view procurement'])
        ->name('inventory.usage.index');

    Route::get('procurement/purchase-requests/{purchaseRequest}', \App\Livewire\Procurement\PurchaseRequest\Show::class)
        ->middleware(['auth', 'verified', 'can:view procurement'])
        ->name('procurement.pr.show');
    
    Route::get('procurement/purchase-orders/{purchaseOrder}', \App\Livewire\Procurement\PurchaseOrder\Show::class)
        ->middleware(['auth', 'verified', 'can:view procurement'])
        ->name('procurement.po.show');
    
    Route::get('procurement/goods-receipts/{goodsReceipt}', \App\Livewire\Procurement\GoodsReceipt\Show::class)
        ->middleware(['auth', 'verified', 'can:view procurement'])
        ->name('procurement.gr.show');
    
    Route::get('hr/employees', \App\Livewire\HR\Employee\Index::class)
        ->middleware(['auth', 'verified', 'can:manage hr'])
        ->name('hr.employees.index');
    Route::get('hr/employees/{employee}', \App\Livewire\HR\Employee\Show::class)
        ->middleware(['auth', 'verified', 'can:manage hr'])
        ->name('hr.employees.show');
    
    Route::get('hr/payrolls', \App\Livewire\HR\Payroll\Index::class)
        ->middleware(['auth', 'verified', 'can:manage hr'])
        ->name('hr.payrolls.index');
    
    Route::get('hr/payrolls/{payroll}', \App\Livewire\HR\Payroll\Show::class)
        ->middleware(['auth', 'verified', 'can:manage hr'])
        ->name('hr.payrolls.show');

    Route::get('hr/payslips/{payslip}', \App\Livewire\HR\Payslip\Show::class)
        ->middleware(['auth', 'verified', 'can:manage hr'])
        ->name('hr.payslips.show');

    Route::get('hr/payslips/{payslip}/pdf', [\App\Http\Controllers\PdfController::class, 'generatePayslipPdf'])
        ->middleware(['auth', 'verified', 'can:manage hr'])
        ->name('hr.payslips.pdf');

    Route::get('procurement/purchase-orders/{purchaseOrder}/pdf', [\App\Http\Controllers\PdfController::class, 'generatePoPdf'])
        ->middleware(['auth', 'verified', 'can:view procurement'])
        ->name('procurement.po.pdf');
    
    Route::get('finance/invoices', \App\Livewire\Finance\Invoice\Index::class)
        ->middleware(['auth', 'verified', 'can:view finance'])
        ->name('finance.invoices.index');

    Route::get('finance/payments', \App\Livewire\Finance\Payment\Index::class)
        ->middleware(['auth', 'verified', 'can:view finance'])
        ->name('finance.payments.index');

    Route::get('reports/project-pl', \App\Livewire\Reports\ProjectPl\Index::class)
        ->middleware(['auth', 'verified', 'can:view reports'])
        ->name('reports.project-pl.index');

    Route::get('reports/project-pl/{project}', \App\Livewire\Reports\ProjectPl\Show::class)
        ->middleware(['auth', 'verified', 'can:view reports'])
        ->name('reports.project-pl.show');

    Route::get('reports/project-pl/{project}/pdf', [\App\Http\Controllers\PdfController::class, 'generateProjectPlDetailPdf'])
        ->middleware(['auth', 'verified', 'can:view reports'])
        ->name('reports.project-pl.detail.pdf');

    Route::get('sales/quotations', \App\Livewire\Sales\Quotation\Index::class)
        ->middleware(['auth', 'verified', 'can:manage quotations'])
        ->name('sales.quotations.index');

    Route::get('sales/quotations/{quotation}', \App\Livewire\Sales\Quotation\Show::class)
        ->middleware(['auth', 'verified', 'can:manage quotations'])
        ->name('sales.quotations.show');

    Route::get('finance/client-invoices', \App\Livewire\Finance\ClientInvoice\Index::class)
        ->middleware(['auth', 'verified', 'can:view finance'])
        ->name('finance.client-invoices.index');
    
    Route::get('finance/client-invoices/{clientInvoice}', \App\Livewire\Finance\ClientInvoice\Show::class)
        ->middleware(['auth', 'verified', 'can:view finance'])
        ->name('finance.client-invoices.show');

    Route::get('finance/client-invoices/{clientInvoice}/pdf', [\App\Http\Controllers\PdfController::class, 'generateClientInvoicePdf'])
        ->middleware(['auth', 'verified', 'can:view finance'])
        ->name('finance.client-invoices.pdf');


    Route::get('finance/client-payments', \App\Livewire\Finance\ClientPayment\Index::class)->name('finance.client-payments.index');
    Route::get('finance/client-payments/{clientPayment}', \App\Livewire\Finance\ClientPayment\Show::class)->name('finance.client-payments.show');

    Route::get('assets', \App\Livewire\Asset\Index::class)
        ->middleware(['auth', 'verified', 'can:manage assets'])
        ->name('assets.index');

    Route::get('assets/{asset}', \App\Livewire\Asset\Show::class)
        ->middleware(['auth', 'verified', 'can:manage assets'])
        ->name('assets.show');

    Route::get('hr/attendances', \App\Livewire\HR\Attendance\Index::class)
        ->middleware(['auth', 'verified', 'can:manage hr'])
        ->name('hr.attendances.index');

    Route::get('reports/cash-flow', \App\Livewire\Reports\CashFlow\Index::class)
        ->middleware(['auth', 'verified', 'can:view reports'])
        ->name('reports.cash-flow.index');

    Route::get('reports/cash-flow/pdf', [\App\Http\Controllers\PdfController::class, 'generateCashFlowPdf'])
        ->middleware(['auth', 'verified', 'can:view reports'])
        ->name('reports.cash-flow.pdf');

    Route::get('sales/clients', \App\Livewire\Sales\Client\Index::class)
        ->middleware(['auth', 'verified', 'can:manage clients'])
        ->name('sales.clients.index');

    Route::get('reports/ap-aging', \App\Livewire\Reports\ApAging\Index::class)
        ->middleware(['auth', 'verified', 'can:view reports'])
        ->name('reports.ap-aging.index');

    Route::get('reports/ar-aging', \App\Livewire\Reports\ArAging\Index::class)
        ->middleware(['auth', 'verified', 'can:view reports'])
        ->name('reports.ar-aging.index');
    
    Route::get('settings/quotes', \App\Livewire\Settings\Quote\Index::class)
        ->middleware(['auth', 'verified', 'can:manage settings'])
        ->name('settings.quotes.index');

    Route::get('settings/announcements', \App\Livewire\Settings\Announcement\Index::class)
        ->middleware(['auth', 'verified', 'can:manage settings'])
        ->name('settings.announcements.index');
    
    Route::get('master/materials', \App\Livewire\Master\Material\Index::class)
        ->middleware(['auth', 'verified', 'can:manage master_data'])
        ->name('master.materials.index');

    Route::get('master/labors', \App\Livewire\Master\Labor\Index::class)
        ->middleware(['auth', 'verified', 'can:manage master_data'])
        ->name('master.labors.index');



    Route::prefix('master/ahs')->name('master.ahs.')->group(function () {
    Route::get('/', \App\Livewire\Master\Analysis\Index::class)->name('index');
    Route::get('/form', Form::class)->name('form'); // ini penting!
    Route::get('/form/{analysis}', Form::class)->name('form.edit'); // jika edit juga via Livewire
 
});
});

require __DIR__.'/auth.php';
