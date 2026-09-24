@props(['status', 'color' => 'gray'])

@php
$classes = match($color) {
    'green'  => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'yellow' => 'bg-amber-50 text-amber-700 ring-amber-200',
    'blue'   => 'bg-blue-50 text-blue-700 ring-blue-200',
    'orange' => 'bg-slate-50 text-slate-600 ring-slate-200',
    default  => 'bg-gray-50 text-gray-600 ring-gray-200',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded px-2 py-0.5 text-xs font-medium ring-1 ring-inset $classes"]) }}>
    {{ $status }}
</span>
