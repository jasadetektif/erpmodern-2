<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">{{ __('Project Documents') }}</h2>

    <form wire:submit.prevent="uploadDocument" class="mb-6 p-4 border rounded-lg bg-gray-50">
        <div class="flex items-center space-x-4">
            <input type="file" wire:model="document" class="flex-grow block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
            <button type="submit" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-md hover:bg-emerald-700 disabled:opacity-50" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('Upload') }}</span>
                <span wire:loading>{{ __('Uploading...') }}</span>
            </button>
        </div>
        @error('document') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror
    </form>

    <div class="space-y-3">
        @forelse ($project->documents as $doc)
            <div class="flex items-center justify-between p-3 bg-white border rounded-md hover:bg-gray-50">
                <div class="flex items-center space-x-3">
                    @if(Str::contains($doc->type, 'pdf'))
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    @else
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    @endif
                    <div>
                        <a href="{{ asset('storage/' . $doc->path) }}" target="_blank" class="font-semibold text-gray-800 hover:underline">{{ $doc->name }}</a>
                        <p class="text-xs text-gray-500">
                            {{ __('Uploaded by') }} {{ $doc->uploadedBy->name }} {{ __('on') }} {{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y') }}
                        </p>
                    </div>
                </div>
                <button wire:click="deleteDocument({{ $doc->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-500 hover:text-red-700 text-sm font-semibold">{{ __('Delete') }}</button>
            </div>
        @empty
            <p class="text-center text-gray-500 py-4">{{ __('No documents have been uploaded for this project.') }}</p>
        @endforelse
    </div>
</div>
