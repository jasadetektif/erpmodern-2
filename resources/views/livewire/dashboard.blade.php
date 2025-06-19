<div>
    {{-- Mengosongkan header default karena kita membuat header kustom di bawah --}}
    <x-slot name="header"></x-slot>

    {{-- Style untuk animasi marquee --}}
    <style>
        @keyframes marquee { 0% { transform: translateX(0%); } 100% { transform: translateX(-50%); } }
        .animate-marquee:hover { animation-play-state: paused; }
        .animate-marquee { animation: marquee 40s linear infinite; }
    </style>

    <div class="py-4 space-y-8">
        
        <!-- Header & Salam -->
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ __($greeting) }}, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-500 mt-1 italic">"{{ __($quote) }}"</p>
        </div>

        <!-- Teks Berjalan (Marquee) untuk Aktivitas Terbaru -->
        @if(count($activityFeed) > 0)
        <div class="bg-white rounded-xl shadow-md p-2 overflow-hidden">
            <div class="flex whitespace-nowrap animate-marquee">
                {{-- Konten diduplikasi untuk menciptakan efek loop yang mulus --}}
                @foreach (range(1, 2) as $i)
                    @forelse($activityFeed as $activity)
                        <div class="flex items-center mx-4 py-2 flex-shrink-0">
                            @if($activity['type'] === 'pr')
                                <div class="bg-blue-100 text-blue-600 p-2 rounded-full mr-3"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg></div>
                                <p class="text-sm text-gray-700">[{{ $activity['timestamp']->setTimezone('Asia/Jakarta')->format('H:i') }} WIB] {{ __('PR') }} <span class="font-semibold">{{$activity['data']->pr_number}}</span> {{ __('was just created by') }} <span class="font-semibold">{{$activity['data']->requester->name}}</span>.</p>
                            @elseif($activity['type'] === 'po')
                                <div class="bg-green-100 text-green-600 p-2 rounded-full mr-3"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></div>
                                <p class="text-sm text-gray-700">[{{ $activity['timestamp']->setTimezone('Asia/Jakarta')->format('H:i') }} WIB] {{ __('PO') }} <span class="font-semibold">{{$activity['data']->po_number}}</span> {{ __('was created for') }} <span class="font-semibold">{{$activity['data']->supplier->name}}</span>.</p>
                            @elseif($activity['type'] === 'employee')
                                <div class="bg-indigo-100 text-indigo-600 p-2 rounded-full mr-3"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg></div>
                                <p class="text-sm text-gray-700">[{{ $activity['timestamp']->setTimezone('Asia/Jakarta')->format('H:i') }} WIB] <span class="font-semibold">{{ $activity['data']->user->name ?? $activity['data']->employee_id_number }}</span> {{ __('was added as a new employee') }}.</p>
                            @endif
                        </div>
                        <span class="text-gray-300 self-center">&bull;</span>
                    @empty
                         <p class="text-sm text-gray-500 mx-4">{{__('No recent activity.')}}</p>
                    @endforelse
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Grid Utama -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <!-- Pusat Aksi & Pengumuman -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Kolom Pusat Aksi -->
                    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-amber-400">
                        <h3 class="font-semibold text-gray-800 mb-4">{{__('Needs Your Attention')}}</h3>
                        <div class="space-y-4">
                            @if($pendingPrCount > 0)
                            <a href="{{route('procurement.pr.index')}}" class="flex items-center space-x-3 p-3 bg-amber-50 rounded-lg hover:bg-amber-100 transition">
                                <div class="bg-amber-100 text-amber-600 p-2 rounded-full"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg></div>
                                <div><p class="text-sm font-semibold text-gray-900">{{ $pendingPrCount }} {{__('Purchase Request')}}</p><p class="text-xs text-gray-500">{{__('Waiting for your approval')}}</p></div>
                            </a>
                            @endif
                            @if($overdueInvoicesCount > 0)
                            <a href="{{route('finance.invoices.index')}}" class="flex items-center space-x-3 p-3 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                 <div class="bg-red-100 text-red-600 p-2 rounded-full"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                <div><p class="text-sm font-semibold text-gray-900">{{ $overdueInvoicesCount }} {{__('Overdue Invoices')}}</p><p class="text-xs text-gray-500">{{__('Need to be paid immediately')}}</p></div>
                            </a>
                            @endif
                            @if($pendingPrCount == 0 && $overdueInvoicesCount == 0)
                            <p class="text-center text-gray-400 py-4">{{__('All clear! No urgent actions needed.')}}</p>
                            @endif
                        </div>
                    </div>
                    <!-- Kolom Pengumuman -->
                    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-blue-400">
                        <h3 class="font-semibold text-gray-800 mb-4">{{__('Announcements')}}</h3>
                        @if($latestAnnouncement)
                            <div class="space-y-2">
                                <p class="font-bold text-emerald-700">{{ $latestAnnouncement->title }}</p>
                                <p class="text-sm text-gray-600 line-clamp-3">{{ $latestAnnouncement->content }}</p>
                                <p class="text-xs text-gray-400 pt-2 border-t">{{__('Posted by')}} {{ $latestAnnouncement->user->name }} - {{ $latestAnnouncement->created_at->diffForHumans() }}</p>
                            </div>
                        @else
                            <p class="text-center text-gray-400 py-4">{{__('No new announcements.')}}</p>
                        @endif
                    </div>
                </div>
                <!-- Grafik Profitabilitas Proyek -->
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="font-semibold text-gray-800 mb-4">{{__('Project Profitability')}}</h3>
                    <div class="h-80" wire:ignore><canvas id="profitabilityChart"></canvas></div>
                </div>
            </div>
            <!-- Kolom Samping Kanan (Statistik Kunci) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-gradient-to-br from-emerald-500 to-green-600 text-white p-6 rounded-xl shadow-lg">
                    <p class="text-sm font-medium opacity-80">{{ __('Ongoing Projects') }}</p><p class="text-3xl font-bold mt-1">{{ $activeProjects }}</p>
                </div>
                <div class="bg-gradient-to-br from-sky-500 to-blue-600 text-white p-6 rounded-xl shadow-lg">
                    <p class="text-sm font-medium opacity-80">{{ __('Total Workers on Site') }}</p><p class="text-3xl font-bold mt-1">{{ $totalWorkers }}</p>
                </div>
            </div>
        </div>
    </div>
    
    @script
<script>
    document.addEventListener('livewire:navigated', () => {
        if (window.profitabilityChart && typeof window.profitabilityChart.destroy === 'function') {
            window.profitabilityChart.destroy();
        }

        const ctx = document.getElementById('profitabilityChart');
        if (ctx) {
            const chartData = @json($profitabilityChartData);
            if (chartData && chartData.labels.length > 0) {
                window.profitabilityChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: '{{__('Revenue')}}',
                                data: chartData.revenues,
                                backgroundColor: 'rgba(16, 185, 129, 0.6)',
                                borderColor: 'rgba(16, 185, 129, 1)',
                                borderWidth: 1,
                                borderRadius: 4
                            },
                            {
                                label: '{{__('Expenses')}}',
                                data: chartData.expenses,
                                backgroundColor: 'rgba(239, 68, 68, 0.6)',
                                borderColor: 'rgba(239, 68, 68, 1)',
                                borderWidth: 1,
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
        }
    });
</script>
@endscript

</div>
