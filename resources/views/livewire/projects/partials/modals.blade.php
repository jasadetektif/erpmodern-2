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
                                <label><input type="radio" wire:model.live="team_payment_type" value="harian" class="form-radio text-emerald-600"> <span class="ml-2">{{__('Daily')}}</span></label>
                                <label><input type="radio" wire:model.live="team_payment_type" value="borongan" class="form-radio text-emerald-600"> <span class="ml-2">{{__('Lump Sum')}}</span></label>
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

<!-- Modal Edit Tim -->
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{__('Payment Type')}}</label>
                            <div class="mt-2 space-x-4">
                                <label><input type="radio" wire:model.live="editingTeam.payment_type" value="harian" class="form-radio text-emerald-600"> <span class="ml-2">{{__('Daily')}}</span></label>
                                <label><input type="radio" wire:model.live="editingTeam.payment_type" value="borongan" class="form-radio text-emerald-600"> <span class="ml-2">{{__('Lump Sum')}}</span></label>
                            </div>
                        </div>
                        @if($editingTeam->payment_type == 'harian')
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{__('Number of Workers')}}</label>
                                <input type="number" wire:model="editingTeam.number_of_workers" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{__('Worker Daily Wage')}}</label>
                                <input type="number" wire:model="editingTeam.worker_daily_wage" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{__('Lump Sum Value')}}</label>
                                <input type="number" wire:model="editingTeam.lump_sum_value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{__('Work Progress (%)')}}</label>
                                <input type="number" wire:model="editingTeam.work_progress" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
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

<!-- Modal Tugas -->
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

<!-- Modal Pengeluaran -->
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
