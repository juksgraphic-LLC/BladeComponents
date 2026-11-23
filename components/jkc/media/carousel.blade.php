@php
    use Juksgraphic\BladeComponents\Components\Media\Carousel;
    use Juksgraphic\BladeComponents\Components\Media\CarouselStyles;

    $carousel = new Carousel([
        'id'            => $id        ?? null,
        'variant'       => $variant   ?? 'slides-only',
        'slides'        => $slides    ?? [],
        'autoplay'      => $autoplay  ?? false,
        'autoplayDelay' => $autoplayDelay ?? 3000,
        'loop'          => $loop      ?? true,
        'height'        => $height    ?? 'h-72',
        'wrapClass'     => $wrapClass ?? '',
        'dark'          => $dark      ?? false,
        'effect'        => $effect    ?? null,
    ]);

    $styles = new CarouselStyles($carousel);
@endphp

<!-- Root carousel container with Alpine.js and Swiper.js lifecycle -->
<div
    id="{{ $carousel->id() }}"
    x-data="{
        ...{{ $carousel->toJson() }},

        init() {
            this.swiper = new Swiper(this.$refs.swiperEl, {
                ...this.swiperOptions,
                on: {
                    slideChange: (sw) => {
                        this.activeIndex = sw.realIndex;
                    },
                },
            });
        },

        goTo(idx) {
            this.swiper && this.swiper.slideToLoop(idx);
        },

        prev() {
            this.swiper && this.swiper.slidePrev();
        },

        next() {
            this.swiper && this.swiper.slideNext();
        }
    }"
    x-init="init()"
    class="{{ $styles->wrapper() }}"
>

    <!-- Swiper viewport with explicit height -->
    <div
        x-ref="swiperEl"
        class="{{ $styles->swiperContainer() }}"
    >
        <div class="{{ $styles->swiperWrapper() }}">

            @if (!$carousel->isEmpty())
                @foreach ($carousel->slides() as $index => $slide)
                    <div
                        class="{{ $styles->slide() }}"
                        @if ($slide['interval'] !== null) data-swiper-autoplay="{{ $slide['interval'] }}" @endif
                    >
                        <div class="{{ $styles->slideFrame() }}">

                            <!-- Slide image or colored SVG placeholder -->
                            @if ($slide['img'] !== null)
                                <img
                                    src="{{ $slide['img'] }}"
                                    alt="{{ $slide['alt'] }}"
                                    class="{{ $styles->slideImage() }}"
                                >
                            @else
                                <div class="{{ $styles->placeholderBox($index) }}">
                                    <svg
                                        class="{{ $styles->placeholderIcon() }}"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >
                                        <path
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         stroke-width="1.5"
                                         d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Optional caption overlay -->
                            @if ($slide['has_caption'])
                                <div class="{{ $styles->captionOverlay() }}">
                                    @if ($slide['caption_title'] !== null)
                                        <h6 class="{{ $styles->captionTitle() }}">
                                            {{ $slide['caption_title'] }}
                                        </h6>
                                    @endif

                                    @if ($slide['caption_text'] !== null)
                                        <p class="{{ $styles->captionText() }}">
                                            {{ $slide['caption_text'] }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>

    <!-- Previous / next navigation buttons -->
    @if ($carousel->showControls())
        <button
            type="button"
            x-on:click="prev()"
            class="{{ $styles->controlButton('left') }}"
            aria-label="Previous slide"
        >
            <svg 
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <button
            type="button"
            x-on:click="next()"
            class="{{ $styles->controlButton('right') }}"
            aria-label="Next slide"
        >
            <svg 
            class="w-4 h-4"
            fill="none" 
            stroke="currentColor"
            viewBox="0 0 24 24" 
            aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    @endif

    <!-- Pagination dot indicators -->
    @if ($carousel->showDots())
        <div class="{{ $styles->dotsContainer() }}">
            @foreach ($carousel->slides() as $index => $slide)
                <button
                    type="button"
                    x-on:click="goTo({{ $index }})"
                    :class="{!! $styles->dotBinding($index) !!}"
                    class="{{ $styles->dotButton() }}"
                    aria-label="Go to slide {{ $index + 1 }}"
                ></button>
            @endforeach
        </div>
    @endif

</div>