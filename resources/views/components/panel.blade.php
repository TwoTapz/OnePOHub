@props(['title' => null])

<div class="bg-white border border-gray-100 rounded-lg">
    @if($title)
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-navy">{{ $title }}</h3>
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
