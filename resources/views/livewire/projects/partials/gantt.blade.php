<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">{{ __('Project Timeline (Gantt Chart)') }}</h2>
    <div class="min-h-[300px]" wire:ignore>
        <svg id="gantt"></svg>
    </div>

    @if (empty($ganttTasks))
        <p class="text-center text-gray-500 py-4">
            {{ __('Add tasks with start and end dates to see the Gantt chart.') }}
        </p>
    @endif
</div>
