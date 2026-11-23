{{-- One block per filter, with a visible label when the variant has one. Expects: $data, $style, $texts, $views. --}}
@foreach ($data['filters'] as $filter)
    <div class="{{ $style['filter'] }}">
        @if ($style['label'] !== null)
            <span class="{{ $style['label'] }}">{{ $filter['label'] ?? $filter['key'] }}</span>
        @endif
        @include($views['SELECT'], compact('filter', 'style', 'texts'))
    </div>
@endforeach