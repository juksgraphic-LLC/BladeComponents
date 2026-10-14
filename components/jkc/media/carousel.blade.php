@php
    /**
     * Carousel component — Juksgraphic / Penguin UI
     *
     * Reusable carousel powered by Swiper.js (npm) and Alpine.js.
     * Works with Laravel Blade and BladeOne.
     *
     * @param string      $variant       'slides-only'|'controls'|'indicators'|'captions'|'crossfade'|'individual-interval'|'no-touch'|'dark'|'coverflow'|'cards'|'creative'. Default: 'slides-only'.
     * @param array       $slides        Array of slides: [['img' => 'url', 'alt' => '', 'caption_title' => '', 'caption_text' => '', 'interval' => 3000]]. Default: [].
     * @param bool        $autoplay      Enable autoplay. Default: false.
     * @param int         $autoplayDelay Autoplay delay in milliseconds. Default: 3000.
     * @param bool        $loop          Infinite loop. Default: true.
     * @param string      $height        Tailwind height class (e.g., 'h-72', 'h-96'). Default: 'h-72'.
     * @param string|null $id            Custom ID. Default: generated hex.
     * @param string      $wrapClass     Additional wrapper classes. Default: ''.
     * @param bool        $dark          Force dark theme for controls. Default: false.
     * @param string|null $effect        Force Swiper effect ('slide'|'fade'|'coverflow'|'cards'|'creative'). Default: null.
     */

    /** @var string $variant */
    $variant = (string) ($variant ?? 'slides-only');

    /** @var array $slides */
    $slides = $slides ?? [];

    /** @var bool $autoplay */
    $autoplay = (bool) ($autoplay ?? false);

    /** @var int $autoplayDelay */
    $autoplayDelay = (int) ($autoplayDelay ?? 3000);

    /** @var bool $loop */
    $loop = (bool) ($loop ?? true);

    /** @var string $height */
    $height = (string) ($height ?? 'h-72');

    /** @var string $wrapClass */
    $wrapClass = (string) ($wrapClass ?? '');

    /** @var bool $dark */
    $dark = (bool) ($dark ?? false);

    /** @var string $carouselId */
    $carouselId = $id ?? 'swiper-' . bin2hex(random_bytes(4));

    /** @var string|null $effect */
    $effect = $effect ?? null;

    /** @var array<string, string> $_effectMap */
    $_effectMap = [
        'slides-only' => 'slide',
        'controls' => 'slide',
        'indicators' => 'slide',
        'captions' => 'slide',
        'crossfade' => 'fade',
        'individual-interval' => 'slide',
        'no-touch' => 'slide',
        'dark' => 'slide',
        'coverflow' => 'coverflow',
        'cards' => 'cards',
        'creative' => 'creative',
    ];
    $_swiperEffect = $effect ?? ($_effectMap[$variant] ?? 'slide');

    /** @var bool $_showControls */
    $_showControls = in_array(
        $variant,
        ['controls', 'captions', 'individual-interval', 'dark', 'no-touch', 'creative'],
        true,
    );

    /** @var bool $_showDots */
    $_showDots = in_array($variant, ['indicators', 'captions', 'dark', 'cards', 'coverflow', 'creative'], true);

    /** @var bool $_noTouch */
    $_noTouch = $variant === 'no-touch';

    /** @var bool $_isDark */
    $_isDark = $dark || $variant === 'dark';

    /** @var string $_btnBase */
    $_btnBase =
        'absolute top-1/2 -translate-y-1/2 z-10 flex items-center justify-center w-9 h-9 rounded-full transition-all duration-200 focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current ';

    /** @var string $_btnTheme */
    $_btnTheme = $_isDark
        ? 'bg-black/50 text-white hover:bg-black/70 border border-white/20'
        : 'bg-white/80 dark:bg-black/50 text-on-surface dark:text-white hover:bg-white dark:hover:bg-black/70 shadow-md border border-outline/20 dark:border-white/20';

    /** @var string $_wrapperBase */
    $_wrapperBase = 'relative w-full overflow-hidden rounded-radius';
    if (in_array($_swiperEffect, ['coverflow', 'cards'], true)) {
        $_wrapperBase .= ' overflow-visible';
    }
@endphp

