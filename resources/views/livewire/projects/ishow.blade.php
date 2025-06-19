<div>
    <x-slot name="header">
        {{ __('Project Detail') }}: {{ $project->name }}
    </x-slot>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ __(session('message')) }}</span>
        </div>
    @endif

    <div class="py-4 space-y-6">
        <!-- Panel Ringkasan Keuangan -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">{{ __('Financial Summary') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Contract Value') }}</h3>
                    <p class="mt-1 text-2xl font-bold text-gray-900">Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Material Expenses') }}</h3>
                    <p class="mt-1 text-lg font-semibold text-red-500">Rp {{ number_format($project->purchaseOrders->sum('total_amount'), 0, ',', '.') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Actual Labor Cost (from Payroll)') }}</h3>
                    <p class="mt-1 text-lg font-semibold text-red-500">Rp {{ number_format($project->total_labor_cost, 0, ',', '.') }}</p>
                </div>
                 <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Other Expenses') }}</h3>
                    <p class="mt-1 text-lg font-semibold text-red-500">Rp {{ number_format($project->otherExpenses->sum('amount'), 0, ',', '.') }}</p>
                </div>
                 <div class="col-span-1 md:col-span-2 lg:col-span-4 grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-4 mt-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Total Expenses') }}</h3>
                        <p class="mt-1 text-xl font-bold text-red-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
                    </div>
                     <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Actual Profit / Loss') }}</h3>
                        <p class="mt-1 text-2xl font-bold {{ $profitOrLoss >= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($profitOrLoss, 0, ',', '.') }}</p>
                    </div>
                 </div>
            </div>
        </div>
        
        <!-- Panel Gantt Chart -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">{{ __('Project Timeline (Gantt Chart)') }}</h2>
            <div class="min-h-[300px]" wire:ignore>
                <svg id="gantt"></svg>
            </div>
            @if(empty($ganttTasks))
                <p class="text-center text-gray-500 py-4">{{__('Add tasks with start and end dates to see the Gantt chart.')}}</p>
            @endif
        </div>

        <!-- Panel Dokumen Proyek -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">{{ __('Project Documents') }}</h2>
            <form wire:submit.prevent="uploadDocument" class="mb-6 p-4 border rounded-lg bg-gray-50">
                <div class="flex items-center space-x-4">
                    <input type="file" wire:model="document" class="flex-grow block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"/>
                    <button type="submit" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 disabled:opacity-50" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ __('Upload') }}</span>
                        <span wire:loading>{{ __('Uploading...') }}</span>
                    </button>
                </div>
                 @error('document') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror
            </form>
            <div class="space-y-3">
                @forelse ($project->documents as $doc)
                    <div class="flex items-center justify-between p-3 bg-white border rounded-md hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            @if(Str::contains($doc->type, 'pdf'))
                                <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            @else
                                <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            @endif
                            <div>
                                <a href="{{ asset('storage/' . $doc->path) }}" target="_blank" class="font-semibold text-gray-800 hover:underline">{{ $doc->name }}</a>
                                <p class="text-xs text-gray-500">{{ __('Uploaded by') }} {{ $doc->uploadedBy->name }} {{ __('on') }} {{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y') }}</p>
                            </div>
                        </div>
                        <button wire:click="deleteDocument({{ $doc->id }})" wire:confirm="{{__('Are you sure?')}}" class="text-red-500 hover:text-red-700 text-sm font-semibold">{{__('Delete')}}</button>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">{{__('No documents have been uploaded for this project.')}}</p>
                @endforelse
            </div>
        </div>

        <!-- Panel Tenaga Kerja (Diperbarui dengan CRUD) -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
             <div class="flex justify-between items-center mb-4"><h2 class="text-xl font-semibold">{{ __('Project Workforce') }}</h2><button wire:click="openTeamModal" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">+ {{ __('Assign Foreman') }}</button></div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr>
                        <th class="px-6 py-3 text-left ...">{{__('Foreman Name')}}</th>
                        <th class="px-6 py-3 text-center ...">{{__('Payment Type')}}</th>
                        <th class="px-6 py-3 text-right ...">{{__('Total Cost (2 Weeks)')}}</th>
                        <th class="px-6 py-3 text-right ...">{{__('Actions')}}</th>
                    </tr></thead>
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
                                <td class="px-6 py-4 ...">{{ $team->employee->user->name ?? $team->employee->employee_id_number }}</td>
                                <td class="px-6 py-4 ... text-center"><span class="px-2 ... bg-slate-100 text-slate-800">{{ __($team->payment_type) }}</span></td>
                                <td class="px-6 py-4 ... text-right font-bold">Rp {{ number_format($cost, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 ... text-right text-sm font-medium">
                                    <button wire:click="editTeam({{ $team->id }})" class="text-indigo-600 hover:text-indigo-900">{{__('Edit')}}</button>
                                    <button wire:click="removeMandor({{ $team->id }})" wire:confirm="{{__('Are you sure?')}}" class="text-red-600 hover:text-red-900 ml-4">{{__('Remove')}}</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">{{__('No foreman has been assigned to this project.')}}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Panel Manajemen Tugas (WBS) -->
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Task Name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Start Date') }} - {{ __('End Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($tasks as $task)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $task->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('d M Y') : '-' }} s/d {{ $task->end_date ? \Carbon\Carbon::parse($task->end_date)->format('d M Y') : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ __($task->status) }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
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
    </div>

    <!-- Modal Form Tim -->
    @if ($isTeamModalOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Assign Foreman to Project') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{__('Select Foreman')}}</label>
                                    <select wire:model="team_employee_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">{{__('Select an Employee')}}</option>
                                        @foreach($mandorList as $mandor)
                                            <option value="{{ $mandor->id }}">{{ $mandor->user->name ?? $mandor->employee_id_number }}</option>
                                        @endforeach
                                    </select>
                                    @error('team_employee_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{__('Payment Type')}}</label>
                                    <div class="mt-2 space-x-4">
                                        <label class="inline-flex items-center"><input type="radio" wire:model.live="team_payment_type" value="harian" class="form-radio text-emerald-600"> <span class="ml-2">{{__('Daily')}}</span></label>
                                        <label class="inline-flex items-center"><input type="radio" wire:model.live="team_payment_type" value="borongan" class="form-radio text-emerald-600"> <span class="ml-2">{{__('Lump Sum')}}</span></label>
                                    </div>
                                </div>
                                @if($team_payment_type == 'harian')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{__('Number of Workers')}}</label>
                                        <input type="number" wire:model="team_number_of_workers" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{__('Worker Daily Wage')}}</label>
                                        <input type="number" wire:model="team_worker_daily_wage" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                @else
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{__('Lump Sum Value')}}</label>
                                        <input type="number" wire:model="team_lump_sum_value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{__('Work Progress (%)')}}</label>
                                        <input type="number" wire:model="team_work_progress" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click.prevent="assignMandor" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Assign') }}</button>
                            <button wire:click="closeTeamModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Modal Form Edit Tim (Baru) -->
    @if ($isTeamEditModalOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Edit Team Assignment') }}</h3>
                            <p class="text-sm text-gray-600">{{ $editingTeam->employee->user->name ?? '' }}</p>
                            <div class="mt-4 space-y-4">
                                <div><label class="block text-sm font-medium text-gray-700">{{__('Payment Type')}}</label>
                                    <div class="mt-2 space-x-4"><label><input type="radio" wire:model.live="editingTeam.payment_type" value="harian" class="form-radio text-emerald-600"> <span class="ml-2">{{__('Daily')}}</span></label><label><input type="radio" wire:model.live="editingTeam.payment_type" value="borongan" class="form-radio text-emerald-600"> <span class="ml-2">{{__('Lump Sum')}}</span></label></div>
                                </div>
                                @if($editingTeam->payment_type == 'harian')
                                    <div><label class="block text-sm font-medium text-gray-700">{{__('Number of Workers')}}</label><input type="number" wire:model="editingTeam.number_of_workers" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                    <div><label class="block text-sm font-medium text-gray-700">{{__('Worker Daily Wage')}}</label><input type="number" wire:model="editingTeam.worker_daily_wage" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                @else
                                    <div><label class="block text-sm font-medium text-gray-700">{{__('Lump Sum Value')}}</label><input type="number" wire:model="editingTeam.lump_sum_value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                    <div><label class="block text-sm font-medium text-gray-700">{{__('Work Progress (%)')}}</label><input type="number" wire:model="editingTeam.work_progress" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click.prevent="updateTeam" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Update') }}</button>
                            <button wire:click="closeTeamEditModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <!-- Modal Form Tugas -->
    @if ($isTaskModalOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $taskId ? __('Edit Task') : __('Add New Task') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Task Name') }}</label>
                                    <input type="text" wire:model.lazy="task_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                                    <textarea wire:model.lazy="task_description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Start Date') }}</label>
                                        <input type="date" wire:model.lazy="task_start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('End Date') }}</label>
                                        <input type="date" wire:model.lazy="task_end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Progress') }} (%)</label>
                                    <input type="range" wire:model.lazy="task_progress" min="0" max="100" class="mt-1 block w-full">
                                    <p class="text-center font-semibold">{{ $task_progress ?? 0 }}%</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click.prevent="storeTask()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Save') }}</button>
                            <button wire:click="closeTaskModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Form Pengeluaran Lain -->
    @if ($isExpenseModalOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Add Other Expense') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{__('Description')}}</label>
                                    <input type="text" wire:model="expense_description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{__('Amount')}}</label>
                                    <input type="number" wire:model="expense_amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click.prevent="storeExpense" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Save Expense') }}</button>
                            <button wire:click="closeExpenseModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

<!-- Panel Pengeluaran Lain-lain -->
<div class="bg-white p-6 rounded-lg shadow-lg">
     <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">{{ __('Other Expenses') }}</h2>
        <button wire:click="openExpenseModal" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
            + {{ __('Add Expense') }}
        </button>
    </div>
     <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Date')}}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Description')}}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Amount')}}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Recorded By')}}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Actions')}}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                 @forelse($project->otherExpenses as $expense)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">Rp {{ number_format($expense->amount, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button wire:click="deleteExpense({{ $expense->id }})" wire:confirm="{{__('Are you sure?')}}" class="text-red-600 hover:text-red-900">{{__('Delete')}}</button></td>
                    </tr>
                 @empty
                     <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">{{__('No other expense data available.')}}</td></tr>
                 @endforelse
            </tbody>
        </table>
    </div>
</div>




   @script
<script>
    document.addEventListener('livewire:navigated', () => {
        const tasks = @json($ganttTasks);
        const ganttEl = document.querySelector("#gantt");

        if (ganttEl) {
            if (window.ganttChartInstance) {
                window.ganttChartInstance.destroy();
            }
            ganttEl.innerHTML = '';

            console.log("GANTT TASKS", tasks); // Debug log

            if (tasks && tasks.length > 0) {
                try {
                    const formattedTasks = tasks.map(t => {
                        let progress = parseInt(t.progress);
                        progress = isNaN(progress) ? 0 : progress;
                        return {
                            id: t.id.toString(),
                            name: t.name,
                            start: new Date(t.start).toISOString().split('T')[0],
                            end: new Date(t.end).toISOString().split('T')[0],
                            progress: progress,
                            dependencies: t.dependencies || ''
                        };
                    });

                    window.ganttChartInstance = new Gantt("#gantt", formattedTasks, {
                        on_click: (task) => {
                            @this.call('editTask', task.id);
                        },
                        bar_height: 20,
                        padding: 18,
                        view_mode: 'Day'
                    });

                    setTimeout(() => {
                        document.querySelectorAll('.view-mode').forEach(el => {
                            if (el.innerText === 'Day') el.innerText = '{{ __("Day") }}';
                            if (el.innerText === 'Week') el.innerText = '{{ __("Week") }}';
                            if (el.innerText === 'Month') el.innerText = '{{ __("Month") }}';
                        });
                    }, 200);
                } catch(e) {
                    console.error("Error creating Gantt instance: ", e);
                }
            }
        }
    });
</script>
@endscript

</div>
