<div
    id="{{ $record->getKey() }}"
    wire:click="recordClicked('{{ $record->getKey() }}', {{ @json_encode($record) }})"
    class="record bg-white dark:bg-gray-700 rounded-lg px-4 py-2 cursor-grab font-medium text-gray-600 dark:text-gray-200"
    @if($record->timestamps && now()->diffInSeconds($record->{$record::UPDATED_AT}) < 3)
        x-data
        x-init="
            $el.classList.add('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
            $el.classList.remove('bg-white', 'dark:bg-gray-700')
            setTimeout(() => {
                $el.classList.remove('bg-primary-100', 'dark:bg-primary-800')
                $el.classList.add('bg-white', 'dark:bg-gray-700')
            }, 3000)
        "
    @endif
>
    {{--<p>{{ $record->{static::$recordTitleAttribute} }}</p>--}}
    <p>Pet: {{ $record->pet()->first()->name }}</p>
    <p>Doctor: {{ $record->doctor()->first()->username }}</p>
    <p>Date: {{ $record->date ? Carbon\Carbon::parse($record->date)->format('d/M/Y') : 'N/A' }}</p>
    <p>Start Time: {{ $record->start_time ? Carbon\Carbon::parse($record->start_time)->format('h:i A') : 'N/A' }}</p>
    <p>Start Time: {{ $record->end_time ? Carbon\Carbon::parse($record->end_time)->format('h:i A') : 'N/A' }}</p>
</div>
