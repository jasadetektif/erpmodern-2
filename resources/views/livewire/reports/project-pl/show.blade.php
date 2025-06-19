<div>
    <x-slot name="header">
        {{ __('Project P&L Detail') }}: {{ $project->name }}
    </x-slot>

    <div class="py-4 space-y-6">
        <!-- Panel Ringkasan Keuangan -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">{{ __('Financial Summary') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Contract Value') }}</h3>
                    <p class="mt-1 text-2xl font-bold text-gray-900">Rp {{ number_format($contractValue, 0, ',', '.') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Actual Revenue Received') }}</h3>
                    <p class="mt-1 text-2xl font-bold text-blue-600">Rp {{ number_format($actualRevenue, 0, ',', '.') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Total Expenses') }}</h3>
                    <p class="mt-1 text-2xl font-bold text-red-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Actual Profit / Loss') }}</h3>
                    <p class="mt-1 text-2xl font-bold {{ $profitOrLoss >= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($profitOrLoss, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Panel Rincian Pengeluaran -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
             <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Expense Details') }}</h2>
                 <a href="{{ route('reports.project-pl.detail.pdf', $project->id) }}" target="_blank" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700">{{ __('Export to PDF') }}</a>
            </div>
            
            {{-- Tabel Biaya Material --}}
            <h3 class="text-lg font-semibold text-gray-700 mb-2">{{__('Material Expenses')}}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('PO Number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Order Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Supplier') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($project->purchaseOrders as $po)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-emerald-600">
                                    <a href="{{ route('procurement.po.show', $po->id) }}" class="hover:underline">{{ $po->po_number }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $po->supplier->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">Rp {{ number_format($po->total_amount, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">{{ __('No expense data available for this project.') }}</td></tr>
                        @endforelse
                    </tbody>
                     <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="3" class="px-6 py-3 text-right font-bold text-gray-700 uppercase">{{__('Total Material Expenses')}}</td>
                            <td class="px-6 py-3 text-right font-bold text-gray-700">Rp {{ number_format($project->purchaseOrders->sum('total_amount'), 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Tabel Biaya Tenaga Kerja --}}
            <h3 class="text-lg font-semibold text-gray-700 mt-8 mb-2">{{__('Labor Expenses')}}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Foreman Name')}}</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Number of Workers')}}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Foreman Salary (Monthly)')}}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Estimated Worker Wages (Monthly)')}}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Total Team Cost (Monthly)')}}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($project->teams as $team)
                            @php
                                $workerWages = $team->number_of_workers * 150000 * 25; // Asumsi upah & hari kerja
                                $teamTotal = ($team->employee->basic_salary ?? 0) + $workerWages;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $team->employee->user->name ?? $team->employee->employee_id_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{ $team->number_of_workers }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">Rp {{ number_format($team->employee->basic_salary ?? 0, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">Rp {{ number_format($workerWages, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">Rp {{ number_format($teamTotal, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">{{__('No labor data available for this project.')}}</td></tr>
                        @endforelse
                    </tbody>
                     <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="4" class="px-6 py-3 text-right font-bold text-gray-700 uppercase">{{__('Total Labor Expenses')}}</td>
                            <td class="px-6 py-3 text-right font-bold text-gray-700">Rp {{ number_format($project->total_labor_cost, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>
