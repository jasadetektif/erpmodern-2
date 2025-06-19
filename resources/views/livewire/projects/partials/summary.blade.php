<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">{{ __('Financial Summary') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
        <div class="col-span-1 md:col-span-2 lg:col-span-4 grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-4 mt-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('Total Expenses') }}</h3>
                <p class="mt-1 text-xl font-bold text-red-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('Actual Profit / Loss') }}</h3>
                <p class="mt-1 text-2xl font-bold {{ $profitOrLoss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    Rp {{ number_format($profitOrLoss, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</div>
