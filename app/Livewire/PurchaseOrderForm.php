<?php

namespace App\Livewire;

use App\Support\PurchaseOrderStore;
use Livewire\Component;
use Livewire\WithFileUploads;
use Smalot\PdfParser\Parser as PdfParser;

class PurchaseOrderForm extends Component
{
    use WithFileUploads;

    public ?string $editId = null;
    public bool $isEdit = false;
    public bool $showModeSelect = true;

    public string $po_number = '';
    public string $po_date = '';
    public string $sla_date = '';
    public string $client_name = '';
    public string $client_type = 'Private';
    public string $business_unit = 'Legacy';
    public string $category = '';
    public string $contact = '';
    public string $your_ref = '';
    public string $currency = 'MYR';
    public string $payment_term = 'deposit';
    public float $deposit_percent = 50;
    public float $amount_paid = 0;
    public string $source = 'manual';

    public array $items = [];

    public $pdfFile = null;
    public string $pdfError = '';

    protected function rules(): array
    {
        return [
            'po_number'   => 'required|string',
            'client_name' => 'required|string',
            'items'       => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'items.required' => 'At least one line item is required.',
        'items.min'      => 'At least one line item is required.',
        'items.*.description.required' => 'Item description is required.',
        'items.*.quantity.required'    => 'Quantity is required.',
        'items.*.unit_price.required'  => 'Unit price is required.',
    ];

    public function mount(?string $id = null): void
    {
        if ($id) {
            $this->editId = $id;
            $this->isEdit = true;
            $this->showModeSelect = false;
            $po = app(PurchaseOrderStore::class)->find($id);
            if (!$po) abort(404);
            $this->fill([
                'po_number'       => $po['po_number'],
                'po_date'         => $po['po_date'],
                'sla_date'        => $po['sla_date'],
                'client_name'     => $po['client_name'],
                'client_type'     => $po['client_type'],
                'business_unit'   => $po['business_unit'] ?? 'Legacy',
                'category'        => $po['category'],
                'contact'         => $po['contact'] ?? '',
                'your_ref'        => $po['your_ref'] ?? '',
                'currency'        => $po['currency'] ?? 'MYR',
                'payment_term'    => $po['payment_term'],
                'deposit_percent' => $po['deposit_percent'],
                'amount_paid'     => $po['amount_paid'],
                'source'          => $po['source'],
                'items'           => $po['items'],
            ]);
        } else {
            $this->addItem();
        }
    }

    public function chooseManual(): void
    {
        $this->showModeSelect = false;
        $this->source = 'manual';
    }

    public function addItem(): void
    {
        $this->items[] = [
            'id'            => 'i-' . uniqid(),
            'description'   => '',
            'delivery_date' => '',
            'quantity'      => 1,
            'unit'          => 'pcs',
            'unit_price'    => 0,
        ];
    }

    public function removeItem(int $index): void
    {
        array_splice($this->items, $index, 1);
    }

    public function getComputedTotalProperty(): float
    {
        return array_sum(array_map(
            fn($item) => (float)($item['quantity'] ?? 0) * (float)($item['unit_price'] ?? 0),
            $this->items
        ));
    }

    public function uploadPdf(): void
    {
        $this->pdfError = '';

        $this->validate(['pdfFile' => 'required|file|mimes:pdf|max:10240']);

        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($this->pdfFile->getRealPath());
            $text = $pdf->getText();

            if (empty(trim($text))) {
                $this->pdfError = "Couldn't read this PDF — it may be a scanned image. Please fill the form manually.";
                return;
            }

            $this->extractFromText($text);
            $this->showModeSelect = false;
            $this->source = 'pdf';
        } catch (\Exception $e) {
            $this->pdfError = "Couldn't read this PDF. Please fill the form manually.";
        }
    }

