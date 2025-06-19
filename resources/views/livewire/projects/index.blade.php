<div>
    <x-slot name="header">
        {{ __('Project List') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Project Data Management') }}</h2>
                <button wire:click="create()" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
                    + {{ __('Add New Project') }}
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Project Name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Client') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Budget') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($projects as $project)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    <a href="{{ route('projects.show', $project->id) }}" class="text-emerald-600 hover:text-emerald-800 hover:underline">
                                        {{ $project->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $project->client }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($project->budget, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $project->status == 'Selesai' ? 'bg-green-100 text-green-800' : ($project->status == 'Berjalan' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ __($project->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="edit({{ $project->id }})" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                                    <button wire:click="delete({{ $project->id }})" wire:confirm="{{ __('Are you sure you want to delete this project?') }}" class="text-red-600 hover:text-red-900 ml-4">{{ __('Delete') }}</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    {{ __('No project data yet.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal Form -->
    @if ($isModalOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $projectId ? __('Edit Project') : __('Add New Project') }}
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Project Name') }}</label>
                                    <input type="text" wire:model.lazy="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="client" class="block text-sm font-medium text-gray-700">{{ __('Client') }}</label>
                                    <input type="text" wire:model.lazy="client" id="client" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="budget" class="block text-sm font-medium text-gray-700">{{ __('Budget') }}</label>
                                    <input type="number" wire:model.lazy="budget" id="budget" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                 <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('Start Date') }}</label>
                                    <input type="date" wire:model.lazy="start_date" id="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                 <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('End Date') }}</label>
                                    <input type="date" wire:model.lazy="end_date" id="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                                    <select wire:model.lazy="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="Baru">{{ __('Baru') }}</option>
                                        <option value="Berjalan">{{ __('Berjalan') }}</option>
                                        <option value="Selesai">{{ __('Selesai') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Save') }}
                            </button>
                            <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
