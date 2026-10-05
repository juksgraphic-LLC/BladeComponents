@php
    $type = $type ?? 'primary';
@endphp
<div role="alert"
    class="p-4 mb-3 text-sm rounded-radius border border-{{ $type }}/20 bg-{{ $type }}/10 text-{{ $type }}">
    {!! $message ?? '' !!}
</div>
