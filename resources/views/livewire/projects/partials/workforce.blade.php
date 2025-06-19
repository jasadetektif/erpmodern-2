<div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">{{ __('Project Workforce') }}</h2>
        <button wire:click="openTeamModal" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
            + {{ __('Assign Foreman') }}
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">{{ __('Foreman Name') }}</th>
                    <th class="px-6 py-3 text-center">{{ __('Payment Type') }}</th>
                    <th class="px-6 py-3 text-right">{{ __('Total Cost (2 Weeks)') }}</th>
                    <th class="px-6 py-3 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($project->teams as $team)
                    @php
                        $cost = 0;
                        if ($team->payment_type == 'harian') {
                            $mandorSalary = ($team->employee->basic_salary ?? 0) / 2;
                            $workerWages = $team->number_of_workers * $team->worker_daily_wage * 12;
                            $cost = $mandorSalary + $workerWages;
                        } else {
                            $cost = $team->lump_sum_value * ($team->work_progress / 100);
                        }
                    @endphp
                    <tr>
                        <td class="px-6 py-4">{{ $team->employee->user->name ?? $team->employee->employee_id_number }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-slate-100 text-slate-800">
                                {{ __($team->payment_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold">Rp {{ number_format($cost, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <button wire:click="editTeam({{ $team->id }})" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                            <button wire:click="removeMandor({{ $team->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-900 ml-4">{{ __('Remove') }}</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            {{ __('No foreman has been assigned to this project.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
