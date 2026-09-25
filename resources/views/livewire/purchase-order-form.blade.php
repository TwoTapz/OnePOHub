<div>
    <div class="flex items-center gap-3 mb-7">
        <a href="{{ route('purchase-orders.index') }}" class="text-gray-300 hover:text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
        </a>
        <h1 class="text-xl font-semibold text-navy">{{ $isEdit ? 'Edit purchase order' : 'New purchase order' }}</h1>
    </div>

    {{-- Mode selection (create only) --}}
    @if($showModeSelect)
        <div class="max-w-xl mx-auto mt-12">
            <p class="text-sm text-gray-500 mb-6 text-center">How would you like to add this PO?</p>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white border border-gray-100 rounded-lg p-5">
                    <h3 class="text-sm font-semibold text-navy mb-1">Upload PDF</h3>
                    <p class="text-xs text-gray-400 mb-4">We'll extract what we can and let you fill in the rest.</p>
                    <div class="space-y-3">
                        <input wire:model="pdfFile" type="file" accept=".pdf"
                               class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-brass/10 file:text-brass hover:file:bg-brass/20 cursor-pointer">
                        @error('pdfFile') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        @if($pdfError)
                            <p class="text-xs text-red-500">{{ $pdfError }}</p>
                        @endif
                        <button wire:click="uploadPdf"
                                wire:loading.attr="disabled"
                                class="w-full bg-brass hover:bg-brass-dark disabled:opacity-50 text-white text-sm font-medium py-2 rounded transition-colors">
                            <span wire:loading.remove wire:target="uploadPdf">Extract and fill</span>
                            <span wire:loading wire:target="uploadPdf">Extracting…</span>
                        </button>
                    </div>
                </div>
                <div class="bg-white border border-gray-100 rounded-lg p-5">
                    <h3 class="text-sm font-semibold text-navy mb-1">Enter manually</h3>
                    <p class="text-xs text-gray-400 mb-4">Fill in all fields yourself.</p>
                    <button wire:click="chooseManual"
                            class="w-full bg-navy/5 hover:bg-navy/10 text-navy text-sm font-medium py-2 rounded transition-colors">
                        Open blank form
                    </button>
                </div>
            </div>
        </div>
    @else
    {{-- The Form --}}
    <form wire:submit="save" class="space-y-5">
        <x-panel title="Order details">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">PO number <span class="text-red-400">*</span></label>
                    <input wire:model="po_number" type="text" class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                    @error('po_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">PO date</label>
                    <input wire:model="po_date" type="date" class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">SLA date</label>
                    <input wire:model="sla_date" type="date" class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Client name <span class="text-red-400">*</span></label>
                    <input wire:model="client_name" type="text" class="w-full rounded border-gray-200 text-sm focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                    @error('client_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Company type</label>
                    <select wire:model="client_type" class="w-full rounded border-gray-200 text-sm focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                        <option value="Private">Private</option>
                        <option value="Government">Government</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Legacy/Ventures</label>
                    <select wire:model="business_unit" class="w-full rounded border-gray-200 text-sm focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                        <option value="Legacy">Legacy</option>
                        <option value="Ventures">Ventures</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Category</label>
                    <input wire:model="category" type="text" class="w-full rounded border-gray-200 text-sm focus:ring-[#1C3F6E] focus:border-[#1C3F6E]" placeholder="e.g. Trophies & Awards">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Contact</label>
                    <input wire:model="contact" type="text" class="w-full rounded border-gray-200 text-sm focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Your ref</label>
                    <input wire:model="your_ref" type="text" class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]" placeholder="e.g. QT-180">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Currency</label>
                    <input wire:model="currency" type="text" class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]" value="MYR">
                </div>
            </div>
        </x-panel>

        <x-panel title="Payment terms">
            <div class="space-y-4">
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model.live="payment_term" type="radio" value="deposit" class="text-brass focus:ring-brass">
                        <span class="text-sm text-gray-700">Deposit required</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model.live="payment_term" type="radio" value="full_on_completion" class="text-brass focus:ring-brass">
                        <span class="text-sm text-gray-700">Full payment on completion</span>
                    </label>
                </div>
                @if($payment_term === 'deposit')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Deposit percentage (%)</label>
                        <input wire:model="deposit_percent" type="number" min="0" max="100" step="1"
                               class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Amount already received (MYR)</label>
                        <input wire:model="amount_paid" type="number" min="0" step="0.01"
                               class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                    </div>
                </div>
                @else
                <div class="max-w-xs">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Amount already received (MYR)</label>
                    <input wire:model="amount_paid" type="number" min="0" step="0.01"
                           class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                </div>
                @endif
            </div>
        </x-panel>

        <x-panel title="Line items">
            @error('items') <p class="text-sm text-red-500 mb-3">{{ $message }}</p> @enderror
            <div class="space-y-2.5">
                <div class="hidden sm:grid sm:grid-cols-12 gap-2 text-xs font-medium text-gray-400 pb-2 border-b border-gray-100">
                    <div class="col-span-4">Description</div>
                    <div class="col-span-2">Delivery date</div>
                    <div class="col-span-1">Qty</div>
                    <div class="col-span-1">Unit</div>
                    <div class="col-span-2">Unit price</div>
                    <div class="col-span-1 text-right">Amount</div>
                    <div class="col-span-1"></div>
                </div>
                @foreach($items as $index => $item)
                <div class="grid grid-cols-12 gap-2 items-start">
                    <div class="col-span-12 sm:col-span-4">
                        <input wire:model.live="items.{{ $index }}.description" type="text" placeholder="Description"
                               class="w-full rounded border-gray-200 text-sm focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                        @error("items.$index.description") <p class="text-xs text-red-500 mt-0.5">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <input wire:model="items.{{ $index }}.delivery_date" type="date"
                               class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <input wire:model.live="items.{{ $index }}.quantity" type="number" min="0.01" step="any" placeholder="Qty"
                               class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <input wire:model="items.{{ $index }}.unit" type="text" placeholder="pcs"
                               class="w-full rounded border-gray-200 text-sm focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                    </div>
                    <div class="col-span-5 sm:col-span-2">
                        <input wire:model.live="items.{{ $index }}.unit_price" type="number" min="0" step="0.01" placeholder="0.00"
                               class="w-full rounded border-gray-200 text-sm font-mono focus:ring-[#1C3F6E] focus:border-[#1C3F6E]">
                    </div>
                    <div class="col-span-5 sm:col-span-1 flex items-center justify-end pt-2">
                        <span class="text-sm font-mono font-medium text-navy">RM {{ number_format(($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0), 2) }}</span>
                    </div>
                    <div class="col-span-2 sm:col-span-1 flex items-center justify-center pt-2">
                        <button type="button" wire:click="removeItem({{ $index }})"
                                class="text-gray-300 hover:text-red-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                <button type="button" wire:click="addItem"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-[#1C3F6E] hover:text-navy">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add item
                </button>
                <div class="text-right">
                    <span class="text-xs text-gray-400">Grand total</span>
                    <span class="ml-2 text-xl font-mono font-light text-navy">RM {{ number_format($computedTotal, 2) }}</span>
                </div>
            </div>
        </x-panel>

        <div class="flex gap-3 justify-end">
            <a href="{{ route('purchase-orders.index') }}"
               class="bg-white border border-gray-200 text-gray-600 hover:text-navy text-sm font-medium px-5 py-2 rounded transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="bg-brass hover:bg-brass-dark text-white text-sm font-medium px-5 py-2 rounded transition-colors">
                {{ $isEdit ? 'Update PO' : 'Create PO' }}
            </button>
        </div>
    </form>
    @endif
</div>
