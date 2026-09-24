<?php

namespace App\Livewire;

use App\Support\PaymentStatus;
use App\Support\PurchaseOrderStore;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $store = app(PurchaseOrderStore::class);
        $orders = $store->all();

        // KPIs
        $totalCount = $orders->count();
        $totalValue = $orders->sum('total');
        $totalCollected = $orders->sum('amount_paid');
        $totalOutstanding = $totalValue - $totalCollected;

        // Recent POs
        $recentPos = $orders->sortByDesc('po_date')->take(6)->values();

        // --- Chart data ---

        // 1. Sales over last 6 months
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('Y-m'));
        }
        $salesByMonth = $months->mapWithKeys(function ($month) use ($orders) {
            $filtered = $orders->filter(fn($po) => str_starts_with($po['po_date'], $month));
            return [$month => [
                'value' => $filtered->sum('total'),
                'count' => $filtered->count(),
                'label' => Carbon::createFromFormat('Y-m', $month)->format('M Y'),
            ]];
        });

        $salesChartData = [
            'labels' => $salesByMonth->pluck('label')->values()->all(),
            'values' => $salesByMonth->pluck('value')->values()->all(),
            'counts' => $salesByMonth->pluck('count')->values()->all(),
        ];

        // 2. Payment status breakdown
        $statusCounts = ['Fully Paid' => 0, 'Awaiting Deposit' => 0, 'Deposit Paid' => 0, 'Pending Payment' => 0];
        foreach ($orders as $po) {
            $s = PaymentStatus::for($po);
            $label = str_starts_with($s['label'], 'Deposit Paid') ? 'Deposit Paid' : $s['label'];
            $statusCounts[$label] = ($statusCounts[$label] ?? 0) + 1;
        }
        $statusChartData = [
            'labels' => array_keys($statusCounts),
            'values' => array_values($statusCounts),
            'colors' => ['#22c55e', '#eab308', '#3b82f6', '#f97316'],
        ];

        // 3. Revenue by client (top 8)
        $byClient = $orders->groupBy('client_name')
            ->map(fn($g) => $g->sum('total'))
            ->sortDesc()
            ->take(8);
        $clientChartData = [
            'labels' => $byClient->keys()->values()->all(),
            'values' => $byClient->values()->all(),
        ];

        // 4. Revenue by category
        $byCategory = $orders->groupBy('category')
            ->map(fn($g) => $g->sum('total'))
            ->sortDesc();
        $categoryChartData = [
            'labels' => $byCategory->keys()->values()->all(),
            'values' => $byCategory->values()->all(),
        ];

        return view('livewire.dashboard', compact(
            'totalCount', 'totalValue', 'totalCollected', 'totalOutstanding',
            'recentPos', 'salesChartData', 'statusChartData', 'clientChartData', 'categoryChartData'
        ))->layout('layouts.app');
    }
}
