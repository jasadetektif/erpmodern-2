<div>
<div class="container mx-auto p-4 sm:p-6 lg:p-8 bg-gray-100 min-h-screen font-inter">
    {{-- Slot untuk header Livewire, sesuaikan jika layout Anda tidak menggunakan x-slot --}}
    <x-slot name="header">
        {{ __('Project Detail') }}: {{ $project->name }}
    </x-slot>

    {{-- Penanganan pesan sesi, muncul di bagian atas --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
            x-init="setTimeout(() => show = false, 3000)"
            class="mb-4 p-4 rounded-lg bg-green-500 text-white shadow-md"
            role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ __(session('message')) }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 mb-6">
        {{-- Judul Proyek dan Kode Proyek --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 pb-4 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-800 mb-2 sm:mb-0">
                {{ $project->name }}
            </h1>
            <div class="text-sm text-gray-600">
                <span class="font-semibold">{{ __('Project Code') }}:</span> {{ $project->project_code }}
            </div>
        </div>

        {{-- Navigasi Tab Utama --}}
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex flex-wrap gap-2 sm:gap-4 justify-center" aria-label="Tabs">
                <button wire:click="switchTab('overview')"
                    class="whitespace-nowrap px-4 py-2 text-sm font-medium rounded-t-lg transition-colors duration-200
                    {{ $activeTab === 'overview' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ __('Overview') }}
                </button>
                <button wire:click="switchTab('team')"
                    class="whitespace-nowrap px-4 py-2 text-sm font-medium rounded-t-lg transition-colors duration-200
                    {{ $activeTab === 'team' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ __('Team Management') }}
                </button>
                <button wire:click="switchTab('tasks')"
                    class="whitespace-nowrap px-4 py-2 text-sm font-medium rounded-t-lg transition-colors duration-200
                    {{ $activeTab === 'tasks' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ __('Task Management') }}
                </button>
                <button wire:click="switchTab('documents')"
                    class="whitespace-nowrap px-4 py-2 text-sm font-medium rounded-t-lg transition-colors duration-200
                    {{ $activeTab === 'documents' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ __('Documents') }}
                </button>
                <button wire:click="switchTab('expenses')"
                    class="whitespace-nowrap px-4 py-2 text-sm font-medium rounded-t-lg transition-colors duration-200
                    {{ $activeTab === 'expenses' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ __('Other Expenses') }}
                </button>
                <button wire:click="switchTab('rab')"
                    class="whitespace-nowrap px-4 py-2 text-sm font-medium rounded-t-lg transition-colors duration-200
                    {{ $activeTab === 'rab' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ __('RAB') }}
                </button>
            </nav>
        </div>

        {{-- Konten Tab --}}
        <div>
            @if ($activeTab === 'overview')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Panel Detail Proyek --}}
                    <div class="bg-indigo-50 p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-semibold text-indigo-700 mb-4">{{ __('Project Details') }}</h3>
                        <p class="mb-2"><strong class="text-gray-700">{{ __('Description') }}:</strong> {{ $project->description }}</p>
                        <p class="mb-2"><strong class="text-gray-700">{{ __('Start Date') }}:</strong> {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : 'N/A' }}</p>
                        <p class="mb-2"><strong class="text-gray-700">{{ __('End Date') }}:</strong> {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : 'N/A' }}</p>
                        <p class="mb-2"><strong class="text-gray-700">{{ __('Client') }}:</b> {{ $project->client_name }}</p>
                        <p class="mb-2"><strong class="text-gray-700">{{ __('Status') }}:</b> <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if ($project->status === 'In Progress') bg-blue-100 text-blue-800
                            @elseif ($project->status === 'Completed') bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ __($project->status) }}
                        </span></p>
                        <p class="mb-2"><strong class="text-gray-700">{{ __('Budget') }}:</strong> Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                        <div class="mt-4">
                            <h4 class="text-lg font-semibold text-gray-700 mb-2">{{ __('Project Progress') }}</h4>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-indigo-600 h-2.5 rounded-full"
                                    style="width: {{ $project->progress > 100 ? 100 : $project->progress }}%;"></div>
                            </div>
                            <p class="text-right text-sm text-gray-600 mt-1">{{ number_format($project->progress, 2) }}% {{ __('Completed') }}</p>
                        </div>
                    </div>

                    {{-- Panel Ringkasan Keuangan --}}
                    <div class="bg-green-50 p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold text-green-700 mb-4">{{ __('Financial Summary') }}</h2>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
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
                            <div class="col-span-1 lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4 mt-4">
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
                </div>
            @endif

            @if ($activeTab === 'team')
                {{-- Panel Manajemen Tenaga Kerja --}}
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">{{ __('Project Workforce') }}</h2>
                        <button wire:click="openTeamModal" class="bg-indigo-600 text-white font-bold px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                            + {{ __('Assign Foreman') }}
                        </button>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Foreman Name')}}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Payment Type')}}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Total Cost (2 Weeks)')}}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($project->teams as $team)
                                    @php
                                        $cost = 0;
                                        if ($team->payment_type == 'harian') {
                                            $mandorSalary = ($team->employee->basic_salary ?? 0) / 2; // Asumsi gaji dasar adalah bulanan, dibagi 2 untuk 2 minggu
                                            $workerWages = $team->number_of_workers * $team->worker_daily_wage * 12; // 12 hari dalam 2 minggu (6 hari/minggu)
                                            $cost = $mandorSalary + $workerWages;
                                        } else {
                                            $cost = $team->lump_sum_value * ($team->work_progress / 100);
                                        }
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $team->employee->user->name ?? $team->employee->employee_id_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">{{ __($team->payment_type) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold">Rp {{ number_format($cost, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button wire:click="editTeam({{ $team->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">{{__('Edit')}}</button>
                                            <button wire:click="removeMandor({{ $team->id }})" wire:confirm="{{__('Are you sure?')}}" class="text-red-600 hover:text-red-900">{{__('Remove')}}</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-4 text-center text-gray-600 italic">{{__('No foreman has been assigned to this project.')}}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($activeTab === 'tasks')
                {{-- Panel Manajemen Tugas (WBS) --}}
                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">{{ __('Task Management (WBS)') }}</h2>
                        <button wire:click="createTask()" class="bg-indigo-600 text-white font-bold px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                            + {{ __('Add New Task') }}
                        </button>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $task->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('d M Y') : '-' }} {{__('s/d')}} {{ $task->end_date ? \Carbon\Carbon::parse($task->end_date)->format('d M Y') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                @if ($task->status === 'Selesai') bg-green-100 text-green-800
                                                @elseif ($task->status === 'Sedang Dikerjakan') bg-blue-100 text-blue-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ __($task->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button wire:click="editTask({{ $task->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('Edit') }}</button>
                                            <button wire:click="deleteTask({{ $task->id }})" wire:confirm="{{ __('Are you sure you want to delete this task?') }}" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-4 text-center text-gray-600 italic">{{ __('No task data yet.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Panel Garis Waktu Proyek (Gantt Chart) --}}
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Project Timeline (Gantt Chart)') }}</h2>
                    <div class="min-h-[300px]" wire:ignore>
                        <svg id="gantt"></svg>
                    </div>
                    @if(empty($ganttTasks))
                        <p class="text-center text-gray-500 py-4">{{__('Add tasks with start and end dates to see the Gantt chart.')}}</p>
                    @endif
                </div>
            @endif

            @if ($activeTab === 'documents')
                {{-- Panel Dokumen Proyek --}}
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('Project Documents') }}</h3>
                    <form wire:submit.prevent="uploadDocument" class="mb-6 p-4 border border-dashed border-gray-300 rounded-lg bg-gray-50">
                        <label for="document" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Upload New Document (Max 10MB)') }}</label>
                        <input type="file" wire:model="document" id="document"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0 file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('document') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                        <button type="submit"
                            class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                            wire:loading.attr="disabled" wire:target="document">
                            <span wire:loading.remove wire:target="document">{{ __('Upload Document') }}</span>
                            <span wire:loading wire:target="document">{{ __('Uploading...') }}</span>
                        </button>
                    </form>

                    @if ($project->documents->isEmpty())
                        <p class="text-gray-600 italic">{{ __('No documents uploaded for this project yet.') }}</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($project->documents as $doc)
                                <div class="flex items-center justify-between p-3 bg-white border rounded-md hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        @if(Str::contains($doc->type, 'pdf'))
                                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        @else
                                            <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        @endif
                                        <div>
                                            <a href="{{ Storage::disk('public')->url($doc->path) }}" target="_blank" class="font-semibold text-gray-800 hover:underline">{{ $doc->name }}</a>
                                            <p class="text-xs text-gray-500">{{ __('Uploaded by') }} {{ $doc->uploadedBy->name }} {{ __('on') }} {{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <button wire:click="deleteDocument({{ $doc->id }})" wire:confirm="{{__('Are you sure?')}}" class="text-red-600 hover:text-red-900">{{__('Delete')}}</button>
                                </td>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            @if ($activeTab === 'expenses')
                {{-- Panel Pengeluaran Lain-lain --}}
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">{{ __('Other Expenses') }}</h2>
                        <button wire:click="openExpenseModal" class="bg-indigo-600 text-white font-bold px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                            + {{ __('Add Expense') }}
                        </button>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $expense->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rp {{ number_format($expense->amount, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $expense->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button wire:click="deleteExpense({{ $expense->id }})" wire:confirm="{{__('Are you sure?')}}" class="text-red-600 hover:text-red-900">{{__('Delete')}}</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-600 italic">{{__('No other expense data available.')}}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

                        {{-- Bagian ini adalah kelanjutan dari konten tab, fokus pada tab RAB --}}
            @if ($activeTab === 'rab')
                {{-- Panel RAB (Rencana Anggaran Biaya) --}}
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('RAB (Cost Budget Plan)') }}</h3>

                    <div class="mb-4 flex flex-wrap gap-2">
                        <button wire:click="addRabItem('PEKERJAAN PERSIAPAN')" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 text-sm">{{ __('Add Preparation Work') }}</button>
                        <button wire:click="addRabItem('PEKERJAAN STRUKTUR')" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 text-sm">{{ __('Add Structure Work') }}</button>
                        <button wire:click="addRabItem('PEKERJAAN ARSITEKTUR')" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 text-sm">{{ __('Add Architecture Work') }}</button>
                        <button wire:click="addRabItem('PEKERJAAN UTILITAS')" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 text-sm">{{ __('Add Utility Work') }}</button>
                        <button wire:click="addRabItem('LAIN-LAIN')" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 text-sm">{{ __('Add Other Work') }}</button>
                    </div>

                    <form wire:submit.prevent="saveRab">
                        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm mb-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('AHS') }}</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Description') }}</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">{{ __('Qty') }}</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">{{ __('Unit') }}</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">{{ __('Unit Price') }}</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">{{ __('Total Price') }}</th>
                                        <th scope="col" class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    {{-- Group items by category for display and subtotaling --}}
                                    @php
                                        $currentCategory = null;
                                        $categorySubtotal = 0;
                                    @endphp

                                    @forelse ($rabItems as $originalIndex => $item)
                                        @if ($item['category'] !== $currentCategory)
                                            @if ($currentCategory !== null)
                                                {{-- Display subtotal for previous category --}}
                                                <tr class="bg-gray-50">
                                                    <td colspan="6" class="px-3 py-2 text-right text-sm font-semibold text-gray-700">
                                                        {{ __('Subtotal') }} {{ __($currentCategory) }}:
                                                    </td>
                                                    <td colspan="2" class="px-3 py-2 text-left text-sm font-bold text-gray-800">
                                                        Rp {{ number_format($categorySubtotal, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endif
                                            {{-- Display new category header --}}
                                            <tr class="bg-gray-100">
                                                <td colspan="8" class="px-3 py-2 text-left text-sm font-semibold text-gray-800">
                                                    {{ __($item['category']) }}
                                                </td>
                                            </tr>
                                            @php
                                                $currentCategory = $item['category'];
                                                $categorySubtotal = 0;
                                            @endphp
                                        @endif

                                        <tr wire:key="rab-item-{{ $item['id'] ?? $originalIndex }}"> {{-- Gunakan item ID jika ada, jika tidak, gunakan originalIndex untuk wire:key --}}
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{-- Kategori tetap dipilih dari dropdown --}}
                                                <select wire:model.live="rabItems.{{ $originalIndex }}.category"
                                                    class="block w-full border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs">
                                                    <option value="PEKERJAAN PERSIAPAN">{{ __('Preparation Work') }}</option>
                                                    <option value="PEKERJAAN STRUKTUR">{{ __('Structure Work') }}</option>
                                                    <option value="PEKERJAAN ARSITEKTUR">{{ __('Architecture Work') }}</option>
                                                    <option value="PEKERJAAN UTILITAS">{{ __('Utility Work') }}</option>
                                                    <option value="LAIN-LAIN">{{ __('Other Work') }}</option>
                                                </select>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <select wire:model.live="rabItems.{{ $originalIndex }}.analysis_id"
                                                    class="block w-full border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs">
                                                    <option value="">{{ __('Select Analysis (Optional)') }}</option>
                                                    @foreach ($analysisList as $analysis)
                                                        <option value="{{ $analysis->id }}">{{ $analysis->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <textarea wire:model.live="rabItems.{{ $originalIndex }}.description" rows="2"
                                                    class="block w-full border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs"></textarea>
                                                @error("rabItems.{$originalIndex}.description") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <input type="number" step="0.01" wire:model.live="rabItems.{{ $originalIndex }}.quantity"
                                                    class="block w-full border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs">
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <input type="text" wire:model.live="rabItems.{{ $originalIndex }}.unit"
                                                    class="block w-full border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs">
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <input type="number" step="0.01" wire:model.live="rabItems.{{ $originalIndex }}.unit_price"
                                                    class="block w-full border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs">
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($item['total_price'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <button type="button" wire:click="removeRabItem({{ $originalIndex }})" class="text-red-600 hover:text-red-900 text-xs">{{ __('Remove') }}</button>
                                            </td>
                                        </tr>
                                        @php
                                            $categorySubtotal += $item['total_price'];
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-gray-600 italic">{{ __('No RAB items added yet. Click \'Add Work\' to start.') }}</td>
                                        </tr>
                                    @endforelse

                                    {{-- Display the last category's subtotal --}}
                                    @if ($currentCategory !== null)
                                        <tr class="bg-gray-50">
                                            <td colspan="6" class="px-3 py-2 text-right text-sm font-semibold text-gray-700">
                                                {{ __('Subtotal') }} {{ __($currentCategory) }}:
                                            </td>
                                            <td colspan="2" class="px-3 py-2 text-left text-sm font-bold text-gray-800">
                                                Rp {{ number_format($categorySubtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="px-6 py-3 text-right text-base font-semibold text-gray-800 bg-gray-50 rounded-bl-lg">{{ __('Total RAB') }}:</td>
                                        <td colspan="2" class="px-6 py-3 text-left text-base font-bold text-gray-800 bg-gray-50 rounded-br-lg">
                                            Rp {{ number_format($totalRabAmount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                {{ __('Save RAB') }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif
            {{-- Akhir dari bagian tab RAB --}}

        </div> {{-- Penutup div untuk konten tab --}}
    </div> {{-- Penutup div untuk bg-white rounded-xl shadow-lg --}}

    {{-- Modal Form Tim (dipindahkan ke sini agar selalu tersedia di luar tab content) --}}
    @if ($isTeamModalOpen)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">{{ __('Assign Foreman to Project') }}</h3>
                    <button wire:click="closeTeamModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form wire:submit.prevent="assignMandor" class="mt-4">
                    <div class="mb-4">
                        <label for="team_employee_id" class="block text-sm font-medium text-gray-700">{{__('Select Foreman')}}</label>
                        <select wire:model="team_employee_id" id="team_employee_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{__('Select an Employee')}}</option>
                            @foreach ($mandorList as $mandor)
                                <option value="{{ $mandor->id }}">{{ $mandor->user->name ?? $mandor->employee_id_number }}</option>
                            @endforeach
                        </select>
                        @error('team_employee_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="team_payment_type" class="block text-sm font-medium text-gray-700">{{__('Payment Type')}}</label>
                        <div class="mt-2 space-x-4">
                            <label class="inline-flex items-center"><input type="radio" wire:model.live="team_payment_type" value="harian" class="form-radio text-indigo-600"> <span class="ml-2">{{__('Daily')}}</span></label>
                            <label class="inline-flex items-center"><input type="radio" wire:model.live="team_payment_type" value="borongan" class="form-radio text-indigo-600"> <span class="ml-2">{{__('Lump Sum')}}</span></label>
                        </div>
                        @error('team_payment_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    @if ($team_payment_type === 'harian')
                        <div class="mb-4">
                            <label for="team_number_of_workers" class="block text-sm font-medium text-gray-700">{{__('Number of Workers')}}</label>
                            <input type="number" wire:model="team_number_of_workers" id="team_number_of_workers"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('team_number_of_workers') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="team_worker_daily_wage" class="block text-sm font-medium text-gray-700">{{__('Worker Daily Wage')}} (Rp)</label>
                            <input type="number" wire:model="team_worker_daily_wage" id="team_worker_daily_wage"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('team_worker_daily_wage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div class="mb-4">
                            <label for="team_lump_sum_value" class="block text-sm font-medium text-gray-700">{{__('Lump Sum Value')}} (Rp)</label>
                            <input type="number" wire:model="team_lump_sum_value" id="team_lump_sum_value"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('team_lump_sum_value') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="team_work_progress" class="block text-sm font-medium text-gray-700">{{__('Work Progress')}} (%)</label>
                            <input type="number" wire:model="team_work_progress" id="team_work_progress"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0" max="100">
                            @error('team_work_progress') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="closeTeamModal"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            {{__('Cancel')}}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            {{__('Assign')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Form Edit Tim --}}
    @if ($isTeamEditModalOpen && $editingTeam)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">{{ __('Edit Team Assignment') }} for {{ $editingTeam->employee->user->name ?? 'N/A' }}</h3>
                    <button wire:click="closeTeamEditModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form wire:submit.prevent="updateTeam" class="mt-4">
                    <div class="mb-4">
                        <label for="editingTeam_payment_type" class="block text-sm font-medium text-gray-700">{{__('Payment Type')}}</label>
                        <div class="mt-2 space-x-4">
                            <label class="inline-flex items-center"><input type="radio" wire:model.live="editingTeam.payment_type" value="harian" class="form-radio text-indigo-600"> <span class="ml-2">{{__('Daily')}}</span></label>
                            <label class="inline-flex items-center"><input type="radio" wire:model.live="editingTeam.payment_type" value="borongan" class="form-radio text-indigo-600"> <span class="ml-2">{{__('Lump Sum')}}</span></label>
                        </div>
                        @error('editingTeam.payment_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    @if ($editingTeam->payment_type === 'harian')
                        <div class="mb-4">
                            <label for="editingTeam_number_of_workers" class="block text-sm font-medium text-gray-700">{{__('Number of Workers')}}</label>
                            <input type="number" wire:model="editingTeam.number_of_workers" id="editingTeam_number_of_workers"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('editingTeam.number_of_workers') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="editingTeam_worker_daily_wage" class="block text-sm font-medium text-gray-700">{{__('Worker Daily Wage')}} (Rp)</label>
                            <input type="number" wire:model="editingTeam.worker_daily_wage" id="editingTeam_worker_daily_wage"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('editingTeam.worker_daily_wage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div class="mb-4">
                            <label for="editingTeam_lump_sum_value" class="block text-sm font-medium text-gray-700">{{__('Lump Sum Value')}} (Rp)</label>
                            <input type="number" wire:model="editingTeam.lump_sum_value" id="editingTeam_lump_sum_value"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('editingTeam.lump_sum_value') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="editingTeam_work_progress" class="block text-sm font-medium text-gray-700">{{__('Work Progress')}} (%)</label>
                            <input type="number" wire:model="editingTeam.work_progress" id="editingTeam_work_progress"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0" max="100">
                            @error('editingTeam.work_progress') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="closeTeamEditModal"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            {{__('Cancel')}}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            {{__('Update')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Form Tugas --}}
    @if ($isTaskModalOpen)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">{{ $taskId ? __('Edit Task') : __('Add New Task') }}</h3>
                    <button wire:click="closeTaskModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form wire:submit.prevent="storeTask" class="mt-4">
                    <div class="mb-4">
                        <label for="task_name" class="block text-sm font-medium text-gray-700">{{ __('Task Name') }}</label>
                        <input type="text" wire:model="task_name" id="task_name"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('task_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="task_description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                        <textarea wire:model="task_description" id="task_description" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        @error('task_description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="task_start_date" class="block text-sm font-medium text-gray-700">{{ __('Start Date') }}</label>
                            <input type="date" wire:model="task_start_date" id="task_start_date"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('task_start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="task_end_date" class="block text-sm font-medium text-gray-700">{{ __('End Date') }}</label>
                            <input type="date" wire:model="task_end_date" id="task_end_date"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('task_end_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="task_progress" class="block text-sm font-medium text-gray-700">{{ __('Progress') }} (%)</label>
                        <input type="range" wire:model="task_progress" id="task_progress" min="0" max="100"
                            class="mt-1 block w-full">
                        <p class="text-center font-semibold">{{ $task_progress ?? 0 }}%</p>
                        @error('task_progress') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="closeTaskModal"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            {{__('Cancel')}}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            {{ $taskId ? __('Update Task') : __('Create Task') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Form Pengeluaran Lain --}}
    @if ($isExpenseModalOpen)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">{{ __('Add Other Expense') }}</h3>
                    <button wire:click="closeExpenseModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form wire:submit.prevent="storeExpense" class="mt-4">
                    <div class="mb-4">
                        <label for="expense_description" class="block text-sm font-medium text-gray-700">{{__('Description')}}</label>
                        <textarea wire:model="expense_description" id="expense_description" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        @error('expense_description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="expense_amount" class="block text-sm font-medium text-gray-700">{{__('Amount')}} (Rp)</label>
                        <input type="number" wire:model="expense_amount" id="expense_amount"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('expense_amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="closeExpenseModal"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            {{__('Cancel')}}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            {{__('Add Expense')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Frappe Gantt CSS and JS --}}
    <link rel="stylesheet" href="https://unpkg.com/frappe-gantt@0.6.0/dist/frappe-gantt.min.css" />
    <script src="https://unpkg.com/frappe-gantt@0.6.0/dist/frappe-gantt.min.js"></script>

    @script
    <script>
        document.addEventListener('livewire:navigated', () => {
            const tasks = @json($ganttTasks);
            const ganttEl = document.querySelector("#gantt");

            if (ganttEl) {
                // Hancurkan instance Gantt sebelumnya jika ada
                if (window.ganttChartInstance) {
                    window.ganttChartInstance.destroy();
                }
                ganttEl.innerHTML = ''; // Bersihkan elemen SVG sebelumnya

                console.log("GANTT TASKS (Livewire):", tasks); // Log data dari Livewire

                if (tasks && tasks.length > 0) {
                    try {
                        const formattedTasks = tasks.map(t => {
                            let progress = parseInt(t.progress);
                            progress = isNaN(progress) ? 0 : progress; // Pastikan progres adalah angka
                            return {
                                id: t.id.toString(),
                                name: t.name,
                                start: new Date(t.start).toISOString().split('T')[0], // Pastikan format tanggal YYYY-MM-DD
                                end: new Date(t.end).toISOString().split('T')[0],     // Pastikan format tanggal YYYY-MM-DD
                                progress: progress,
                                dependencies: t.dependencies || '' // Tambahkan dependensi jika ada
                            };
                        });
                        console.log("GANTT TASKS (Formatted for Frappe):", formattedTasks); // Log data setelah diformat

                        // Inisialisasi Gantt chart
                        window.ganttChartInstance = new Gantt("#gantt", formattedTasks, { // Gunakan formattedTasks
                            on_click: (task) => {
                                @this.call('editTask', task.id);
                            },
                            bar_height: 20,
                            padding: 18,
                            view_mode: 'Day' // Set default view mode
                            // Hapus opsi 'language' agar tetap dalam bahasa Inggris (default Frappe Gantt)
                        });

                    } catch(e) {
                        console.error("Error creating Gantt instance: ", e);
                    }
                }
            }
        });
    </script>
    @endscript
</div>
    </div>