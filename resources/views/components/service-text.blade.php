@if ($expandable)
    <div {{ $attributes->class(['text-expandable']) }} x-data="{ expanded: false }">
        @if ($hasMore)
            <div x-show="!expanded" class="font-medium text-white break-words text-truncated">{{ $summary }}</div>
            <div x-show="expanded" x-cloak class="font-medium text-white break-words text-truncated">{!! $html !!}</div>
            <button type="button" @click="expanded = !expanded"
                class="text-blue-400 hover:text-blue-300 text-xs mt-1 transition-colors expand-button"
                :aria-expanded="expanded" x-text="expanded ? 'Ver menos' : 'Ver más'"></button>
        @else
            <div class="font-medium text-white break-words text-truncated">{!! $html !!}</div>
        @endif
    </div>
@else
    <span {{ $attributes }}>{!! $html !!}</span>
@endif
