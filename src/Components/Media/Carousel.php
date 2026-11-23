<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Media;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Swiper.js and Alpine.js carousel configuration and slide normalization helper.
 */
class Carousel extends Component
{
    /**
     * Allowed carousel variant names.
     *
     * @var array<int, string>
     */
    public const VARIANTS = [
        'slides-only', 'controls', 'indicators', 'captions',
        'crossfade', 'individual-interval', 'no-touch', 'dark',
        'coverflow', 'cards', 'creative',
    ];

    /**
     * Allowed Swiper effect names.
     *
     * @var array<int, string>
     */
    public const EFFECTS = [
        'slide', 'fade', 'coverflow', 'cards', 'creative',
    ];

    /**
     * Variant-to-Swiper-effect mapping.
     *
     * @var array<string, string>
     */
    private const EFFECT_MAP = [
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

    /**
     * Variants displaying previous/next control buttons.
     *
     * @var array<int, string>
     */
    private const CONTROL_VARIANTS = [
        'controls', 'captions', 'individual-interval',
        'dark', 'no-touch', 'creative',
    ];

    /**
     * Variants displaying pagination indicator dots.
     *
     * @var array<int, string>
     */
    private const DOT_VARIANTS = [
        'indicators', 'captions', 'dark',
        'cards', 'coverflow', 'creative',
    ];

    /**
     * Fallback background classes for slides without an image.
     *
     * @var array<int, string>
     */
    private const PLACEHOLDER_BACKGROUNDS = [
        'bg-primary/20',
        'bg-secondary/20',
        'bg-success/20',
        'bg-warning/20',
        'bg-info/20',
    ];

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'id' => null,
        'variant' => 'slides-only',
        'slides' => [],
        'autoplay' => false,
        'autoplayDelay' => 3000,
        'loop' => true,
        'height' => 'h-72',
        'wrapClass' => '',
        'dark' => false,
        'effect' => null,
    ];

    /**
     * Create and sanitize the carousel configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $variant = (string) $this->config['variant'];
        $variant = in_array($variant, self::VARIANTS, true) ? $variant : 'slides-only';

        $requestedEffect = is_string($this->config['effect'] ?? null) && in_array($this->config['effect'], self::EFFECTS, true)
            ? $this->config['effect']
            : null;
        $swiperEffect = $requestedEffect ?? (self::EFFECT_MAP[$variant] ?? 'slide');

        $isDark = (bool) $this->config['dark'] || $variant === 'dark';
        $delay = is_numeric($this->config['autoplayDelay']) ? max(0, (int) $this->config['autoplayDelay']) : 3000;
        $height = is_scalar($this->config['height']) && trim((string) $this->config['height']) !== ''
            ? trim((string) $this->config['height'])
            : 'h-72';

        $this->config['id'] = $this->safeId($this->config['id']);
        $this->config['variant'] = $variant;
        $this->config['effect'] = $swiperEffect;
        $this->config['autoplay'] = (bool) $this->config['autoplay'];
        $this->config['autoplayDelay'] = $delay;
        $this->config['loop'] = (bool) $this->config['loop'];
        $this->config['height'] = $height;
        $this->config['wrapClass'] = is_scalar($this->config['wrapClass']) ? trim((string) $this->config['wrapClass']) : '';
        $this->config['dark'] = $isDark;
        $this->config['slides'] = $this->normalizeSlides((array) $this->config['slides'], $variant);
    }

    /**
     * Get the carousel DOM identifier.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->config['id'];
    }

    /**
     * Get the active variant name.
     *
     * @return string
     */
    public function variant(): string
    {
        return $this->config['variant'];
    }

    /**
     * Get the resolved Swiper effect name.
     *
     * @return string
     */
    public function effect(): string
    {
        return $this->config['effect'];
    }

    /**
     * Check whether the carousel uses any of the specified effects.
     *
     * @param string ...$effects Effect names to test.
     *
     * @return bool
     */
    public function hasEffect(string ...$effects): bool
    {
        return in_array($this->config['effect'], $effects, true);
    }

    /**
     * Get normalized slides array.
     *
     * @return array<int, array<string, mixed>>
     */
    public function slides(): array
    {
        return $this->config['slides'];
    }

    /**
     * Check whether there are no slides configured.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return count($this->config['slides']) === 0;
    }

    /**
     * Get total slide count.
     *
     * @return int
     */
    public function totalSlides(): int
    {
        return count($this->config['slides']);
    }

    /**
     * Check whether autoplay is enabled.
     *
     * @return bool
     */
    public function hasAutoplay(): bool
    {
        return (bool) $this->config['autoplay'] || $this->config['variant'] === 'individual-interval';
    }

    /**
     * Get autoplay delay in milliseconds.
     *
     * @return int
     */
    public function autoplayDelay(): int
    {
        return $this->config['autoplayDelay'];
    }

    /**
     * Check whether loop mode is enabled.
     *
     * @return bool
     */
    public function isLoop(): bool
    {
        return (bool) $this->config['loop'];
    }

    /**
     * Check whether touch dragging is disabled.
     *
     * @return bool
     */
    public function isNoTouch(): bool
    {
        return $this->config['variant'] === 'no-touch';
    }

    /**
     * Get Swiper transition duration in milliseconds.
     *
     * @return int
     */
    public function transitionSpeed(): int
    {
        return $this->config['effect'] === 'fade' ? 600 : 400;
    }

