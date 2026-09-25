@props(['unit'])

@if($unit === 'Ventures')
    <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200">
        Ventures
    </span>
@else
    <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-200">
        Legacy
    </span>
@endif
