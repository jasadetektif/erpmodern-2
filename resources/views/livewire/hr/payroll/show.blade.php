<div>
    <x-slot name="header">
        {{ __('Payroll Detail') }}: {{ $payroll->payroll_period }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            
            <div class="flex justify-end space-x-2 mb-6">
                <button class="bg-gray-200 text-gray-800 font-bold px-4 py-2 rounded-md hover:bg-gray-300">{{ __('Export All') }}</button>
            </div>

            <div class="border-b pb-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ $payroll->payroll_period }}</h2>
                <div class="grid grid-cols-2 lg:grid-cols-3 mt-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Date Range') }}</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($payroll->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($payroll->end_date)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Total Employees') }}</p>
                        <p class="font-semibold">{{ $payroll->payslips->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                        <p class="font-bold text-lg">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ __($payroll->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Daftar Slip Gaji --}}
            <h3 class="text-xl font-semibold mb-4">{{ __('Generated Payslips') }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Employee Name') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Position') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Gross Salary') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Total Deductions') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Net Salary') }}</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payroll->payslips as $payslip)
                            <tr>
                                <td class="px-4 py-3 font-semibold">{{ $payslip->employee->user->name ?? $payslip->employee->employee_id_number }}</td>
                                <td class="px-4 py-3">{{ $payslip->employee->position }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($payslip->gross_salary, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($payslip->total_deductions, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-bold">Rp {{ number_format($payslip->net_salary, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('hr.payslips.show', $payslip->id) }}" class="text-emerald-600 hover:underline">{{ __('View') }}</a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
