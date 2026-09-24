@props(['type'])

@if($type === 'Government')
    <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium bg-navy text-white/90">
        Gov
    </span>
@else
    <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium bg-brass/10 text-brass ring-1 ring-inset ring-brass/30">
        Private
    </span>
@endif
