<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      
    @vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
    @media print {
        /* Sembunyikan semua elemen yang tidak perlu dicetak */
        .no-print,
        body > div > div:not(.flex-1),
        body > div > div.flex-1 > header {
            display: none !important;
        }
        /* Pastikan konten utama memenuhi seluruh halaman cetak */
        body > div > div.flex-1 {
            margin-left: 0 !important;
        }
        body > div > div.flex-1 > main {
            padding: 0 !important;
        }
    }
</style>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full font-sans antialiased">
    <div x-data="{ sidebarOpen: window.innerWidth > 1024 }" @resize.window="sidebarOpen = window.innerWidth > 1024">
        
        <!-- Sidebar -->
        <div 
            x-show="sidebarOpen" 
            x-transition:enter="transition-transform duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-40 w-64 bg-emerald-800 text-white flex flex-col"
            style="background-color: #1A4731;"
            @click.away="if (window.innerWidth < 1024) sidebarOpen = false"
        >
            <!-- Logo -->
            <div class="h-16 flex-shrink-0 flex items-center justify-center px-4 space-x-3">
                <svg class="h-8 w-8 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                </svg>
                <span class="text-xl font-bold">Arkavera App</span>
            </div>

            <!-- Navigasi Utama -->
<nav class="flex-1 px-4 py-4 space-y-2">
    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors {{ request()->routeIs('dashboard') ? 'bg-emerald-700' : '' }}">
        {{ __('Dashboard') }}
    </a>
    

{{-- Ganti link Sales & Quotations yang lama dengan blok dropdown ini --}}
@can('manage quotations')
<div x-data="{ open: {{ request()->routeIs('sales.*') ? 'true' : 'false' }} }" x-cloak>
    <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 text-left rounded-md hover:bg-emerald-700 transition-colors">
        <span>{{ __('Sales') }}</span>
        <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>
    <div x-show="open" x-collapse class="mt-2 pl-4 space-y-2">
        <a href="{{ route('sales.quotations.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('sales.quotations.index') ? 'bg-emerald-700' : '' }}">
            {{ __('Quotations') }}
        </a>
        <a href="{{ route('sales.clients.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('sales.clients.index') ? 'bg-emerald-700' : '' }}">
            {{ __('Clients') }}
        </a>
    </div>
</div>
@endcan

    @can('view projects')
    <a href="{{ route('projects.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors {{ request()->routeIs('projects.index') ? 'bg-emerald-700' : '' }}">
        {{ __('Project Management') }}
    </a>
    @endcan

    @can('manage assets')
<a href="{{ route('assets.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors {{ request()->routeIs('assets.index') ? 'bg-emerald-700' : '' }}">
    {{ __('Asset Management') }}
</a>
@endcan

    @can('view procurement')
    <div x-data="{ open: {{ request()->routeIs('procurement.*') ? 'true' : 'false' }} }" x-cloak>
        <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 text-left rounded-md hover:bg-emerald-700 transition-colors">
            <span>{{ __('Procurement') }}</span>
            <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open" x-collapse class="mt-2 pl-4 space-y-2">
            <a href="{{ route('procurement.pr.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('procurement.pr.index') ? 'bg-emerald-700' : '' }}">
                {{ __('Purchase Requests') }}
            </a>
            <a href="{{ route('procurement.po.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('procurement.po.index') ? 'bg-emerald-700' : '' }}">
                {{ __('Purchase Orders') }}
            </a>
            <a href="{{ route('procurement.gr.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('procurement.gr.index') ? 'bg-emerald-700' : '' }}">
                {{ __('Goods Receipts') }}
            </a>
            @can('manage suppliers')
            <a href="{{ route('procurement.suppliers.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('procurement.suppliers.index') ? 'bg-emerald-700' : '' }}">
                {{ __('Suppliers') }}
            </a>
            @endcan
        </div>
    </div>
    @endcan

    {{-- Ganti link Inventaris yang lama dengan blok dropdown ini --}}
@can('view procurement')
<div x-data="{ open: {{ request()->routeIs('inventory.*') ? 'true' : 'false' }} }" x-cloak>
    <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 text-left rounded-md hover:bg-emerald-700 transition-colors">
        <span>{{ __('Inventory') }}</span>
        <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>
    <div x-show="open" x-collapse class="mt-2 pl-4 space-y-2">
        <a href="{{ route('inventory.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('inventory.index') ? 'bg-emerald-700' : '' }}">
            {{ __('Stock List') }}
        </a>
        <a href="{{ route('inventory.usage.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('inventory.usage.index') ? 'bg-emerald-700' : '' }}">
            {{ __('Stock Usage') }}
        </a>
    </div>
</div>
@endcan

