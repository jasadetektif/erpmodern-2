<div>
    <x-slot name="header">
        {{ __('Attendance Management') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h2 class="text-xl font-semibold">{{ __('Daily Attendance Record') }}</h2>
                <div class="flex items-center gap-4">
                    <select wire:model.live="selected_project_id" class="border-gray-300 rounded-md shadow-sm">
                        <option value="">{{ __('Select Project') }}</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                    <input type="date" wire:model.live="selected_date" class="border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ __(session('message')) }}</span>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($selected_project_id)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Employee Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Attendance Status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Notes') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($employees as $employee)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ $employee->user->name ?? $employee->employee_id_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-x-4 gap-y-2">
                                            @foreach(['Hadir', 'Sakit', 'Izin', 'Alpha'] as $status)
                                                <label class="inline-flex items-center">
                                                    <input type="radio" wire:model="attendances.{{ $employee->id }}.status" value="{{ $status }}" class="form-radio text-emerald-600 focus:ring-emerald-500">
                                                    <span class="ml-2">{{ __($status) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="text" wire:model="attendances.{{ $employee->id }}.notes" class="w-full border-gray-300 rounded-md shadow-sm">
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">{{ __('No employees assigned to this project.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($employees) > 0)
                <div class="flex justify-end mt-6">
                    <button wire:click="saveAttendances" class="bg-emerald-600 text-white font-bold px-6 py-2 rounded-md hover:bg-emerald-700">
                        {{ __('Save Attendances') }}
                    </button>
                </div>
                @endif
            @else
                 <p class="text-center text-gray-500 py-8">{{__('Please select a project and date to view attendance data.')}}</p>
            @endif

        </div>
    </div>
</div>