<div>
    <x-slot name="header">
        {{ __('Payroll Management') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Payroll Runs') }}</h2>
                <button wire:click="create()" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
                    + {{ __('Create New Run') }}
                </button>
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

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Payroll Period') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date Range') }}</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($payrolls as $payroll)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ $payroll->payroll_period }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($payroll->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($payroll->end_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payroll->status == 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ __($payroll->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($payroll->status == 'Draft')
                                        <button wire:click="processPayroll({{ $payroll->id }})" wire:confirm="{{ __('Are you sure you want to process this payroll? This action cannot be undone.') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Process') }}</button>
                                        <button wire:click="delete({{ $payroll->id }})" wire:confirm="{{ __('Are you sure you want to delete this payroll run?') }}" class="text-red-600 hover:text-red-900 ml-4">{{ __('Delete') }}</button>
                                    @else
                                        <a href="{{ route('hr.payrolls.show', $payroll->id) }}" class="text-emerald-600 hover:text-emerald-800">{{ __('View Payslips') }}</a>
                                        <button wire:click="delete({{ $payroll->id }})" wire:confirm="{{ __('Are you sure you want to delete this payroll run?') }}" class="text-red-600 hover:text-red-900 ml-4">{{ __('Delete') }}</button>
                                    @endif
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
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Create Payroll Run') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Payroll Period') }}</label>
                                    <input type="text" wire:model="payroll_period" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('payroll_period') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Start Date') }}</label>
                                    <input type="date" wire:model="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('End Date') }}</label>
                                    <input type="date" wire:model="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
