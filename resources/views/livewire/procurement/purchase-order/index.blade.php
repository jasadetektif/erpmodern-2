<div>
    <x-slot name="header">
        {{ __('Purchase Orders') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Purchase Order List') }}</h2>
                <button wire:click="create()" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
                    + {{ __('Create New PO') }}
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('PO Number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Project') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Supplier') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Order Date') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total Amount') }}</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($purchaseOrders as $po)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-emerald-600">
    <a href="{{ route('procurement.po.show', $po->id) }}" class="hover:underline">
        {{ $po->po_number }}
    </a>
</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $po->purchaseRequest->project->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $po->supplier->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">Rp {{ number_format($po->total_amount, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __($po->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">{{ __('No data available') }}</td></tr>
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Create New Purchase Order') }}</h3>
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Base Purchase Request') }}</label>
                                    <select wire:model.live="purchase_request_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">{{ __('Select PR') }}</option>
                                        @foreach($purchaseRequests as $pr)
                                            <option value="{{ $pr->id }}">{{ $pr->pr_number }} - {{ $pr->project->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('purchase_request_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Supplier') }}</label>
                                    <select wire:model="supplier_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">{{ __('Select Supplier') }}</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                     @error('supplier_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Order Date') }}</label>
                                    <input type="date" wire:model="order_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                     @error('order_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @if(!empty($items))
                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-800">{{ __('Items to Order') }}</h4>
                                <div class="mt-4 overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">{{__('Item Name')}}</th>
                                                <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">{{__('Qty')}}</th>
                                                <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">{{__('Unit')}}</th>
                                                <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">{{__('Price')}}</th>
                                                <th class="py-2 px-3 text-right text-xs font-medium text-gray-500 uppercase">{{__('Total Price')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($items as $index => $item)
                                            <tr wire:key="po-item-{{ $index }}">
                                                <td class="py-2 px-3">{{ $item['item_name'] }}</td>
                                                <td class="py-2 px-3 text-center">{{ $item['quantity'] }}</td>
                                                <td class="py-2 px-3 text-center">{{ $item['unit'] }}</td>
                                                <td class="py-2 px-3">
                                                    <input type="number" wire:model.live="items.{{ $index }}.price" class="w-full border-gray-300 rounded-md shadow-sm text-right">
                                                    @error('items.'.$index.'.price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </td>
                                                <td class="py-2 px-3 text-right">Rp {{ number_format($item['total_price'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right font-bold py-2 px-3">{{ __('Grand Total') }}:</td>
                                                <td class="font-bold py-2 px-3 text-right">Rp {{ number_format($total_amount, 0, ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Save PO') }}</button>
                            <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
