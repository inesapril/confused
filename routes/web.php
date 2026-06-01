<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\ReturnPesananController;
use App\Http\Controllers\PenyesuaianPersediaanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Barang routes - Owner, Admin
    Route::middleware('role:Owner,Admin')->group(function () {
        Route::resource('barang', BarangController::class)->except(['show']);
        Route::get('/barang/export/excel',[BarangController::class, 'exportExcel'])->name('barang.export');
        Route::post('/barang/import/excel',[BarangController::class, 'importExcel'])->name('barang.import');
        Route::get('/barang/print-qr', [BarangController::class, 'qrForm'])->name('barang.qrForm');
        Route::post('/barang/download-qr', [BarangController::class, 'downloadQrPdf'])->name('barang.downloadQrPdf');
        Route::resource('supplier', SupplierController::class)->except(['show']);;
        Route::get('/supplier/export-barang',[SupplierController::class, 'exportBarang'])->name('supplier.exportBarang');
        Route::post('/supplier/import-barang',[SupplierController::class, 'importBarang'])->name('supplier.importBarang');
        Route::resource('reseller', ResellerController::class)->except(['show']);;
        Route::get('/reseller/export-barang',[ResellerController::class, 'exportBarang'])->name('reseller.exportBarang');
        Route::post('/reseller/import-barang',[ResellerController::class, 'importBarang'])->name('reseller.importBarang');
        Route::resource('toko', TokoController::class)->except(['show']);;
        Route::get('/toko/export-barang',[TokoController::class, 'exportBarang'])->name('toko.exportBarang');
        Route::post('/toko/import-barang',[TokoController::class, 'importBarang'])->name('toko.importBarang');
        Route::resource('barang_masuk', BarangMasukController::class);
        Route::resource('barang_keluar', BarangKeluarController::class);
        Route::resource('pesanan', PesananController::class);
        Route::resource('return_pesanan', ReturnPesananController::class);
        Route::resource('penyesuaian_persediaan', PenyesuaianPersediaanController::class);
        Route::resource('persediaan', PersediaanController::class);
        Route::get('/persediaan/export/pdf', [PersediaanController::class, 'exportPdf'])->name('persediaan.exportPdf');
        Route::get('/barang/qr',[BarangController::class, 'qrForm'])->name('barang.label.index');
        Route::post('/barang/qr/download',[BarangController::class, 'downloadQrPdf'])->name('barang.downloadQrPdf');
        Route::get('/notifications', [NotificationController::class, 'getNotifications']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    });

    // User routes - Owner only
    Route::middleware('role:Owner')->group(function () {
        Route::resource('user', UserController::class)->except(['show']);
        Route::get('user-roles', [UserController::class, 'roles'])->name('user.roles');
        Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');
        Route::resource('laporan', LaporanController::class);
        Route::get('/cashflow', [CashFlowController::class, 'index'])->name('cashflow.index');
        Route::get('/cashflow/export-pdf', [CashFlowController::class, 'exportPdf'])->name('cashflow.exportPdf');
    });
});

require __DIR__.'/auth.php';
