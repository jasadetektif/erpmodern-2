<div>
    <x-slot name="header">
        {{ __('Stock Usage') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Stock Usage List') }}</h2>
                <button wire:click="create()" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
                    + {{ __('Record New Usage') }}
                </button>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ __(session('message')) }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Usage Number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Project') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Usage Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Used By') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($stockUsages as $usage)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-emerald-600">{{ $usage->usage_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $usage->project->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($usage->usage_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $usage->usedBy->name }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">{{ __('No data available') }}</td></tr>
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
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Record Stock Usage') }}</h3>
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Project') }}</label>
                                    <select wire:model.live="project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">{{ __('Select Project') }}</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Usage Date') }}</label>
                                    <input type="date" wire:model="usage_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                            @if($project_id)
                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-800">{{ __('Used Items') }}</h4>
                                <div class="mt-4 space-y-4">
                                    @foreach($items as $index => $item)
                                        <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded-md" wire:key="usage-item-{{ $index }}">
                                            <div class="grid grid-cols-12 gap-2 flex-1">
                                                <div class="col-span-6">
                                                    <select wire:model.live="items.{{ $index }}.inventory_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                                        <option value="">{{ __('Select Item') }}</option>
                                                        @foreach($inventoryItems as $invItem)
                                                            <option value="{{ $invItem->id }}">{{ $invItem->item_name }} ({{__('Stock')}}: {{ $invItem->stock_quantity }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-span-4">
                                                    <input type="number" wire:model.live="items.{{ $index }}.used_quantity" placeholder="{{ __('Qty') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                                                </div>
                                                <div class="col-span-2">
                                                    <input type="text" value="{{ $item['unit'] }}" placeholder="{{ __('Unit') }}" class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
                                                </div>
                                            </div>
                                            <button wire:click.prevent="removeItem({{ $index }})" class="text-red-500 hover:text-red-700 p-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                        </div>
                                    @endforeach
                                </div>
                                <button wire:click.prevent="addItem" class="mt-4 text-sm font-medium text-emerald-600 hover:text-emerald-800">+ {{ __('Add Item') }}</button>
                            </div>
                            @endif
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Save Usage') }}</button>
                            <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
