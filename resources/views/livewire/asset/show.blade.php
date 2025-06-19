<div>
    <x-slot name="header">
        {{ __('Asset Detail') }}
    </x-slot>

    <div class="py-4 space-y-6">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ __(session('message')) }}</span>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-lg">
            {{-- Info Aset Utama --}}
            <div class="flex items-start space-x-6">
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $asset->name }}</h2>
                    <p class="text-lg text-gray-600">{{ $asset->asset_code }}</p>
                    <p class="text-sm text-gray-500 mt-2">{{ $asset->description }}</p>
                </div>
                <div class="flex-shrink-0 text-right">
                    <p class="text-sm font-medium text-gray-500">{{__('Status')}}</p>
                    <span class="px-3 py-1 mt-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        @if($asset->status == 'Tersedia') bg-green-100 text-green-800
                        @elseif($asset->status == 'Dalam Perbaikan') bg-red-100 text-red-800
                        @else bg-blue-100 text-blue-800 @endif">
                        {{ __($asset->status) }}
                    </span>
                </div>
            </div>

            {{-- Detail Lainnya --}}
            <div class="mt-6 border-t border-gray-200 pt-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-8">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Current Location') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $asset->project->name ?? __('Warehouse') }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Purchase Date') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->purchase_date ? \Carbon\Carbon::parse($asset->purchase_date)->format('d F Y') : '-' }}</dd>
                    </div>
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Purchase Price') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($asset->purchase_price, 2, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Panel Riwayat Pemeliharaan -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
             <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">{{ __('Maintenance History') }}</h2>
                <button wire:click="createMaintenance()" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
                    + {{ __('Add Maintenance Record') }}
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Date')}}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Type')}}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Description')}}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Cost')}}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Conducted By')}}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($asset->maintenances as $maintenance)
                            <tr>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('d M Y') }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $maintenance->type }}</td>
                                <td class="px-4 py-3">{{ $maintenance->description }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($maintenance->cost, 2, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $maintenance->conductedBy->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-3 text-center text-gray-500">{{__('No maintenance history available.')}}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form Maintenance -->
    @if ($isModalOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Add Maintenance Record') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{__('Maintenance Date')}}</label>
                                    <input type="date" wire:model="maintenance_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('maintenance_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{__('Maintenance Type')}}</label>
                                    <input type="text" wire:model="maintenance_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('maintenance_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{__('Description')}}</label>
                                    <textarea wire:model="maintenance_description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                    @error('maintenance_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{__('Cost')}}</label>
                                    <input type="number" wire:model="maintenance_cost" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('maintenance_cost') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click.prevent="storeMaintenance" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Save Record') }}</button>
                            <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
