@php
    
    use Juksgraphic\BladeComponents\Components\Overlays\Offcanvas;
    use Juksgraphic\BladeComponents\Components\Overlays\OffcanvasStyles;

    
    $offcanvas = new Offcanvas([
        'id' => $id ?? null,
        'placement' => $placement ?? 'start',
        'size' => $size ?? null,
        'backdrop' => $backdrop ?? true,
        'scroll' => $scroll ?? false,
        'title' => $title ?? 'Offcanvas',
        'showClose' => $showClose ?? true,
        'class' => $class ?? '',
        'attrs' => $attrs ?? '',
    ]);
    
    $styles = new OffcanvasStyles($offcanvas);

    $_hasBody = isset($body) && trim((string) $body) !== '';
    $_hasFooter = isset($footer) && trim((string) $footer) !== '';
@endphp

<!-- Root offcanvas container with Alpine.js event & visibility controller -->
<div x-cloak x-data="{
    open: false,

    setOpen(value) {
        if (this.open === value) return;
        this.open = value;
        if (value) {
            this.$dispatch('jkc-on-open-offcanvas', { id: '{{ $offcanvas->id() }}' });
        } else {
            this.$dispatch('jkc-on-close-offcanvas', { id: '{{ $offcanvas->id() }}' });
        }
    }
}" x-show="open"
    x-transition:enter="transition ease-out duration-250 motion-reduce:transition-opacity"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-out duration-200 motion-reduce:transition-opacity"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    @jkc-open-offcanvas.window="if ($event.detail === '{{ $offcanvas->id() }}') setOpen(true)"
    @jkc-close-modal.window="if ($event.detail === '{{ $offcanvas->id() }}') setOpen(false)"
    @jkc-close-offcanvas.window="if ($event.detail === '{{ $offcanvas->id() }}') setOpen(false)"
    class="{{ $styles->wrapper() }}" style="display: none;" {!! $offcanvas->attrs() !!}>

    <!-- Optional dimmed backdrop overlay -->
    @if ($offcanvas->hasBackdrop())
        <div x-cloak x-show="open"
            x-transition:enter="transition-opacity ease-out duration-250 motion-reduce:transition-opacity"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-out duration-200 motion-reduce:transition-opacity"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-on:click="setOpen(false)"
            class="{{ $styles->backdrop() }}"></div>
    @endif

    <!-- Sliding offcanvas panel returning smoothly to its starting edge -->
    <div x-cloak x-show="open"
        x-transition:enter="transform transition ease-out duration-250 motion-reduce:transition-opacity"
        x-transition:enter-start="{{ $offcanvas->enterStart() }}" x-transition:enter-end="{{ $offcanvas->enterEnd() }}"
        x-transition:leave="transform transition ease-out duration-200 motion-reduce:transition-opacity"
        x-transition:leave-start="{{ $offcanvas->enterEnd() }}" x-transition:leave-end="{{ $offcanvas->leaveEnd() }}"
        class="{{ $styles->panel() }}" @click.stop>

        <!-- Panel header bar -->
        <div class="{{ $styles->header() }}">
            <h5 class="{{ $styles->title() }}">
                {{ $offcanvas->title() }}
            </h5>

            @if ($offcanvas->showsClose())
                <button type="button" x-on:click="setOpen(false)" aria-label="Close offcanvas"
                    class="{{ $styles->closeButton() }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>

        <!-- Optional body slot -->
        @if ($_hasBody)
            <div class="{{ $styles->body() }}">
                {!! $body !!}
            </div>
        @endif

        <!-- Optional footer slot -->
        @if ($_hasFooter)
            <div class="{{ $styles->footer() }}">
                {!! $footer !!}
            </div>
        @endif

    </div>

</div>
