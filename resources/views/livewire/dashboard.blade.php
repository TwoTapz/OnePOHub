<div>
    <div class="mb-8">
        <h1 class="text-xl font-semibold text-navy">Dashboard</h1>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-kpi-card title="Purchase orders" value="{{ $totalCount }}" subtitle="All time"/>
        <x-kpi-card title="Total order value" value="RM {{ number_format($totalValue, 0) }}" subtitle="Confirmed POs"/>
        <x-kpi-card title="Collected" value="RM {{ number_format($totalCollected, 0) }}" subtitle="Payments received"/>
        <x-kpi-card title="Outstanding" value="RM {{ number_format($totalOutstanding, 0) }}" subtitle="Balance due"/>
    </div>

    {{-- Charts row 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
        <x-panel title="Sales over last 6 months">
            <div wire:ignore x-data="{
                init() {
                    const data = {{ Js::from($salesChartData) }};
                    new Chart(this.$refs.salesChart, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: 'Order value (RM)',
                                    data: data.values,
                                    backgroundColor: 'rgba(176,125,16,0.75)',
                                    borderColor: '#B07D10',
                                    borderWidth: 1,
                                    borderRadius: 3,
                                    yAxisID: 'y',
                                },
                                {
                                    label: 'PO count',
                                    data: data.counts,
                                    type: 'line',
                                    borderColor: '#1C3F6E',
                                    backgroundColor: 'rgba(28,63,110,0.08)',
                                    pointBackgroundColor: '#1C3F6E',
                                    pointRadius: 3,
                                    tension: 0.3,
                                    yAxisID: 'y1',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            interaction: { mode: 'index', intersect: false },
                            plugins: { legend: { labels: { font: { family: 'IBM Plex Sans', size: 11 }, boxWidth: 12 } } },
                            scales: {
                                y: { type: 'linear', position: 'left', beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { family: 'IBM Plex Mono', size: 11 }, callback: v => 'RM ' + v.toLocaleString() }},
                                y1: { type: 'linear', position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, ticks: { font: { family: 'IBM Plex Mono', size: 11 }, precision: 0 }}
                            }
                        }
                    });
                }
            }">
                <canvas x-ref="salesChart" height="220"></canvas>
            </div>
        </x-panel>

        <x-panel title="Payment status">
            <div wire:ignore x-data="{
                init() {
                    const data = {{ Js::from($statusChartData) }};
                    new Chart(this.$refs.statusChart, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.values,
                                backgroundColor: ['#059669','#D97706','#2563EB','#94a3b8'],
                                borderWidth: 3,
                                borderColor: '#ffffff',
                            }]
                        },
                        options: {
                            responsive: true,
                            cutout: '68%',
                            plugins: {
                                legend: { position: 'right', labels: { font: { family: 'IBM Plex Sans', size: 11 }, boxWidth: 10, padding: 14 } }
                            }
                        }
                    });
                }
            }">
                <canvas x-ref="statusChart" height="220"></canvas>
            </div>
        </x-panel>
    </div>

    {{-- Charts row 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
        <x-panel title="Revenue by client">
            <div wire:ignore x-data="{
                init() {
                    const data = {{ Js::from($clientChartData) }};
                    new Chart(this.$refs.clientChart, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Order value (RM)',
                                data: data.values,
                                backgroundColor: 'rgba(28,63,110,0.7)',
                                borderColor: '#1C3F6E',
                                borderWidth: 1,
                                borderRadius: 3,
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { family: 'IBM Plex Mono', size: 11 }, callback: v => 'RM ' + v.toLocaleString() }},
                                y: { ticks: { font: { family: 'IBM Plex Sans', size: 11 } } }
                            }
                        }
                    });
                }
            }">
                <canvas x-ref="clientChart" height="260"></canvas>
            </div>
        </x-panel>

        <x-panel title="Revenue by category">
            <div wire:ignore x-data="{
                init() {
                    const data = {{ Js::from($categoryChartData) }};
                    const colors = ['#B07D10','#1C3F6E','#059669','#D97706','#2563EB','#7C3AED','#0891B2'];
                    new Chart(this.$refs.categoryChart, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Order value (RM)',
                                data: data.values,
                                backgroundColor: data.labels.map((_, i) => colors[i % colors.length] + 'C0'),
                                borderColor: data.labels.map((_, i) => colors[i % colors.length]),
                                borderWidth: 1,
                                borderRadius: 3,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { family: 'IBM Plex Mono', size: 11 }, callback: v => 'RM ' + v.toLocaleString() }},
                                x: { ticks: { font: { family: 'IBM Plex Sans', size: 11 } } }
                            }
                        }
                    });
                }
            }">
                <canvas x-ref="categoryChart" height="260"></canvas>
            </div>
        </x-panel>
    </div>

    {{-- Recent POs --}}
    <x-panel title="Recent purchase orders">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="pb-3 text-left text-xs font-medium text-gray-400">PO No.</th>
                    <th class="pb-3 text-left text-xs font-medium text-gray-400">Client</th>
                    <th class="pb-3 text-left text-xs font-medium text-gray-400">Date</th>
                    <th class="pb-3 text-right text-xs font-medium text-gray-400">Total</th>
                    <th class="pb-3 text-left text-xs font-medium text-gray-400">Status</th>
                    <th class="pb-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentPos as $po)
                @php $status = \App\Support\PaymentStatus::for($po); @endphp
                <tr class="hover:bg-surface/50 transition-colors">
                    <td class="py-3 font-mono text-xs text-navy font-medium">{{ $po['po_number'] }}</td>
                    <td class="py-3 text-gray-700 max-w-[180px] truncate">{{ $po['client_name'] }}</td>
                    <td class="py-3 text-gray-400 font-mono text-xs">{{ \Carbon\Carbon::parse($po['po_date'])->format('d M Y') }}</td>
                    <td class="py-3 text-right font-mono text-sm font-medium text-navy">RM {{ number_format($po['total'], 0) }}</td>
                    <td class="py-3"><x-badge :status="$status['label']" :color="$status['color']"/></td>
                    <td class="py-3 text-right">
                        <a href="{{ route('purchase-orders.show', $po['id']) }}" class="text-xs font-medium text-[#1C3F6E] hover:text-navy underline underline-offset-2">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-5 pt-4 border-t border-gray-50">
            <a href="{{ route('purchase-orders.index') }}" class="text-sm font-medium text-[#1C3F6E] hover:text-navy">All purchase orders</a>
        </div>
    </x-panel>
</div>
