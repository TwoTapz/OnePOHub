<?php

use App\Livewire\Dashboard;
use App\Livewire\PurchaseOrderList;
use App\Livewire\PurchaseOrderView;
use App\Livewire\PurchaseOrderForm;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/dashboard'));
Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/purchase-orders', PurchaseOrderList::class)->name('purchase-orders.index');
Route::get('/purchase-orders/create', PurchaseOrderForm::class)->name('purchase-orders.create');
Route::get('/purchase-orders/{id}/edit', PurchaseOrderForm::class)->name('purchase-orders.edit');
Route::get('/purchase-orders/{id}', PurchaseOrderView::class)->name('purchase-orders.show');
Route::get('/quotations', fn() => view('quotations'))->name('quotations');
Route::get('/invoices', fn() => view('invoices'))->name('invoices');
