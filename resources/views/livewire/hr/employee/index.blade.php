<div>
    <x-slot name="header">
        {{ __('Employee Management') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Employee List') }}</h2>
                <button wire:click="create()" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
                    + {{ __('Add New Employee') }}
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Employee Name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Position') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Join Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($employees as $employee)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('hr.employees.show', $employee->id) }}" class="font-semibold text-emerald-600 hover:underline">{{ $employee->user->name ?? $employee->employee_id_number }}</a></td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $employee->position }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __($employee->status) }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="edit({{ $employee->id }})" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</button>
                                    <button wire:click="delete({{ $employee->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-900 ml-4">{{ __('Delete') }}</button>
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
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $employeeId ? __('Edit Employee') : __('Add New Employee') }}</h3>
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Info Pribadi -->
                                <div class="col-span-2"><h4 class="font-semibold text-gray-600">{{__('Personal Information')}}</h4></div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Link to User Account') }}</label>
                                    <select wire:model="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">{{ __('No Linked Account') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Employee ID') }}</label>
                                    <input type="text" wire:model="employee_id_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Position') }}</label>
                                    <input type="text" wire:model="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Join Date') }}</label>
                                    <input type="date" wire:model="join_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                 <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                                    <input type="text" wire:model="phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                 <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Address') }}</label>
                                    <textarea wire:model="address" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                </div>

                                 <!-- Komponen Gaji -->
                                <div class="col-span-2 pt-4 border-t"><h4 class="font-semibold text-gray-600">{{__('Salary Components')}}</h4></div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Basic Salary') }}</label>
                                    <input type="number" wire:model="basic_salary" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                 <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Transport Allowance') }}</label>
                                    <input type="number" wire:model="allowance_transport" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                 <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Meal Allowance') }}</label>
                                    <input type="number" wire:model="allowance_meal" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('PPh 21 Deduction') }}</label>
                                    <input type="number" wire:model="deduction_pph21" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('BPJS TK Deduction') }}</label>
                                    <input type="number" wire:model="deduction_bpjs_tk" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('BPJS Kesehatan Deduction') }}</label>
                                    <input type="number" wire:model="deduction_bpjs_kesehatan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
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
