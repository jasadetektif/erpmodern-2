<div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">{{ __('Other Expenses') }}</h2>
        <button wire:click="openExpenseModal" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 transition-colors">
            + {{ __('Add Expense') }}
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Description') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Recorded By') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($project->otherExpenses as $expense)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">Rp {{ number_format($expense->amount, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="deleteExpense({{ $expense->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            {{ __('No other expense data available.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
