<div>
    <x-slot name="header">
        {{ __('Client Invoice Detail') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-4xl mx-auto">
            
            <div class="flex justify-end mb-6 no-print" x-data="{ open: false }">
                <div class="relative">
                    <button @click="open = !open" class="inline-flex items-center bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700">
                        <span>{{ __('Actions') }}</span>
                        <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10">
                        <div class="py-1">
                            <a href="javascript:window.print()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Print') }}</a>
                            <a href="{{ route('finance.client-invoices.pdf', $clientInvoice->id) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Export to PDF') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b pb-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Invoice') }}</h2>
                <div class="grid grid-cols-2 mt-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('To') }}:</p>
                        <p class="font-semibold">{{ $clientInvoice->project->client }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ __('Invoice Number') }}</p>
                        <p class="font-semibold">{{ $clientInvoice->invoice_number }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ __('Invoice Date') }}</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($clientInvoice->invoice_date)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Description') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3">{{ $clientInvoice->description }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($clientInvoice->amount, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                     <tfoot>
                        <tr class="bg-gray-50 font-bold">
                            <td class="px-4 py-2 text-right">{{ __('Grand Total') }}</td>
                            <td class="px-4 py-2 text-right">Rp {{ number_format($clientInvoice->amount, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-6 border-t pt-4">
                <div class="grid grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Due Date') }}</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($clientInvoice->due_date)->format('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                        <p class="font-bold text-lg">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($clientInvoice->status == 'Lunas') bg-green-100 text-green-800
                            @elseif($clientInvoice->status == 'Telat') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800 @endif">
                                {{ __($clientInvoice->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
