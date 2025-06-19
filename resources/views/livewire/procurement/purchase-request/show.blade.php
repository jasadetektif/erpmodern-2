<div>
    <x-slot name="header">
        {{ __('Purchase Request Detail') }}: {{ $purchaseRequest->pr_number }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            
            {{-- Tombol Aksi --}}
            <div class="flex justify-end space-x-2 mb-6">
                <button class="bg-gray-200 text-gray-800 font-bold px-4 py-2 rounded-md hover:bg-gray-300">{{ __('Print') }}</button>
                <button class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700">{{ __('Create PO') }}</button>
            </div>

            {{-- Header Dokumen --}}
            <div class="border-b pb-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Purchase Request') }}</h2>
                <div class="grid grid-cols-2 mt-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Project') }}</p>
                        <p class="font-semibold">{{ $purchaseRequest->project->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('PR Number') }}</p>
                        <p class="font-semibold">{{ $purchaseRequest->pr_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Requester') }}</p>
                        <p class="font-semibold">{{ $purchaseRequest->requester->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Request Date') }}</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($purchaseRequest->request_date)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Tabel Item --}}
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Item Name') }}</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">{{ __('Quantity') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Unit') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Description') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($purchaseRequest->items as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->item_name }}</td>
                                <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                                <td class="px-4 py-3">{{ $item->unit }}</td>
                                <td class="px-4 py-3">{{ $item->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Catatan & Status --}}
            <div class="mt-6 border-t pt-4">
                <div class="grid grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Notes') }}</p>
                        <p>{{ $purchaseRequest->notes ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                        <p class="font-bold text-lg">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($purchaseRequest->status == 'Disetujui') bg-green-100 text-green-800
                            @elseif($purchaseRequest->status == 'Ditolak') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800 @endif">
                                {{ __($purchaseRequest->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