{{-- PERUBAHAN DIMULAI DI SINI --}}
                @can('view finance')
                <div x-data="{ open: {{ request()->routeIs('finance.invoices.*') || request()->routeIs('finance.payments.*') ? 'true' : 'false' }} }" x-cloak>
                    <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 text-left rounded-md hover:bg-emerald-700 transition-colors">
                        <span>{{ __('Accounts Payable') }}</span>
                        <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 pl-4 space-y-2">
                        <a href="{{ route('finance.invoices.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('finance.invoices.index') ? 'bg-emerald-700' : '' }}">{{ __('Supplier Invoices') }}</a>
                        <a href="{{ route('finance.payments.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('finance.payments.index') ? 'bg-emerald-700' : '' }}">{{ __('Supplier Payments') }}</a>
                    </div>
                </div>
                <div x-data="{ open: {{ request()->routeIs('finance.client-invoices.*') || request()->routeIs('finance.client-payments.*') ? 'true' : 'false' }} }" x-cloak>
                    <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 text-left rounded-md hover:bg-emerald-700 transition-colors">
                        <span>{{ __('Accounts Receivable') }}</span>
                        <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 pl-4 space-y-2">
                        <a href="{{ route('finance.client-invoices.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('finance.client-invoices.index') ? 'bg-emerald-700' : '' }}">{{ __('Client Invoices') }}</a>
                        <a href="{{ route('finance.client-payments.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('finance.client-payments.index') ? 'bg-emerald-700' : '' }}">{{ __('Client Payments') }}</a>
                    </div>
                </div>
                @endcan
                {{-- PERUBAHAN SELESAI DI SINI --}}

{{-- Ganti link Human Resources yang lama dengan blok dropdown ini --}}
@can('manage hr')
<div x-data="{ open: {{ request()->routeIs('hr.*') ? 'true' : 'false' }} }" x-cloak>
    <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 text-left rounded-md hover:bg-emerald-700 transition-colors">
        <span>{{ __('Human Resources') }}</span>
        <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>
    <div x-show="open" x-collapse class="mt-2 pl-4 space-y-2">
        <a href="{{ route('hr.employees.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('hr.employees.index') ? 'bg-emerald-700' : '' }}">
            {{ __('Employee Management') }}
        </a>
        <a href="{{ route('hr.attendances.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('hr.attendances.index') ? 'bg-emerald-700' : '' }}">
            {{ __('Attendance') }}
        <a href="{{ route('hr.payrolls.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('hr.payrolls.index') ? 'bg-emerald-700' : '' }}">
            {{ __('Payroll') }}
        </a>
    </div>
</div>
@endcan

@can('view reports')
<div x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }" x-cloak>
    <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 text-left rounded-md hover:bg-emerald-700 transition-colors">
        <span>{{ __('Reports') }}</span>
        <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>
    <div x-show="open" x-collapse class="mt-2 pl-4 space-y-2">
        <a href="{{ route('reports.project-pl.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('reports.project-pl.index') ? 'bg-emerald-700' : '' }}">
            {{ __('Project P&L') }}
        </a>
        <a href="{{ route('reports.cash-flow.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('reports.cash-flow.index') ? 'bg-emerald-700' : '' }}">
    {{ __('Cash Flow') }}
</a>
</a>
<a href="{{ route('reports.ap-aging.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('reports.ap-aging.index') ? 'bg-emerald-700' : '' }}">
    {{ __('A/P Aging') }}
</a>
<a href="{{ route('reports.ar-aging.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('reports.ar-aging.index') ? 'bg-emerald-700' : '' }}">
    {{ __('A/R Aging') }}
</a>
    </div>
</div>
@endcan
    </a>

@can('manage master_data')
<div x-data="{ open: {{ request()->routeIs('master.*') ? 'true' : 'false' }} }" x-cloak>
    <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 text-left rounded-md hover:bg-emerald-700 transition-colors">
        <span>{{ __('Master Data') }}</span>
        <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>
    <div x-show="open" x-collapse class="mt-2 pl-4 space-y-2">
        <a href="{{ route('master.materials.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('master.materials.index') ? 'bg-emerald-700' : '' }}">
            {{ __('Materials') }}
        </a>
        <a href="{{ route('master.labors.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('master.labors.index') ? 'bg-emerald-700' : '' }}">
    {{ __('Labors') }}
</a>
<a href="{{ route('master.ahs.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('master.ahs.*') ? 'bg-emerald-700' : '' }}">
    {{ __('Unit Price Analysis') }}
</a>

    </div>
</div>
@endcan

@can('manage settings')
                <div x-data="{ open: {{ request()->routeIs('settings.*') ? 'true' : 'false' }} }" x-cloak>
                    <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 text-left rounded-md hover:bg-emerald-700 transition-colors">
                        <span>{{ __('Settings') }}</span>
                        <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                        <div x-show="open" x-collapse class="mt-2 pl-4 space-y-2">
                            <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors {{ request()->routeIs('users.index') ? 'bg-emerald-700' : '' }}">
                             {{ __('User Management') }}
                             </a>
                        <a href="{{ route('settings.quotes.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('settings.quotes.index') ? 'bg-emerald-700' : '' }}">
                            {{ __('Quote Management') }}
                        </a>
                        <a href="{{ route('settings.announcements.index') }}" class="block px-4 py-2 rounded-md hover:bg-emerald-700 {{ request()->routeIs('settings.announcements.index') ? 'bg-emerald-700' : '' }}">
                            {{ __('Announcements') }}
                        </a>
                    </div>
                </div>
                @endcan

</nav>
        </div>

        <!-- Konten Utama -->
        <div class="flex-1 flex flex-col" :class="{'lg:ml-64': sidebarOpen}">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
                
                <div class="flex items-center">
                    <livewire:layout.navigation />
                </div>
            </header>

            <!-- Konten Halaman -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if (isset($header))
                    <header class="mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900">
                            {{ $header }}
                        </h1>
                    </header>
                @endif
                
                {{ $slot }}
            </main>
        </div>
    </div>
<livewire:global-search />
</body>
</html>