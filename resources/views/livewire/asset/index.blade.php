<div>
    <x-slot name="header">
        {{ __('Asset Management') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Asset List') }}</h2>
                <button wire:click="create()" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
                    + {{ __('Add New Asset') }}
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Asset Code') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Asset Name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Current Location') }}</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($assets as $asset)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-emerald-600">
    <a href="{{ route('assets.show', $asset->id) }}" class="hover:underline">
        {{ $asset->asset_code }}
    </a>
</td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ $asset->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $asset->project->name ?? __('Warehouse') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($asset->status == 'Tersedia') bg-green-100 text-green-800
                                    @elseif($asset->status == 'Dalam Perbaikan') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                        {{ __($asset->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="edit({{ $asset->id }})" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                                    <button wire:click="delete({{ $asset->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-900 ml-4">{{ __('Delete') }}</button>
                                </td>
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
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-2xl sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $assetId ? __('Edit Asset') : __('Add New Asset') }}</h3>
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Asset Code') }}</label>
                                    <input type="text" wire:model="asset_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Asset Name') }}</label>
                                    <input type="text" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                                    <textarea wire:model="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Purchase Date') }}</label>
                                    <input type="date" wire:model="purchase_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Purchase Price') }}</label>
                                    <input type="number" wire:model="purchase_price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                                    <select wire:model="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="Tersedia">{{__('Tersedia')}}</option>
                                        <option value="Digunakan">{{__('Digunakan')}}</option>
                                        <option value="Dalam Perbaikan">{{__('Dalam Perbaikan')}}</option>
                                    </select>
                                </div>
                                 <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Current Location') }}</label>
                                    <select wire:model="current_project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">{{__('Warehouse')}}</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Save') }}</button>
                            <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
