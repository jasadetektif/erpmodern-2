<div>
    <x-slot name="header">
        {{ __('Employee Detail') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            
            <div class="flex items-start space-x-6">
                {{-- Foto Profil (Placeholder) --}}
                <div class="flex-shrink-0">
                    <img class="h-24 w-24 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($employee->user->name ?? 'E') }}&color=7F9CF5&background=EBF4FF" alt="Employee Photo">
                </div>

                {{-- Info Utama --}}
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $employee->user->name ?? $employee->employee_id_number }}</h2>
                    <p class="text-lg text-gray-600">{{ $employee->position }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ __('Employee ID') }}: {{ $employee->employee_id_number }}</p>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex-shrink-0">
                     <button class="bg-gray-200 text-gray-800 font-bold px-4 py-2 rounded-md hover:bg-gray-300">{{ __('Print') }}</button>
                </div>
            </div>

            {{-- Detail Kontak & Pekerjaan --}}
            <div class="mt-6 border-t border-gray-200 pt-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-8">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Email') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $employee->user->email ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Phone') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $employee->phone ?? '-' }}</dd>
                    </div>
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Join Date') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($employee->join_date)->format('d F Y') }}</dd>
                    </div>
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Employment Status') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ __($employee->status) }}
                            </span>
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Address') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $employee->address ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-3 border-t border-gray-100 pt-4 mt-4">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Basic Salary') }}</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($employee->basic_salary, 2, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>