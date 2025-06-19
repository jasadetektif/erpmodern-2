@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">Dashboard Arkavera App</h1>

        {{-- Statistik Kartu --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-dashboard.stat-card title="Proyek Aktif" :value="$activeProjects" icon="briefcase" color="blue" />
            <x-dashboard.stat-card title="PR Tertunda" :value="$pendingPRs" icon="clipboard-list" color="yellow" />
            <x-dashboard.stat-card title="Pegawai Aktif" :value="$activeEmployees" icon="users" color="green" />
            <x-dashboard.stat-card title="Pengeluaran Bulan Ini" :value="number_format($monthlyExpense, 0, ',', '.')" icon="currency-dollar" color="red" />
        </div>

        {{-- Chart Seksi --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <x-dashboard.chart-card title="Status Proyek" chart-id="statusChart" />
            <x-dashboard.chart-card title="Pengeluaran per Proyek (Top 5)" chart-id="expenseChart" />
        </div>
    </div>
</div>

{{-- Chart.js Script --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const statusChart = new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: @json($statusChartData['labels']),
            datasets: [{
                label: 'Jumlah',
                data: @json($statusChartData['data']),
                borderWidth: 1
            }]
        }
    });

    const expenseChart = new Chart(document.getElementById('expenseChart'), {
        type: 'bar',
        data: {
            labels: @json($expenseChartData['labels']),
            datasets: [{
                label: 'Total Pengeluaran',
                data: @json($expenseChartData['data']),
                backgroundColor: 'rgba(255, 99, 132, 0.5)'
            }]
        }
    });
</script>
@endpush
@endsection
