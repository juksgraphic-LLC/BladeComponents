<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Media;

/**
 * Tailwind classes for the carousel wrapper, slides, controls, and indicators.
 */
class CarouselStyles
{
    /**
     * Carousel instance providing configuration and state.
     *
     * @var Carousel
     */
    private Carousel $carousel;

    /**
     * Create the styles helper.
     *
     * @param Carousel $carousel Carousel instance to style.
     */
    public function __construct(Carousel $carousel)
    {
        $this->carousel = $carousel;
    }

    /**
     * Get outer wrapper classes.
     *
     * @return string
     */
    public function wrapper(): string
    {
        $classes = 'relative w-full overflow-hidden rounded-radius';

        if ($this->carousel->hasEffect('coverflow', 'cards')) {
            $classes .= ' overflow-visible';
        }

        if ($this->carousel->wrapClass() !== '') {
            $classes .= ' ' . $this->carousel->wrapClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get `.swiper` container classes including height and dark flag.
     *
     * @return string
     */
    public function swiperContainer(): string
    {
        $classes = "swiper {$this->carousel->height()} w-full";

        if ($this->carousel->isDark()) {
            $classes .= ' carousel-dark';
        }

        return $classes;
    }

    /**
     * Get `.swiper-wrapper` classes.
     *
     * @return string
     */
    public function swiperWrapper(): string
    {
        return 'swiper-wrapper h-full';
    }

    /**
     * Get `.swiper-slide` width and layout classes.
     *
     * @return string
     */
    public function slide(): string
    {
        $widthClass = $this->carousel->hasEffect('coverflow', 'cards')
            ? 'w-72 md:w-80'
            : 'w-full';

        return "swiper-slide h-full {$widthClass}";
    }

    /**
     * Get inner slide frame classes.
     *
     * @return string
     */
    public function slideFrame(): string
    {
        $radius = $this->carousel->hasEffect('cards') ? 'rounded-xl' : 'rounded-radius';

        return "relative h-full w-full overflow-hidden {$radius}";
    }

    /**
     * Get full-bleed slide image classes.
     *
     * @return string
     */
    public function slideImage(): string
    {
        return 'w-full h-full object-cover block';
    }

    /**
     * Get fallback placeholder box classes for slides without an image.
     *
     * @param int $index Slide index.
     *
     * @return string
     */
    public function placeholderBox(int $index): string
    {
        $slide = $this->carousel->slides()[$index];

        return "w-full h-full {$slide['placeholderBg']} flex items-center justify-center";
    }

    /**
     * Get placeholder SVG icon classes.
     *
     * @return string
     */
    public function placeholderIcon(): string
    {
        return 'w-12 h-12 text-on-surface/20 dark:text-on-surface-dark/20';
    }

    /**
     * Get caption overlay container classes.
     *
     * @return string
     */
    public function captionOverlay(): string
    {
        $gradient = $this->carousel->isDark()
            ? 'bg-gradient-to-t from-black/80 via-black/50 to-transparent text-white'
            : 'bg-gradient-to-t from-black/70 via-black/40 to-transparent text-white';

        return "absolute inset-x-0 bottom-0 px-5 py-4 {$gradient}";
    }

    /**
     * Get caption heading classes.
     *
     * @return string
     */
    public function captionTitle(): string
    {
        return 'text-base font-semibold font-title mb-0.5';
    }

    /**
     * Get caption body text classes.
     *
     * @return string
     */
    public function captionText(): string
    {
        return 'text-sm text-white/80 leading-snug';
    }

    /**
     * Get previous/next button classes for left or right side.
     *
     * @param string $side Button position ('left' or 'right').
     *
     * @return string
     */
    public function controlButton(string $side): string
    {
        $base = 'absolute top-1/2 -translate-y-1/2 z-10 flex items-center justify-center w-9 h-9 rounded-full transition-all duration-200 focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current';
        $pos  = $side === 'right' ? 'right-3' : 'left-3';
        $tone = $this->carousel->isDark()
            ? 'bg-black/50 text-white hover:bg-black/70 border border-white/20'
            : 'bg-white/80 dark:bg-black/50 text-on-surface dark:text-white hover:bg-white dark:hover:bg-black/70 shadow-md border border-outline/20 dark:border-white/20';

        return "{$base} {$pos} {$tone}";
    }

    /**
     * Get pagination indicators container classes.
     *
     * @return string
     */
    public function dotsContainer(): string
    {
        return 'absolute bottom-3 left-1/2 -translate-x-1/2 z-10 flex items-center gap-1.5';
    }

    /**
     * Get Alpine `:class` binding expression for an indicator dot.
     *
     * @param int $index Slide index.
     *
     * @return string
     */
    public function dotBinding(int $index): string
    {
        $activeBg   = $this->carousel->isDark() ? 'bg-white' : 'bg-primary';
        $inactiveBg = $this->carousel->isDark() ? 'bg-white/50 hover:bg-white/80' : 'bg-white/70 hover:bg-white';

        return "activeIndex === {$index} ? 'w-5 h-2 {$activeBg} rounded-full' : 'w-2 h-2 {$inactiveBg} rounded-full'";
    }

    /**
     * Get base classes for an indicator dot button.
     *
     * @return string
     */
    public function dotButton(): string
    {
        return 'transition-all duration-300 rounded-full focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current';
    }
}