{{-- ═══════════════════════════ Template ═══════════════════════════ --}}
<div x-data="{
    swiper: null,
    activeIndex: 0,
    totalSlides: {{ count($slides) }},
    init() {
        this.swiper = new Swiper(this.$refs.swiperEl, {
            effect: '{{ $_swiperEffect }}',
            loop: {{ $loop ? 'true' : 'false' }},
            allowTouchMove: {{ $_noTouch ? 'false' : 'true' }},
            grabCursor: {{ !$_noTouch ? 'true' : 'false' }},
            speed: {{ $_swiperEffect === 'fade' ? 600 : 400 }},
            observer: true,
            observeParents: true,
            @if ($autoplay || $variant === 'individual-interval') autoplay: {
                delay: {{ $autoplayDelay }},
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            }, @endif
            @if ($_swiperEffect === 'coverflow') centeredSlides: true,
            slidesPerView: 'auto',
            coverflowEffect: {
                rotate: 40,
                stretch: 0,
                depth: 120,
                modifier: 1,
                slideShadows: true,
            },
            @elseif ($_swiperEffect === 'cards')
            centeredSlides: true,
            slidesPerView: 'auto',
            cardsEffect: {
                slideShadows: true,
                perSlideOffset: 8,
                perSlideRotate: 2,
            },
            @elseif ($_swiperEffect === 'creative')
            centeredSlides: true,
            slidesPerView: 1,
            creativeEffect: {
                prev: { shadow: true, translate: ['-120%', 0, -500] },
                next: { translate: ['120%', 0, 0] },
            },
            @elseif ($_swiperEffect === 'fade')
            fadeEffect: { crossFade: true }, @endif
            on: {
                slideChange: (sw) => {
                    this.activeIndex = sw.realIndex;
                },
            },
        });
    },
    goTo(idx) { this.swiper && this.swiper.slideToLoop(idx); },
    prev() { this.swiper && this.swiper.slidePrev(); },
    next() { this.swiper && this.swiper.slideNext(); },
}" x-init="init()" class="{{ $_wrapperBase }} {{ $wrapClass }}">

    {{-- Swiper Container (avec hauteur explicite pour éviter l'affichage à 0px) --}}
    <div x-ref="swiperEl" class="swiper {{ $height }} w-full {{ $_isDark ? 'carousel-dark' : '' }}">
        <div class="swiper-wrapper h-full">
            @if (empty($slides))
                echo "Slides vides";
            @endif
            @foreach ($slides as $_si => $_slide)
                @php
                    $_slideAttrs = '';
                    if ($variant === 'individual-interval' && !empty($_slide['interval'])) {
                        $_slideAttrs = 'data-swiper-autoplay="' . (int) $_slide['interval'] . '"';
                    }
                @endphp
                <div class="swiper-slide h-full {{ in_array($_swiperEffect, ['coverflow', 'cards'], true) ? 'w-72 md:w-80' : 'w-full' }}"
                    {!! $_slideAttrs !!}>

                    {{-- Slide Content --}}
                    <div
                        class="relative h-full w-full overflow-hidden {{ $_swiperEffect === 'cards' ? 'rounded-xl' : 'rounded-radius' }}">
                        @if (!empty($_slide['img']))
                            <img src="{{ $_slide['img'] }}" alt="{{ $_slide['alt'] ?? '' }}"
                                class="w-full h-full object-cover block">
                        @else
                            @php
                                $_placeholderColors = [
                                    'bg-primary/20',
                                    'bg-secondary/20',
                                    'bg-success/20',
                                    'bg-warning/20',
                                    'bg-info/20',
                                ];
                                $_bg = $_placeholderColors[$_si % count($_placeholderColors)];
                            @endphp
                            <div class="w-full h-full {{ $_bg }} flex items-center justify-center">
                                <svg class="w-12 h-12 text-on-surface/20 dark:text-on-surface-dark/20" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        {{-- Caption Overlay --}}
                        @if (!empty($_slide['caption_title']) || !empty($_slide['caption_text']))
                            <div
                                class="absolute inset-x-0 bottom-0 px-5 py-4 {{ $_isDark ? 'bg-gradient-to-t from-black/80 via-black/50 to-transparent text-white' : 'bg-gradient-to-t from-black/70 via-black/40 to-transparent text-white' }}">
                                @if (!empty($_slide['caption_title']))
                                    <h6 class="text-base font-semibold font-title mb-0.5">
                                        {{ $_slide['caption_title'] }}</h6>
                                @endif
                                @if (!empty($_slide['caption_text']))
                                    <p class="text-sm text-white/80 leading-snug">{{ $_slide['caption_text'] }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Controls Prev / Next --}}
    @if ($_showControls)
        <button type="button" @click="prev()" class="{{ $_btnBase }} left-3 {{ $_btnTheme }}"
            aria-label="Previous slide">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button type="button" @click="next()" class="{{ $_btnBase }} right-3 {{ $_btnTheme }}"
            aria-label="Next slide">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    @endif

    {{-- Indicators / Dots --}}
    @if ($_showDots)
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-10 flex items-center gap-1.5">
            @foreach ($slides as $_di => $_ignored)
                <button type="button" @click="goTo({{ $_di }})"
                    :class="activeIndex === {{ $_di }} ?
                        'w-5 h-2 {{ $_isDark ? 'bg-white' : 'bg-primary' }} rounded-full' :
                        'w-2 h-2 {{ $_isDark ? 'bg-white/50 hover:bg-white/80' : 'bg-white/70 hover:bg-white' }} rounded-full'"
                    class="transition-all duration-300 rounded-full focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
                    aria-label="Go to slide {{ $_di + 1 }}">
                </button>
            @endforeach
        </div>
    @endif
</div>
