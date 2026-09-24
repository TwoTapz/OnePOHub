<?php

namespace App\Livewire;

use App\Support\PaymentStatus;
use App\Support\PurchaseOrderStore;
use Livewire\Component;

class PurchaseOrderView extends Component
{
    public string $id;
    public ?array $po = null;
    public bool $showPaymentForm = false;
    public string $paymentAmount = '';
    public string $paymentError = '';

    public function mount(string $id): void
    {
        $this->id = $id;
        $this->po = app(PurchaseOrderStore::class)->find($id);
        if (!$this->po) {
            abort(404);
        }
    }

    public function recordPayment(): void
    {
        $this->paymentError = '';
        $amount = (float) $this->paymentAmount;

        if ($amount <= 0) {
            $this->paymentError = 'Amount must be greater than zero.';
            return;
        }

        $balance = ($this->po['total'] ?? 0) - ($this->po['amount_paid'] ?? 0);
        if ($amount > $balance) {
            $amount = $balance;
        }

        $this->po['amount_paid'] = ($this->po['amount_paid'] ?? 0) + $amount;
        app(PurchaseOrderStore::class)->save($this->po);
        $this->po = app(PurchaseOrderStore::class)->find($this->id);
        $this->paymentAmount = '';
        $this->showPaymentForm = false;
    }

    public function deletePo(): void
    {
        app(PurchaseOrderStore::class)->delete($this->id);
        $this->redirect(route('purchase-orders.index'), navigate: true);
    }

    public function render()
    {
        $status = $this->po ? PaymentStatus::for($this->po) : ['label' => '', 'color' => 'gray'];
        $total = $this->po['total'] ?? 0;
        $paid = $this->po['amount_paid'] ?? 0;
        $balance = $total - $paid;
        $progress = $total > 0 ? min(100, round(($paid / $total) * 100)) : 0;

        return view('livewire.purchase-order-view', compact('status', 'total', 'paid', 'balance', 'progress'))
            ->layout('layouts.app');
    }
}
