<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-navy">Purchase Orders</h1>
            <p class="text-xs text-gray-400 mt-0.5">{{ $orders->count() }} {{ $orders->count() === 1 ? 'order' : 'orders' }} shown</p>
        </div>
        <a href="{{ route('purchase-orders.create') }}"
           class="inline-flex items-center gap-2 bg-brass hover:bg-brass-dark text-white text-sm font-medium px-4 py-2 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add PO
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-gray-100 rounded-lg p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Search</label>
                <input wire:model.live="search" type="text" placeholder="PO no. or client"
                       class="w-full rounded border-gray-200 text-sm focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Category</label>
                <select wire:model.live="filterCategory"
                        class="w-full rounded border-gray-200 text-sm focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Status</label>
                <select wire:model.live="filterStatus"
                        class="w-full rounded border-gray-200 text-sm focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                    <option value="">All statuses</option>
                    <option value="Fully Paid">Fully Paid</option>
                    <option value="Awaiting Deposit">Awaiting Deposit</option>
                    <option value="Deposit Paid">Deposit Paid</option>
                    <option value="Pending Payment">Pending Payment</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Company type</label>
                <select wire:model.live="filterType"
                        class="w-full rounded border-gray-200 text-sm focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                    <option value="">All types</option>
                    <option value="Government">Government</option>
                    <option value="Private">Private</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-gray-100 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-surface/60">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">PO No.</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">Company</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">SLA</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">Type</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $po)
                        @php
                            $status = \App\Support\PaymentStatus::for($po);
                        @endphp
                        <tr class="hover:bg-surface/40 transition-colors">
                            <td class="px-4 py-3 font-mono font-medium text-navy text-xs">{{ $po['po_number'] }}</td>
                            <td class="px-4 py-3 text-gray-700 max-w-[200px] truncate">{{ $po['client_name'] }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-400">{{ \Carbon\Carbon::parse($po['po_date'])->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-400">{{ \Carbon\Carbon::parse($po['sla_date'])->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right font-mono font-medium text-navy">RM {{ number_format($po['total'], 2) }}</td>
                            <td class="px-4 py-3">
                                <x-badge :status="$status['label']" :color="$status['color']"/>
                            </td>
                            <td class="px-4 py-3">
                                <x-company-type-tag :type="$po['client_type']"/>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('purchase-orders.show', $po['id']) }}"
                                       title="View"
                                       class="text-gray-300 hover:text-[#1C3F6E] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('purchase-orders.edit', $po['id']) }}"
                                       title="Edit"
                                       class="text-gray-300 hover:text-navy transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </a>
                                    <button wire:click="deleteOrder('{{ $po['id'] }}')"
                                            wire:confirm="Delete PO {{ $po['po_number'] }}? This cannot be undone."
                                            title="Delete"
                                            class="text-gray-300 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-16 text-center">
                                <p class="text-sm text-gray-400">No purchase orders match your filters.</p>
                                <a href="{{ route('purchase-orders.create') }}" class="mt-2 inline-block text-sm font-medium text-[#1C3F6E] hover:text-navy">Add your first PO</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
