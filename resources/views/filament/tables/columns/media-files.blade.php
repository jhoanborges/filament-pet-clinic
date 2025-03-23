{{-- Save as resources/views/filament/tables/columns/media-files.blade.php --}}
<div class="flex flex-wrap gap-2">
    @php
        $mediaFiles = $getState();
        // Ensure $mediaFiles is always an array
        $mediaFiles = is_array($mediaFiles) ? $mediaFiles : [];
    @endphp

    @if(count($mediaFiles) > 0)
        @foreach($mediaFiles as $media)
            <div class="flex flex-col items-center gap-1">
                <a href="{{ $media['url'] }}" target="_blank" class="group relative" title="{{ $media['name'] }} ({{ $media['size'] }})">
                    @if(Str::startsWith($media['mime_type'], 'image/'))
                        <div class="h-10 w-10 rounded-full overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                            <img src="{{ $media['url'] }}" alt="{{ $media['name'] }}" class="w-full h-full object-cover" />
                        </div>
                    @else
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm text-gray-500 dark:text-gray-400">
                            @switch(strtolower($media['extension']))
                                @case('pdf')
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                    </svg>
                                    @break
                                @case('doc')
                                @case('docx')
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 3a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm0 3a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm0 3a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    @break
                                @case('xls')
                                @case('xlsx')
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm0 2a1 1 0 00-1 1v6a1 1 0 001 1h10a1 1 0 001-1V7a1 1 0 00-1-1H5z" clip-rule="evenodd" />
                                    </svg>
                                    @break
                                @case('mp3')
                                @case('wav')
                                @case('ogg')
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z" clip-rule="evenodd" />
                                    </svg>
                                    @break
                                @case('mp4')
                                @case('mov')
                                @case('avi')
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                    </svg>
                                    @break
                                @default
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                            @endswitch
                        </div>
                    @endif

                    <div class="absolute inset-0 rounded-full bg-gray-900 bg-opacity-0 group-hover:bg-opacity-25 transition-opacity"></div>
                </a>

                <span class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[64px]">
                    {{ Str::limit($media['name'], 10) }}
                </span>
            </div>
        @endforeach
    @else
        <span class="text-xs text-gray-500 dark:text-gray-400">No files</span>
    @endif
</div>
