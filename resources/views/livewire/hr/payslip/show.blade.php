<div>
    <x-slot name="header">
        {{ __('Payslip Detail') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl mx-auto">
            
            <div class="flex justify-end mb-6 no-print">
                {{-- Tombol Aksi Universal --}}
                <div x-data="{ open: false }">
                    <div class="relative">
                        <button @click="open = !open" class="inline-flex items-center bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700">
                            <span>{{ __('Actions') }}</span>
                            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10">
                            <div class="py-1">
                                <a href="javascript:window.print()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Print') }}</a>
                                <a href="{{ route('hr.payslips.pdf', $payslip->id) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Export to PDF') }}</a>
                                <a href="https://wa.me/?text={{ urlencode('Lihat detail slip gaji untuk ' . ($payslip->employee->user->name ?? '') . ' periode ' . $payslip->payroll->payroll_period . ': ' . route('hr.payslips.show', $payslip->id)) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Share to WhatsApp') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Header Slip Gaji --}}
            <div class="text-center border-b-2 border-gray-200 pb-4 mb-6">
                <h2 class="text-3xl font-bold text-gray-800">{{ __('Payslip') }}</h2>
                <p class="text-gray-500">{{ $payslip->payroll->payroll_period }}</p>
            </div>

            {{-- Info Karyawan --}}
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div>
                    <p class="font-semibold">{{ $payslip->employee->user->name ?? $payslip->employee->employee_id_number }}</p>
                    <p class="text-sm text-gray-600">{{ $payslip->employee->position }}</p>
                    <p class="text-sm text-gray-600">{{ __('Employee ID') }}: {{ $payslip->employee->employee_id_number }}</p>
                </div>
                 <div class="text-right">
                    <p class="text-sm text-gray-600">{{ __('Pay Date') }}: {{ \Carbon\Carbon::parse($payslip->payroll->end_date)->format('d F Y') }}</p>
                </div>
            </div>

            {{-- Rincian Gaji & Total --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                {{-- Kolom Pendapatan & Potongan --}}
                <div class="md:col-span-3">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <h3 class="font-bold text-lg border-b pb-2 mb-2">{{ __('Earnings') }}</h3>
                            <dl>
                                @foreach($payslip->items->where('type', 'earning')->where('amount', '>', 0) as $item)
                                <div class="flex justify-between py-1"><dt class="text-gray-600">{{ __($item->description) }}</dt><dd>Rp {{ number_format($item->amount, 2, ',', '.') }}</dd></div>
                                @endforeach
                            </dl>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg border-b pb-2 mb-2">{{ __('Deductions') }}</h3>
                             <dl>
                                @forelse($payslip->items->where('type', 'deduction') as $item)
                                <div class="flex justify-between py-1"><dt class="text-gray-600">{{ __($item->description) }}</dt><dd>(Rp {{ number_format($item->amount, 2, ',', '.') }})</dd></div>
                                @empty
                                <div class="flex justify-between py-1"><dt class="text-gray-600">{{__('No deductions')}}</dt><dd>Rp 0.00</dd></div>
                                @endforelse
                            </dl>
                        </div>
                    </div>
                </div>
                {{-- Kolom Ringkasan --}}
                <div class="md:col-span-2 bg-slate-50 p-4 rounded-lg">
                    <h3 class="font-bold text-lg border-b pb-2 mb-2">{{ __('Summary') }}</h3>
                    <dl>
                        @foreach($payslip->items->where('type', 'earning')->where('amount', 0) as $item)
                        <div class="flex justify-between py-1 text-sm"><dt class="text-gray-600">{{ __($item->description) }}</dt></div>
                        @endforeach
            
                        <div class="flex justify-between py-1 mt-4"><dt class="text-gray-600">{{ __('Gross Salary') }}</dt><dd>Rp {{ number_format($payslip->gross_salary, 2, ',', '.') }}</dd></div>
                        <div class="flex justify-between py-1"><dt class="text-gray-600">{{ __('Total Deductions') }}</dt><dd>(Rp {{ number_format($payslip->total_deductions, 2, ',', '.') }})</dd></div>
                        <div class="flex justify-between py-2 mt-2 border-t-2 font-bold text-lg">
                            <dt>{{ __('Net Salary') }}</dt>
                            <dd>Rp {{ number_format($payslip->net_salary, 2, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>
    </div>
</div>
