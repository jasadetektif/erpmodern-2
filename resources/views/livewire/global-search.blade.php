<div
    x-data="{ show: @entangle('show') }"
    x-show="show"
    x-on:keydown.window.prevent.cmd.k="show = true"
    x-on:keydown.window.prevent.ctrl.k="show = true"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto"
>
    <!-- Overlay -->
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>

    <!-- Modal -->
    <div x-show="show" x-transition class="relative bg-gray-100 rounded-lg max-w-xl mx-auto my-12 p-4">
        {{-- Input Pencarian --}}
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                class="w-full pl-10 pr-4 py-3 border-gray-300 rounded-md shadow-sm"
                placeholder="{{__('Search anything (Projects, PR, PO, Clients, Employees, Assets)...')}}"
                x-ref="searchInput"
                x-init="$watch('show', value => { if (value) { setTimeout(() => $refs.searchInput.focus(), 100) } })"
            >
            <button @click="show = false" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        {{-- Hasil Pencarian --}}
        <div wire:loading class="text-center py-4 text-gray-500">{{__('Searching...')}}</div>

        @if(!empty($search))
            <div wire:loading.remove class="mt-4 max-h-96 overflow-y-auto">
                @forelse($results as $type => $models)
                    @if(!$models->isEmpty())
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mt-4 mb-2">{{ __(ucfirst($type)) }}</h3>
                        <ul class="bg-white rounded-md shadow-sm divide-y">
                            @foreach($models as $model)
                                <li>
                                    <a href="{{ $model->link ?? '#' }}" class="block p-3 hover:bg-gray-50">
                                        <p class="font-semibold text-gray-800">{{ $model->title ?? $model->name ?? $model->client_name ?? $model->quotation_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $model->subtitle ?? $model->email ?? $model->project->name ?? '' }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @empty
                    <p class="text-center py-4 text-gray-500">{{__('No results found for')}} "{{$search}}"</p>
                @endforelse
            </div>
        @endif
    </div>
</div>
