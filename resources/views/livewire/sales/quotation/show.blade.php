<div>
    <x-slot name="header">
        {{ __('Quotation Detail') }}: {{ $quotation->quotation_number }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">

            {{-- Tombol Aksi --}}
            <div class="flex justify-end space-x-2 mb-6 no-print" x-data="{ open: false }">
                <div class="relative">
                    <button @click="open = !open" class="inline-flex items-center bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700">
                        <span>{{ __('Actions') }}</span>
                        <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10">
                        <div class="py-1">
                            <a href="javascript:window.print()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Print') }}</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Export to PDF') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Header --}}
            <div class="border-b pb-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Quotation') }}</h2>
                <div class="grid grid-cols-2 mt-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('To') }}:</p>
                        <p class="font-semibold">{{ $quotation->client_name }}</p>
                        <p class="text-sm text-gray-600">{{ $quotation->client_contact }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ __('Quotation Number') }}</p>
                        <p class="font-semibold">{{ $quotation->quotation_number }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ __('Date') }}</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Tabel Item --}}
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Description') }}</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">{{ __('Quantity') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Unit') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Unit Price') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($quotation->items as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->description }}</td>
                                <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                                <td class="px-4 py-3">{{ $item->unit }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($item->price, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($item->total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 font-bold">
                            <td colspan="4" class="px-4 py-2 text-right">{{ __('Grand Total') }}</td>
                            <td class="px-4 py-2 text-right">Rp {{ number_format($quotation->total_amount, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Notes dan Status --}}
            <div class="mt-6 border-t pt-4">
                <div class="grid grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Notes') }}</p>
                        <p>{{ $quotation->notes ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                        <p class="font-bold text-lg">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                @if($quotation->status == 'Disetujui') bg-green-100 text-green-800
                                @elseif($quotation->status == 'Ditolak') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ __($quotation->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tombol Convert --}}
            @if($quotation->status == 'Disetujui')
                <div class="border-t border-gray-100 my-1"></div>
                <button 
                    wire:click="convertToProject" 
                    onclick="confirm('{{ __('Are you sure you want to convert this quotation to a new project?') }}') || event.stopImmediatePropagation()" 
                    class="block w-full text-left px-4 py-2 text-sm text-emerald-700 hover:bg-emerald-50 font-semibold">
                    {{ __('Convert to Project') }}
                </button>
            @endif

        </div>
    </div>
</div>
