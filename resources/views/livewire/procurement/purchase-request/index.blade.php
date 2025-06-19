<div>
    <x-slot name="header">
        {{ __('Purchase Requests') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Purchase Request List') }}</h2>
                <button wire:click="create()" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
                    + {{ __('Add New PR') }}
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('PR Number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Project') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Request Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Requester') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($purchaseRequests as $pr)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-emerald-600">
    <a href="{{ route('procurement.pr.show', $pr->id) }}" class="hover:underline">
        {{ $pr->pr_number }}
    </a>
</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $pr->project->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($pr->request_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $pr->requester->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($pr->status == 'Disetujui') bg-green-100 text-green-800
                                    @elseif($pr->status == 'Ditolak') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                        {{ __($pr->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="edit({{ $pr->id }})" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                                    <button wire:click="delete({{ $pr->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-900 ml-4">{{ __('Delete') }}</button>
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
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $prId ? __('Edit Purchase Request') : __('Add New Purchase Request') }}</h3>
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="project_id" class="block text-sm font-medium text-gray-700">{{ __('Project') }}</label>
                                    <select wire:model="project_id" id="project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">{{ __('Select Project') }}</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="request_date" class="block text-sm font-medium text-gray-700">{{ __('Request Date') }}</label>
                                    <input type="date" wire:model="request_date" id="request_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('request_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                                    <select wire:model="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="Draft">{{ __('Draft') }}</option>
                                        <option value="Menunggu Persetujuan">{{ __('Menunggu Persetujuan') }}</option>
                                        <option value="Disetujui">{{ __('Disetujui') }}</option>
                                        <option value="Ditolak">{{ __('Ditolak') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-800">{{ __('Items') }}</h4>
                                <div class="mt-4 space-y-4">
                                    @foreach($items as $index => $item)
                                        <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded-md" wire:key="item-{{ $index }}">
                                            <div class="grid grid-cols-12 gap-2 flex-1">
                                                <input type="text" wire:model="items.{{ $index }}.item_name" placeholder="{{ __('Item Name') }}" class="col-span-4 border-gray-300 rounded-md shadow-sm">
                                                <input type="number" wire:model="items.{{ $index }}.quantity" placeholder="{{ __('Qty') }}" class="col-span-2 border-gray-300 rounded-md shadow-sm">
                                                <input type="text" wire:model="items.{{ $index }}.unit" placeholder="{{ __('Unit') }}" class="col-span-2 border-gray-300 rounded-md shadow-sm">
                                                <input type="text" wire:model="items.{{ $index }}.description" placeholder="{{ __('Description') }}" class="col-span-4 border-gray-300 rounded-md shadow-sm">
                                            </div>
                                            <button wire:click.prevent="removeItem({{ $index }})" class="text-red-500 hover:text-red-700 p-2"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                        </div>
                                    @endforeach
                                </div>
                                <button wire:click.prevent="addItem" class="mt-4 text-sm font-medium text-emerald-600 hover:text-emerald-800">+ {{ __('Add Item') }}</button>
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
