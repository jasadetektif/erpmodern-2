<div>
    <x-slot name="header">
        {{ __('Inventory Management') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-6">{{ __('Stock List') }}</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Project') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Item Name') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Stock Quantity') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Unit') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($inventoryItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->project->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ $item->item_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-lg">{{ $item->stock_quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->unit }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">{{ __('No data available') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