    /**
     * Get carousel container height class.
     *
     * @return string
     */
    public function height(): string
    {
        return $this->config['height'];
    }

    /**
     * Get extra wrapper classes.
     *
     * @return string
     */
    public function wrapClass(): string
    {
        return $this->config['wrapClass'];
    }

    /**
     * Check whether dark control styling is active.
     *
     * @return bool
     */
    public function isDark(): bool
    {
        return (bool) $this->config['dark'];
    }

    /**
     * Check whether previous/next buttons should be rendered.
     *
     * @return bool
     */
    public function showControls(): bool
    {
        return in_array($this->config['variant'], self::CONTROL_VARIANTS, true);
    }

    /**
     * Check whether indicator dots should be rendered.
     *
     * @return bool
     */
    public function showDots(): bool
    {
        return in_array($this->config['variant'], self::DOT_VARIANTS, true);
    }

    /**
     * Build Swiper options array for Alpine.js initialization.
     *
     * @return array<string, mixed>
     */
    public function swiperOptions(): array
    {
        $options = [
            'effect' => $this->effect(),
            'loop' => $this->isLoop(),
            'allowTouchMove' => ! $this->isNoTouch(),
            'grabCursor' => ! $this->isNoTouch(),
            'speed' => $this->transitionSpeed(),
            'observer' => true,
            'observeParents' => true,
        ];

        if ($this->hasAutoplay()) {
            $options['autoplay'] = [
                'delay' => $this->autoplayDelay(),
                'disableOnInteraction' => false,
                'pauseOnMouseEnter' => true,
            ];
        }

        match ($this->effect()) {
            'coverflow' => $options += [
                'centeredSlides' => true,
                'slidesPerView' => 'auto',
                'coverflowEffect' => [
                    'rotate' => 40,
                    'stretch' => 0,
                    'depth' => 120,
                    'modifier' => 1,
                    'slideShadows' => true,
                ],
            ],
            'cards' => $options += [
                'centeredSlides' => true,
                'slidesPerView' => 'auto',
                'cardsEffect' => [
                    'slideShadows' => true,
                    'perSlideOffset' => 8,
                    'perSlideRotate' => 2,
                ],
            ],
            'creative' => $options += [
                'centeredSlides' => true,
                'slidesPerView' => 1,
                'creativeEffect' => [
                    'prev' => ['shadow' => true, 'translate' => ['-120%', 0, -500]],
                    'next' => ['translate' => ['120%', 0, 0]],
                ],
            ],
            'fade' => $options += [
                'fadeEffect' => ['crossFade' => true],
            ],
            default => null,
        };

        return $options;
    }

    /**
     * Get Alpine component initial state data.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'swiper' => null,
            'activeIndex' => 0,
            'totalSlides' => $this->totalSlides(),
            'swiperOptions' => $this->swiperOptions(),
        ];
    }

    /**
     * Sanitize or generate a carousel DOM ID.
     *
     * @param mixed $id Raw ID value.
     *
     * @return string
     */
    private function safeId(mixed $id): string
    {
        $clean = is_scalar($id) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id) : '';

        return ($clean !== null && $clean !== '') ? $clean : 'swiper-' . bin2hex(random_bytes(4));
    }

    /**
     * Reject unsafe URI schemes on slide image sources.
     *
     * @param mixed $src Raw image URL/path.
     *
     * @return string|null
     */
    private function sanitizeImageUrl(mixed $src): ?string
    {
        if (! is_string($src)) {
            return null;
        }

        $trimmed = trim($src);

        if ($trimmed === '' || preg_match('/^\s*(javascript|data|vbscript):/i', $trimmed)) {
            return null;
        }

        return $trimmed;
    }

    /**
     * Normalize raw slides into structured items.
     *
     * @param array<int, mixed> $slides  Raw slide items.
     * @param string            $variant Active variant name.
     *
     * @return array<int, array<string, mixed>>
     */
    private function normalizeSlides(array $slides, string $variant): array
    {
        $normalized = [];

        foreach (array_values($slides) as $index => $slide) {
            $slide = (array) $slide;
            $safeImg = $this->sanitizeImageUrl($slide['img'] ?? null);
            $captionTitle = isset($slide['caption_title']) && is_scalar($slide['caption_title']) && (string) $slide['caption_title'] !== ''
                ? (string) $slide['caption_title']
                : null;
            $captionText = isset($slide['caption_text']) && is_scalar($slide['caption_text']) && (string) $slide['caption_text'] !== ''
                ? (string) $slide['caption_text']
                : null;
            $interval = ($variant === 'individual-interval' && isset($slide['interval']) && is_numeric($slide['interval']))
                ? max(0, (int) $slide['interval'])
                : null;

            $normalized[] = [
                'img' => $safeImg,
                'alt' => isset($slide['alt']) && is_scalar($slide['alt']) ? (string) $slide['alt'] : '',
                'caption_title' => $captionTitle,
                'caption_text' => $captionText,
                'has_caption' => $captionTitle !== null || $captionText !== null,
                'interval' => $interval,
                'placeholderBg' => self::PLACEHOLDER_BACKGROUNDS[$index % count(self::PLACEHOLDER_BACKGROUNDS)],
            ];
        }

        return $normalized;
    }
}
