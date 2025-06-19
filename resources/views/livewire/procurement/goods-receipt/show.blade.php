<div>
    <x-slot name="header">
        {{ __('Goods Receipt Detail') }}: {{ $goodsReceipt->gr_number }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            
            <div class="flex justify-end space-x-2 mb-6">
                <button class="bg-gray-200 text-gray-800 font-bold px-4 py-2 rounded-md hover:bg-gray-300">{{ __('Print') }}</button>
            </div>

            <div class="border-b pb-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Goods Receipt') }}</h2>
                <div class="grid grid-cols-2 lg:grid-cols-3 mt-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Project') }}</p>
                        <p class="font-semibold">{{ $goodsReceipt->purchaseOrder->purchaseRequest->project->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('GR Number') }}</p>
                        <p class="font-semibold">{{ $goodsReceipt->gr_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Receipt Date') }}</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($goodsReceipt->receipt_date)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Based on PO') }}</p>
                        <p class="font-semibold">{{ $goodsReceipt->purchaseOrder->po_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Received By') }}</p>
                        <p class="font-semibold">{{ $goodsReceipt->receivedBy->name }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Item Name') }}</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">{{ __('Received Qty') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Unit') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Notes') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($goodsReceipt->items as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->purchaseOrderItem->item_name }}</td>
                                <td class="px-4 py-3 text-center">{{ $item->received_quantity }}</td>
                                <td class="px-4 py-3">{{ $item->purchaseOrderItem->unit }}</td>
                                <td class="px-4 py-3">{{ $item->notes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 border-t pt-4">
                <p class="text-sm text-gray-500">{{ __('Notes') }}</p>
                <p>{{ $goodsReceipt->notes ?? '-' }}</p>
            </div>

        </div>
    </div>
</div>
