<div>
    <x-slot name="header">
        {{ $analysisId ? __('Edit Analysis') : __('Create Analysis') }}
    </x-slot>

    <div class="py-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form wire:submit.prevent="save">
                {{-- Form Info Utama AHS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Analysis Name') }}</label>
                        <input type="text" wire:model="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: 1 m3 Pekerjaan Beton K-225">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700">{{ __('Unit') }}</label>
                        <input type="text" wire:model="unit" id="unit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: m3, m2, ls">
                        @error('unit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Bagian Tenaga Kerja --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Labor Component') }}</h3>
                    <div class="space-y-3">
                        @foreach($items as $index => $item)
                            @if($item['type'] === 'labor')
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-md" wire:key="item-labor-{{ $index }}">
                                <div class="grid grid-cols-12 gap-3 flex-1">
                                    <div class="col-span-12 md:col-span-5">
                                        <select wire:model.live="items.{{$index}}.id" class="w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">{{__('Select Labor')}}</option>
                                            @foreach($allLabors as $labor)
                                                <option value="{{$labor->id}}">{{$labor->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-6 md:col-span-2">
                                        <input type="number" step="any" wire:model.live="items.{{$index}}.coefficient" placeholder="{{__('Coefficient')}}" class="w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div class="col-span-6 md:col-span-2">
                                        <input type="text" value="{{ $this->getUnitPrice($item['type'], $item['id']) }}" readonly class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-right">
                                    </div>
                                    <div class="col-span-12 md:col-span-3">
                                        <input type="text" value="Rp {{ number_format($this->calculateSubtotal($item), 2, ',', '.') }}" readonly class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-right font-semibold">
                                    </div>
                                </div>
                                <button wire:click.prevent="removeItem({{$index}})" class="text-red-500 hover:text-red-700 p-2">&times;</button>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <button wire:click.prevent="addLabor" class="mt-4 text-sm font-medium text-emerald-600 hover:text-emerald-800">+ {{ __('Add Labor') }}</button>
                </div>

                {{-- Bagian Material --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Material Component') }}</h3>
                    <div class="space-y-3">
                         @foreach($items as $index => $item)
                            @if($item['type'] === 'material')
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-md" wire:key="item-material-{{ $index }}">
                                <div class="grid grid-cols-12 gap-3 flex-1">
                                    <div class="col-span-12 md:col-span-5">
                                        <select wire:model.live="items.{{$index}}.id" class="w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">{{__('Select Material')}}</option>
                                            @foreach($allMaterials as $material)
                                                <option value="{{$material->id}}">{{$material->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-6 md:col-span-2">
                                        <input type="number" step="any" wire:model.live="items.{{$index}}.coefficient" placeholder="{{__('Coefficient')}}" class="w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div class="col-span-6 md:col-span-2">
                                        <input type="text" value="{{ $this->getUnitPrice($item['type'], $item['id']) }}" readonly class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-right">
                                    </div>
                                    <div class="col-span-12 md:col-span-3">
                                        <input type="text" value="Rp {{ number_format($this->calculateSubtotal($item), 2, ',', '.') }}" readonly class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-right font-semibold">
                                    </div>
                                </div>
                                <button wire:click.prevent="removeItem({{$index}})" class="text-red-500 hover:text-red-700 p-2">&times;</button>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <button wire:click.prevent="addMaterial" class="mt-4 text-sm font-medium text-emerald-600 hover:text-emerald-800">+ {{ __('Add Material') }}</button>
                </div>

                {{-- Total Biaya --}}
                <div class="mt-8 pt-4 border-t text-right">
                    <span class="text-sm font-medium text-gray-500">{{__('Total Unit Price')}}:</span>
                    <span class="font-bold text-2xl text-gray-900 ml-2">Rp {{ number_format($total_cost, 2, ',', '.') }}</span>
                </div>

                {{-- Tombol Simpan --}}
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('master.ahs.index') }}" class="bg-gray-200 text-gray-800 font-bold px-6 py-2 rounded-md hover:bg-gray-300 mr-4">{{__('Back')}}</a>
                    <button type="submit" class="bg-emerald-600 text-white font-bold px-6 py-2 rounded-md hover:bg-emerald-700">{{__('Save Analysis')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
