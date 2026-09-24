<?php

namespace App\Livewire;

use App\Support\PaymentStatus;
use App\Support\PurchaseOrderStore;
use Livewire\Component;

class PurchaseOrderList extends Component
{
    public string $search = '';
    public string $filterCategory = '';
    public string $filterStatus = '';
    public string $filterType = '';

    public function deleteOrder(string $id): void
    {
        app(PurchaseOrderStore::class)->delete($id);
    }

    public function getFilteredOrdersProperty()
    {
        return app(PurchaseOrderStore::class)->all()
            ->filter(function ($po) {
                if ($this->search) {
                    $q = strtolower($this->search);
                    if (!str_contains(strtolower($po['po_number']), $q) &&
                        !str_contains(strtolower($po['client_name']), $q)) {
                        return false;
                    }
                }
                if ($this->filterCategory && ($po['category'] ?? '') !== $this->filterCategory) {
                    return false;
                }
                if ($this->filterType && ($po['client_type'] ?? '') !== $this->filterType) {
                    return false;
                }
                if ($this->filterStatus) {
                    $status = PaymentStatus::for($po);
                    $label = $status['label'];
                    if ($this->filterStatus === 'Fully Paid' && $label !== 'Fully Paid') return false;
                    if ($this->filterStatus === 'Awaiting Deposit' && $label !== 'Awaiting Deposit') return false;
                    if ($this->filterStatus === 'Pending Payment' && $label !== 'Pending Payment') return false;
                    if ($this->filterStatus === 'Deposit Paid' && !str_starts_with($label, 'Deposit Paid')) return false;
                }
                return true;
            })
            ->sortByDesc('po_date')
            ->values();
    }

    public function getCategoriesProperty()
    {
        return app(PurchaseOrderStore::class)->all()
            ->pluck('category')
            ->unique()
            ->sort()
            ->values();
    }

    public function render()
    {
        return view('livewire.purchase-order-list', [
            'orders' => $this->filteredOrders,
            'categories' => $this->categories,
        ])->layout('layouts.app');
    }
}
