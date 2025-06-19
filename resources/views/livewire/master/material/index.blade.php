<div>
    <x-slot name="header">{{ __('Master Materials') }}</x-slot>
    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Material & Price List') }}</h2>
                <button wire:click="create()" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">+ {{ __('Add New Material') }}</button>
            </div>
            @if (session()->has('message'))<div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ __(session('message')) }}</div>@endif
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Material Name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Unit') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Price') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr></thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($materials as $material)
                            <tr>
                                <td class="px-6 py-4 font-semibold">{{ $material->name }}</td>
                                <td class="px-6 py-4">{{ $material->unit }}</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($material->price, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <button wire:click="edit({{ $material->id }})" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                                    <button wire:click="delete({{ $material->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-900 ml-4">{{ __('Delete') }}</button>
                                </td>
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
    <div class="fixed z-10 inset-0 overflow-y-auto"><div class="flex items-center justify-center min-h-screen"><div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div><div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
        <form>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $materialId ? __('Edit Material') : __('Add New Material') }}</h3>
                <div class="mt-4 space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700">{{ __('Material Name') }}</label><input type="text" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">@error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm font-medium text-gray-700">{{ __('Unit') }}</label><input type="text" wire:model="unit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">@error('unit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm font-medium text-gray-700">{{ __('Price') }}</label><input type="number" wire:model="price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">@error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"><button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Save') }}</button><button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button></div>
        </form>
    </div></div></div>
    @endif
</div>
