<div>
    <x-slot name="header">
        {{ __('A/R Aging Report') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                 <h2 class="text-xl font-semibold">{{ __('Accounts Receivable Aging Summary') }}</h2>
                 <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-600">{{__('Report as of')}}: {{ \Carbon\Carbon::parse($reportDate)->format('d M Y') }}</span>
                    <button class="bg-gray-200 text-gray-800 font-bold px-4 py-2 rounded-md hover:bg-gray-300">{{ __('Export') }}</button>
                 </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-slate-100 p-4 rounded-lg text-center">
                    <p class="text-sm font-medium text-gray-500">{{__('Not Yet Due')}}</p>
                    <p class="text-xl font-bold text-gray-800">Rp {{number_format($totals['current'], 2, ',', '.')}}</p>
                </div>
                <div class="bg-amber-100 p-4 rounded-lg text-center">
                    <p class="text-sm font-medium text-amber-600">{{__('1 - 30 Days')}}</p>
                    <p class="text-xl font-bold text-amber-800">Rp {{number_format($totals['days_1_30'], 2, ',', '.')}}</p>
                </div>
                <div class="bg-orange-100 p-4 rounded-lg text-center">
                    <p class="text-sm font-medium text-orange-600">{{__('31 - 60 Days')}}</p>
                    <p class="text-xl font-bold text-orange-800">Rp {{number_format($totals['days_31_60'], 2, ',', '.')}}</p>
                </div>
                <div class="bg-red-100 p-4 rounded-lg text-center">
                    <p class="text-sm font-medium text-red-600">{{__('61 - 90 Days')}}</p>
                    <p class="text-xl font-bold text-red-800">Rp {{number_format($totals['days_61_90'], 2, ',', '.')}}</p>
                </div>
                <div class="bg-red-200 p-4 rounded-lg text-center">
                    <p class="text-sm font-medium text-red-700">{{__('> 90 Days')}}</p>
                    <p class="text-xl font-bold text-red-900">Rp {{number_format($totals['over_90'], 2, ',', '.')}}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left ...">{{ __('Client') }}</th>
                            <th class="px-6 py-3 text-left ...">{{ __('Invoice Number') }}</th>
                            <th class="px-6 py-3 text-left ...">{{ __('Due Date') }}</th>
                            <th class="px-6 py-3 text-right ...">{{ __('Amount') }}</th>
                            <th class="px-6 py-3 text-center ...">{{ __('Days Overdue') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $allInvoices = collect($agingData)->flatten(); @endphp
                        @forelse ($allInvoices as $invoice)
                            <tr>
                                <td class="px-6 py-4 ...">{{ $invoice->project->client }}</td>
                                <td class="px-6 py-4 ...">{{ $invoice->invoice_number }}</td>
                                <td class="px-6 py-4 ...">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($invoice->amount, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center font-semibold {{ \Carbon\Carbon::parse($invoice->due_date)->isPast() ? 'text-red-600' : 'text-green-600' }}">
                                    {{ \Carbon\Carbon::parse($invoice->due_date)->isPast() ? \Carbon\Carbon::parse($invoice->due_date)->diffInDays(now()) : 0 }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center ...">{{ __('All client invoices are paid.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
