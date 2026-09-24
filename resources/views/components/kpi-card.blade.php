@props(['title', 'value', 'subtitle' => null])

<div class="bg-white border-l-4 border-brass px-5 py-5 rounded-r">
    <p class="text-xs font-medium text-gray-400 mb-2">{{ $title }}</p>
    <p class="text-4xl font-light font-mono text-navy leading-none">{{ $value }}</p>
    @if($subtitle)
        <p class="text-xs text-gray-400 mt-2">{{ $subtitle }}</p>
    @endif
</div>
