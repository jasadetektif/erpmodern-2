<div>
    <x-slot name="header">{{ __('Unit Price Analysis') }}</x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <!-- Header dan tombol tambah -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Unit Price Analysis List') }}</h2>
                <a href="{{ route('master.ahs.form') }}"
                   class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
                    + {{ __('Add New Analysis') }}
                </a>
            </div>

            <!-- Flash Message -->
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Tabel AHS -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Analysis Name') }}</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Unit') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total Unit Price') }}</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Items') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($analyses as $analysis)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $analysis->name }}</td>
                                <td class="px-6 py-4 text-center text-gray-700">{{ $analysis->unit }}</td>
                                <td class="px-6 py-4 text-right text-gray-700">Rp {{ number_format($analysis->total_cost, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center text-gray-700">{{ $analysis->items_count }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('master.ahs.form', $analysis->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900 font-medium">
                                       ✏️ {{ __('Edit') }}
                                    </a>
                                    <button
                                        x-data
                                        @click="if(confirm('{{ __('Are you sure you want to delete this analysis?') }}')) { $wire.delete({{ $analysis->id }}) }"
                                        class="text-red-600 hover:text-red-800 font-medium"
                                    >
                                        🗑️ {{ __('Delete') }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    {{ __('No data available.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
