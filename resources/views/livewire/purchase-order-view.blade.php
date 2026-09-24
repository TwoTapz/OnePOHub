<div>
    <div class="flex items-center gap-3 mb-7">
        <a href="{{ route('purchase-orders.index') }}" class="text-gray-300 hover:text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
        </a>
        <h1 class="text-xl font-semibold text-navy font-mono">{{ $po['po_number'] }}</h1>
        <x-badge :status="$status['label']" :color="$status['color']"/>
        <x-company-type-tag :type="$po['client_type']"/>
        @if($po['source'] === 'pdf')
            <span class="inline-flex items-center rounded px-2 py-0.5 text-xs text-gray-400 bg-gray-50 ring-1 ring-gray-100">PDF import</span>
        @endif
        <div class="ml-auto flex gap-2">
            <a href="{{ route('purchase-orders.edit', $po['id']) }}"
               class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-600 hover:text-navy hover:border-gray-300 text-sm font-medium px-3 py-1.5 rounded transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                </svg>
                Edit
            </a>
            <button wire:click="deletePo"
                    wire:confirm="Delete this PO permanently?"
                    class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-200 text-sm font-medium px-3 py-1.5 rounded transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                </svg>
                Delete
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 space-y-5">
            {{-- Order details --}}
            <x-panel title="Order details">
                <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Client</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-navy">{{ $po['client_name'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">PO date</dt>
                        <dd class="mt-0.5 text-sm font-mono text-gray-700">{{ \Carbon\Carbon::parse($po['po_date'])->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">SLA date</dt>
                        <dd class="mt-0.5 text-sm font-mono text-gray-700">{{ \Carbon\Carbon::parse($po['sla_date'])->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Category</dt>
                        <dd class="mt-0.5 text-sm text-gray-700">{{ $po['category'] }}</dd>
                    </div>
                    @if($po['contact'])
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Contact</dt>
                        <dd class="mt-0.5 text-sm text-gray-700">{{ $po['contact'] }}</dd>
                    </div>
                    @endif
                    @if($po['your_ref'])
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Your ref</dt>
                        <dd class="mt-0.5 text-sm font-mono text-gray-700">{{ $po['your_ref'] }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Payment terms</dt>
                        <dd class="mt-0.5 text-sm text-gray-700">
                            @if($po['payment_term'] === 'deposit')
                                {{ $po['deposit_percent'] }}% deposit + balance on completion
                            @else
                                Full payment on completion
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Currency</dt>
                        <dd class="mt-0.5 text-sm font-mono text-gray-700">{{ $po['currency'] ?? 'MYR' }}</dd>
                    </div>
                </dl>
            </x-panel>

            {{-- Line items --}}
            <x-panel title="Line items">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-3 text-left text-xs font-medium text-gray-400">Description</th>
                            <th class="pb-3 text-left text-xs font-medium text-gray-400">Delivery</th>
                            <th class="pb-3 text-right text-xs font-medium text-gray-400">Qty</th>
                            <th class="pb-3 text-left text-xs font-medium text-gray-400 pl-2">Unit</th>
                            <th class="pb-3 text-right text-xs font-medium text-gray-400">Unit price</th>
                            <th class="pb-3 text-right text-xs font-medium text-gray-400">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($po['items'] as $item)
                        <tr>
                            <td class="py-2.5 text-gray-700">{{ $item['description'] }}</td>
                            <td class="py-2.5 font-mono text-xs text-gray-400">
                                {{ $item['delivery_date'] ? \Carbon\Carbon::parse($item['delivery_date'])->format('d M Y') : '—' }}
                            </td>
                            <td class="py-2.5 text-right font-mono text-gray-600">{{ $item['quantity'] }}</td>
                            <td class="py-2.5 pl-2 text-gray-400 text-xs">{{ $item['unit'] }}</td>
                            <td class="py-2.5 text-right font-mono text-gray-600">RM {{ number_format($item['unit_price'], 2) }}</td>
                            <td class="py-2.5 text-right font-mono font-medium text-navy">RM {{ number_format($item['quantity'] * $item['unit_price'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-gray-200">
                            <td colspan="5" class="pt-3 text-right text-xs font-medium text-gray-400">Net total</td>
                            <td class="pt-3 text-right font-mono text-lg font-semibold text-navy">RM {{ number_format($total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </x-panel>
        </div>

        {{-- Payment panel --}}
        <div class="space-y-5">
            <x-panel title="Payment">
                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Order total</span>
                        <span class="font-mono font-semibold text-navy">RM {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Collected</span>
                        <span class="font-mono font-semibold text-emerald-600">RM {{ number_format($paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Balance due</span>
                        <span class="font-mono font-semibold text-amber-700">RM {{ number_format($balance, 2) }}</span>
                    </div>

                    {{-- Progress bar --}}
                    <div>
                        <div class="flex justify-between text-xs text-gray-300 mb-1.5">
                            <span>Payment progress</span>
                            <span class="font-mono">{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all
                                @if($progress >= 100) bg-emerald-500
                                @elseif($progress > 0) bg-brass
                                @else bg-gray-200
                                @endif"
                                 style="width: {{ $progress }}%">
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-badge :status="$status['label']" :color="$status['color']"/>
                    </div>

                    @if($balance > 0)
                        @if(!$showPaymentForm)
                            <button wire:click="$set('showPaymentForm', true)"
                                    class="w-full mt-1 bg-brass hover:bg-brass-dark text-white text-sm font-medium py-2 rounded transition-colors">
                                Record payment
                            </button>
                        @else
                            <div class="space-y-2 mt-1 pt-3 border-t border-gray-100">
                                <label class="block text-xs font-medium text-gray-500">Amount received (MYR)</label>
                                <input wire:model="paymentAmount" type="number" step="0.01" min="0.01" max="{{ $balance }}"
                                       class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]"
                                       placeholder="0.00">
                                @if($paymentError)
                                    <p class="text-xs text-red-500">{{ $paymentError }}</p>
                                @endif
                                <div class="flex gap-2">
                                    <button wire:click="recordPayment"
                                            class="flex-1 bg-brass hover:bg-brass-dark text-white text-sm font-medium py-2 rounded transition-colors">
                                        Save
                                    </button>
                                    <button wire:click="$set('showPaymentForm', false)"
                                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium py-2 rounded transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </x-panel>
        </div>
    </div>
</div>
