<div>
    <x-slot name="header">
        {{ __('Client Payment Detail') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl mx-auto">
            
            <div class="flex justify-end mb-6 no-print">
                <button onclick="window.print()" class="bg-gray-200 text-gray-800 font-bold px-4 py-2 rounded-md hover:bg-gray-300">{{ __('Print') }}</button>
            </div>

            {{-- Header --}}
            <div class="text-center border-b-2 border-gray-200 pb-4 mb-6">
                <h2 class="text-3xl font-bold text-gray-800">{{ __('Payment Receipt') }}</h2>
                <p class="text-gray-500">{{ __('Payment Receipt Number') }}: {{ $clientPayment->payment_number }}</p>
            </div>

            {{-- Detail Transaksi --}}
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div>
                    <p class="text-sm text-gray-500">{{ __('Received from') }}:</p>
                    <p class="font-semibold text-lg">{{ $clientPayment->clientInvoice->project->client }}</p>
                    <p class="text-sm text-gray-600">{{ __('For Project') }}: {{ $clientPayment->clientInvoice->project->name }}</p>
                </div>
                 <div class="text-right">
                    <p class="text-sm text-gray-500">{{ __('Payment Date') }}</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($clientPayment->payment_date)->format('d F Y') }}</p>
                     <p class="text-sm text-gray-500 mt-2">{{ __('Processed By') }}</p>
                    <p class="font-semibold">{{ $clientPayment->receivedBy->name }}</p>
                </div>
            </div>

            {{-- Rincian Pembayaran --}}
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
                            <td class="px-4 py-3">
                                <p>{{ __('Payment for Invoice') }}</p>
                                <p class="text-sm text-gray-600 font-semibold">{{ $clientPayment->clientInvoice->invoice_number }}</p>
                            </td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($clientPayment->amount, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                     <tfoot>
                        <tr class="bg-gray-50 font-bold">
                            <td class="px-4 py-2 text-right">{{ __('Total Paid') }}</td>
                            <td class="px-4 py-2 text-right">Rp {{ number_format($clientPayment->amount, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Catatan & Metode Pembayaran --}}
            <div class="mt-6 border-t pt-4">
                <div class="grid grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Payment Method') }}</p>
                        <p class="font-semibold">{{ $clientPayment->payment_method }}</p>
                    </div>
                    <div class="text-left">
                        <p class="text-sm text-gray-500">{{ __('Notes') }}</p>
                        <p>{{ $clientPayment->notes ?? '-' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
