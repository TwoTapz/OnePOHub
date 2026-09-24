<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnePOHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-surface font-sans antialiased">
<div class="flex h-full">
    <aside class="w-60 bg-navy flex flex-col shrink-0">
        <div class="px-6 pt-7 pb-6">
            <span class="text-lg font-semibold text-white tracking-tight">OnePOHub</span>
            <p class="text-xs text-white/40 mt-0.5 font-normal">Purchase Tracker</p>
        </div>
        <nav class="flex-1 px-3 space-y-0.5">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 text-sm rounded transition-colors
                      {{ request()->routeIs('dashboard') ? 'text-white bg-white/10 font-medium' : 'text-white/55 hover:text-white/90 hover:bg-white/5 font-normal' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('purchase-orders.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 text-sm rounded transition-colors
                      {{ request()->routeIs('purchase-orders.*') ? 'text-white bg-white/10 font-medium' : 'text-white/55 hover:text-white/90 hover:bg-white/5 font-normal' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                </svg>
                Purchase Orders
            </a>
            <a href="{{ route('quotations') }}"
               class="flex items-center gap-3 px-3 py-2.5 text-sm rounded transition-colors
                      {{ request()->routeIs('quotations') ? 'text-white bg-white/10 font-medium' : 'text-white/55 hover:text-white/90 hover:bg-white/5 font-normal' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                Quotations
            </a>
            <a href="{{ route('invoices') }}"
               class="flex items-center gap-3 px-3 py-2.5 text-sm rounded transition-colors
                      {{ request()->routeIs('invoices') ? 'text-white bg-white/10 font-medium' : 'text-white/55 hover:text-white/90 hover:bg-white/5 font-normal' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                </svg>
                Invoices
            </a>
        </nav>
        <div class="px-6 py-5 border-t border-white/10">
            <p class="text-xs text-white/25">© 2026 OnePOHub</p>
        </div>
    </aside>
    <div class="flex-1 flex flex-col min-w-0 overflow-auto">
        <main class="flex-1 px-8 py-8 max-w-screen-xl">
            {{ $slot }}
        </main>
    </div>
</div>
@livewireScripts
</body>
</html>
