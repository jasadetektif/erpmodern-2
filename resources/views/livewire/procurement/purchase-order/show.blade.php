<div>
    <x-slot name="header">
        {{ __('Purchase Order Detail') }}: {{ $purchaseOrder->po_number }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            
            <div class="flex justify-end space-x-2 mb-6">
                {{-- Hapus tombol Print lama dan ganti dengan ini --}}
<div class="flex justify-end mb-6 no-print" x-data="{ open: false }">
    <div class="relative">
        <button @click="open = !open" class="inline-flex items-center bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700">
            <span>{{ __('Actions') }}</span>
            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" 
             @click.away="open = false" 
             x-transition 
             class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10">
            <div class="py-1">
                <a href="javascript:window.print()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Print') }}</a>
                <a href="{{ route('procurement.po.pdf', $purchaseOrder->id) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Export to PDF') }}</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Send via Email') }}</a>
            </div>
        </div>
    </div>
</div>
            </div>

            <div class="border-b pb-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Purchase Order') }}</h2>
                <div class="grid grid-cols-2 lg:grid-cols-3 mt-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Project') }}</p>
                        <p class="font-semibold">{{ $purchaseOrder->purchaseRequest->project->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('PO Number') }}</p>
                        <p class="font-semibold">{{ $purchaseOrder->po_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Order Date') }}</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Supplier') }}</p>
                        <p class="font-semibold">{{ $purchaseOrder->supplier->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Ordered By') }}</p>
                        <p class="font-semibold">{{ $purchaseOrder->orderBy->name }}</p>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500">{{ __('Based on PR') }}</p>
                        <p class="font-semibold">{{ $purchaseOrder->purchaseRequest->pr_number }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Item Name') }}</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">{{ __('Quantity') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Unit') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Price') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Total Price') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($purchaseOrder->items as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->item_name }}</td>
                                <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                                <td class="px-4 py-3">{{ $item->unit }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($item->price, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($item->total_price, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-right font-bold">{{ __('Grand Total') }}</td>
                            <td class="px-4 py-3 text-right font-bold">Rp {{ number_format($purchaseOrder->total_amount, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-6 border-t pt-4">
                <div class="grid grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Notes') }}</p>
                        <p>{{ $purchaseOrder->notes ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                        <p class="font-bold text-lg">
                             <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                             @if($purchaseOrder->status == 'Diterima Penuh') bg-green-100 text-green-800
                             @elseif($purchaseOrder->status == 'Sebagian Diterima') bg-yellow-100 text-yellow-800
                             @else bg-blue-100 text-blue-800 @endif">
                                {{ __($purchaseOrder->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