    private function extractFromText(string $text): void
    {
        // PO Number
        if (preg_match('/(?:PO\s*No[.:]?\s*|PO\s*Number[:\s]+|Purchase Order No[.:]?\s*)([A-Z0-9\-\/]+)/i', $text, $m)) {
            $this->po_number = trim($m[1]);
        }

        // Date
        if (preg_match('/Date[:\s]+(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}|\d{4}-\d{2}-\d{2})/i', $text, $m)) {
            try {
                $this->po_date = \Carbon\Carbon::parse(trim($m[1]))->format('Y-m-d');
            } catch (\Exception) {}
        }

        // Client name — look for "Vendor" or "To:" block
        if (preg_match('/(?:Vendor|Bill To|To)[:\s]+([A-Z][^\n]{5,60})/i', $text, $m)) {
            $this->client_name = trim($m[1]);
        }

        // Payment terms
        if (preg_match('/Payment\s+[Tt]erms?[:\s]+([^\n]+)/i', $text, $m)) {
            $terms = strtolower(trim($m[1]));
            if (str_contains($terms, 'deposit') || str_contains($terms, '%')) {
                $this->payment_term = 'deposit';
                if (preg_match('/(\d+)\s*%/', $terms, $pm)) {
                    $this->deposit_percent = (float)$pm[1];
                }
            } else {
                $this->payment_term = 'full_on_completion';
            }
        }

        // Net total
        if (preg_match('/Net\s+[Tt]otal[:\s]+(?:MYR|RM)?\s*([\d,]+\.?\d*)/i', $text, $m)) {
            // just informational — total is derived from items
        }

        // Line items: look for rows like "description qty unit price amount"
        $lines = explode("\n", $text);
        $parsedItems = [];
        foreach ($lines as $line) {
            $line = trim($line);
            // pattern: text followed by numbers
            if (preg_match('/^(.{5,50}?)\s+(\d+(?:\.\d+)?)\s+(?:pcs?|units?|sets?|ea)?\s+([\d,]+\.?\d*)\s+([\d,]+\.?\d*)\s*$/i', $line, $m)) {
                $price = (float)str_replace(',', '', $m[3]);
                $qty = (float)$m[2];
                if ($price > 0 && $qty > 0) {
                    $parsedItems[] = [
                        'id'            => 'i-' . uniqid(),
                        'description'   => trim($m[1]),
                        'delivery_date' => '',
                        'quantity'      => $qty,
                        'unit'          => 'pcs',
                        'unit_price'    => $price,
                    ];
                }
            }
        }

        if (!empty($parsedItems)) {
            $this->items = $parsedItems;
        } elseif (empty($this->items)) {
            $this->addItem();
        }
    }

    public function save(): void
    {
        $this->validate();

        $store = app(PurchaseOrderStore::class);
        $id = $this->editId ?? 'po-' . uniqid();

        $po = [
            'id'              => $id,
            'po_number'       => $this->po_number,
            'po_date'         => $this->po_date,
            'sla_date'        => $this->sla_date,
            'client_name'     => $this->client_name,
            'client_type'     => $this->client_type,
            'business_unit'   => $this->business_unit,
            'category'        => $this->category,
            'contact'         => $this->contact ?: null,
            'your_ref'        => $this->your_ref ?: null,
            'currency'        => $this->currency ?: 'MYR',
            'payment_term'    => $this->payment_term,
            'deposit_percent' => $this->payment_term === 'deposit' ? $this->deposit_percent : 0,
            'amount_paid'     => $this->amount_paid,
            'items'           => $this->items,
            'total'           => $this->computedTotal,
            'source'          => $this->source,
            'created_at'      => $this->editId
                ? ($store->find($id)['created_at'] ?? now()->toIso8601String())
                : now()->toIso8601String(),
        ];

        $store->save($po);
        $this->redirect(route('purchase-orders.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.purchase-order-form', [
            'computedTotal' => $this->computedTotal,
        ])->layout('layouts.app');
    }
}
