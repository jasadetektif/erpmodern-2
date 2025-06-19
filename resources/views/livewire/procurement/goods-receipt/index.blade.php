<div>
    <x-slot name="header">
        {{ __('Goods Receipts') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Goods Receipt List') }}</h2>
                <button wire:click="create()" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
                    + {{ __('Create New GR') }}
                </button>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ __(session('message')) }}</span>
                </div>
            @endif

            <!-- Tabel Data -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('GR Number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('PO Number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Project') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Receipt Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Received By') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($goodsReceipts as $gr)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-emerald-600">
    <a href="{{ route('procurement.gr.show', $gr->id) }}" class="hover:underline">
        {{ $gr->gr_number }}
    </a>
</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $gr->purchaseOrder->po_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $gr->purchaseOrder->purchaseRequest->project->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($gr->receipt_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $gr->receivedBy->name }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">{{ __('No data available') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isModalOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Create New Goods Receipt') }}</h3>
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Base Purchase Order') }}</label>
                                    <select wire:model.live="purchase_order_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">{{ __('Select PO') }}</option>
                                        @foreach($purchaseOrders as $po)
                                            <option value="{{ $po->id }}">{{ $po->po_number }} - {{ $po->supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Receipt Date') }}</label>
                                    <input type="date" wire:model="receipt_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                            @if(!empty($items))
                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-800">{{ __('Items to Receive') }}</h4>
                                <div class="mt-4 overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">{{__('Item Name')}}</th>
                                                <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">{{__('Ordered')}}</th>
                                                <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">{{__('Prev. Received')}}</th>
                                                <th class="py-2 px-3 text-center text-xs font-medium text-gray-500 uppercase">{{__('Received Qty')}}</th>
                                                <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">{{__('Notes')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($items as $index => $item)
                                            <tr wire:key="gr-item-{{ $index }}">
                                                <td class="py-2 px-3">{{ $item['item_name'] }}</td>
                                                <td class="py-2 px-3 text-center">{{ $item['ordered_quantity'] }} {{ $item['unit'] }}</td>
                                                <td class="py-2 px-3 text-center">{{ $item['previously_received'] }} {{ $item['unit'] }}</td>
                                                <td class="py-2 px-3 w-32">
                                                    <input type="number" wire:model.live="items.{{ $index }}.received_quantity" class="w-full border-gray-300 rounded-md shadow-sm text-center">
                                                </td>
                                                <td class="py-2 px-3 w-48">
                                                    <input type="text" wire:model.live="items.{{ $index }}.notes" class="w-full border-gray-300 rounded-md shadow-sm">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Save Receipt') }}</button>
                            <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>