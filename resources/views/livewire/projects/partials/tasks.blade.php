<div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">{{ __('Task Management (WBS)') }}</h2>
        <button wire:click="createTask()" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
            + {{ __('Add New Task') }}
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Task Name') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Start Date') }} - {{ __('End Date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($tasks as $task)
                    <tr>
                        <td class="px-6 py-4">{{ $task->name }}</td>
                        <td class="px-6 py-4">
                            {{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('d M Y') : '-' }} 
                            s/d 
                            {{ $task->end_date ? \Carbon\Carbon::parse($task->end_date)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ __($task->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <button wire:click="editTask({{ $task->id }})" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                            <button wire:click="deleteTask({{ $task->id }})" wire:confirm="{{ __('Are you sure you want to delete this task?') }}" class="text-red-600 hover:text-red-900 ml-4">{{ __('Delete') }}</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">{{ __('No task data yet.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
