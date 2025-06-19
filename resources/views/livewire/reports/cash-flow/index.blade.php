<div>
    <x-slot name="header">
        {{ __('Cash Flow Report') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                 <h2 class="text-xl font-semibold">{{ __('Cash Flow Report') }}</h2>
                 <div class="flex items-center gap-4">
                    <input type="date" wire:model.live="startDate" class="border-gray-300 rounded-md shadow-sm">
                    <span class="text-gray-500">s/d</span>
                    <input type="date" wire:model.live="endDate" class="border-gray-300 rounded-md shadow-sm">
                    <a href="{{ route('reports.cash-flow.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700">
            {{ __('Export') }}
        </a>
                 </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Description') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Cash In') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Cash Out') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Balance') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $balance = 0; @endphp
                        @forelse ($transactions as $transaction)
                            @php $balance += $transaction->cash_in - $transaction->cash_out; @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-green-600">
                                    {{ $transaction->cash_in > 0 ? 'Rp ' . number_format($transaction->cash_in, 2, ',', '.') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-red-600">
                                    {{ $transaction->cash_out > 0 ? 'Rp ' . number_format($transaction->cash_out, 2, ',', '.') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-semibold">
                                    Rp {{ number_format($balance, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">{{ __('No transactions in this period.') }}</td></tr>
                        @endforelse
                    </tbody>
                     <tfoot class="bg-gray-100">
                        <tr class="font-bold">
                            <td colspan="2" class="px-6 py-3 text-right">Total</td>
                            <td class="px-6 py-3 text-right text-green-600">Rp {{ number_format($transactions->sum('cash_in'), 2, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right text-red-600">Rp {{ number_format($transactions->sum('cash_out'), 2, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right">Rp {{ number_format($balance, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>