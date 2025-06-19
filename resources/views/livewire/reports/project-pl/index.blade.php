<div>
    <x-slot name="header">
        {{ __('Project Profit & Loss') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">{{ __('Project P&L Report') }}</h2>
                <button class="bg-gray-200 text-gray-800 font-bold px-4 py-2 rounded-md hover:bg-gray-300">
                    {{ __('Export') }}
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Project Name') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Contract Value') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actual Revenue') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Total Expenses') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actual P&L') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($projects as $project)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                    <a href="{{ route('reports.project-pl.show', $project->id) }}" class="text-emerald-600 hover:underline">
                                        {{ $project->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    Rp {{ number_format($project->contract_value, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-blue-600">
                                    Rp {{ number_format($project->actual_revenue, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    Rp {{ number_format($project->total_expenses, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold {{ $project->profit_loss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($project->profit_loss, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    {{ __('No data available') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
