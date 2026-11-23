@php

    use Juksgraphic\BladeComponents\Components\Overlays\Modal;
    use Juksgraphic\BladeComponents\Components\Overlays\ModalStyles;

   
    $modal = new Modal([
        'id' => $id ?? null,
        'size' => $size ?? null,
        'position' => $position ?? null,
        'centered' => $centered ?? false,
        'static' => $static ?? false,
        'scrollable' => $scrollable ?? false,
        'animation' => $animation ?? 'fade',
        'showClose' => $showClose ?? true,
        'title' => $title ?? 'Modal Title',
        'class' => $class ?? '',
        'attrs' => $attrs ?? '',
    ]);

   
    $styles = new ModalStyles($modal);

    $_hasHeader = isset($header);
    $_hasBody   = isset($body);
    $_hasFooter = isset($footer);
@endphp

<!-- Root modal backdrop and Alpine.js visibility controller -->
<div x-cloak x-data="{
    open: false,


    setOpen(value) {
        if (this.open === value) return;
        this.open = value;
        if (value) {
            this.$dispatch('jkc-on-open-modal', { id: '{{ $modal->id() }}' });
        } else {
            this.$dispatch('jkc-on-close-modal', { id: '{{ $modal->id() }}' });
        }
    }
}" x-show="open"
    x-transition:enter="transition ease-out duration-250 motion-reduce:transition-opacity"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-out duration-200 motion-reduce:transition-opacity"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    @jkc-open-modal.window="if ($event.detail === '{{ $modal->id() }}') setOpen(true)"
    @jkc-close-modal.window="if ($event.detail === '{{ $modal->id() }}') setOpen(false)"
    class="{{ $styles->backdrop() }}" style="display: none;"
    @if (!$modal->isStatic()) @click.self="setOpen(false)" @endif {!! $modal->attrs() !!} tabindex="-1"
    aria-modal="true" role="dialog">

    <!-- Animated dialog surface -->
    <div x-cloak x-show="open"
        x-transition:enter="transform transition ease-out duration-250 motion-reduce:transition-opacity"
        x-transition:enter-start="{{ $modal->dialogEnterStart() }}"
        x-transition:enter-end="{{ $modal->dialogEnterEnd() }}"
        x-transition:leave="transform transition ease-out duration-200 motion-reduce:transition-opacity"
        x-transition:leave-start="{{ $modal->dialogEnterEnd() }}"
        x-transition:leave-end="{{ $modal->dialogLeaveEnd() }}" class="{{ $styles->dialog() }}" @click.stop>

        <!-- Modal header bar -->
        <div class="{{ $styles->header() }}">
            @if ($_hasHeader)
                {!! $header !!}
            @else
                <h5 class="{{ $styles->title() }}">
                    {{ $modal->title() }}
                </h5>
            @endif

            @if ($modal->showsClose())
                <button type="button" @click="setOpen(false)" aria-label="Close modal"
                    class="{{ $styles->closeButton() }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>

        <!-- Modal body slot -->
        <div class="{{ $styles->body() }}">
            @if ($_hasBody)
                {!! $body !!}
            @endif
        </div>

        <!-- Modal footer slot or default actions -->
        <div class="{{ $styles->footer() }}">
            @if ($_hasFooter)
                {!! $footer !!}
            @else
                <button type="button" x-on:click="setOpen(false)" class="{{ $styles->secondaryButton() }}">
                    Close
                </button>
                <button type="button" x-on:click="setOpen(false)" class="{{ $styles->primaryButton() }}">
                    Save changes
                </button>
            @endif
        </div>

    </div>

</div>
