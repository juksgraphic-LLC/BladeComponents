<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Navigation;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Pagination range calculation, URL builder, and state helper.
 */
class Pagination extends Component
{
    /**
     * Supported pagination visual styles.
     *
     * @var array<int, string>
     */
    public const STYLES = [
        'default', 'pill', 'bordered', 'minimal',
    ];

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'total' => 50,
        'current' => 1,
        'perPage' => 10,
        'baseUrl' => '?page=',
        'window' => 1,
        'style' => 'default',
        'class' => '',
        'attrs' => '',
        'prevLabel' => 'Previous',
        'nextLabel' => 'Next',
    ];

    /**
     * Create and sanitize pagination parameters and page bounds.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $total = max(0, is_numeric($this->config['total']) ? (int) $this->config['total'] : 50);
        $perPage = max(1, is_numeric($this->config['perPage']) ? (int) $this->config['perPage'] : 10);
        $last = (int) ceil($total / $perPage);
        $current = max(1, min($last > 0 ? $last : 1, is_numeric($this->config['current']) ? (int) $this->config['current'] : 1));
        $window = max(0, is_numeric($this->config['window']) ? (int) $this->config['window'] : 1);
        $style = is_string($this->config['style'] ?? null) && in_array($this->config['style'], self::STYLES, true)
            ? $this->config['style']
            : 'default';

        $this->config['total'] = $total;
        $this->config['perPage'] = $perPage;
        $this->config['last'] = $last;
        $this->config['current'] = $current;
        $this->config['window'] = $window;
        $this->config['start'] = max(1, $current - $window);
        $this->config['end'] = min($last, $current + $window);
        $this->config['style'] = $style;
        $this->config['baseUrl'] = $this->sanitizeBaseUrl($this->config['baseUrl']);
        $this->config['class'] = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
        $this->config['attrs'] = is_scalar($this->config['attrs']) ? trim((string) $this->config['attrs']) : '';
        $this->config['prevLabel'] = is_scalar($this->config['prevLabel']) ? (string) $this->config['prevLabel'] : 'Previous';
        $this->config['nextLabel'] = is_scalar($this->config['nextLabel']) ? (string) $this->config['nextLabel'] : 'Next';
    }

    /**
     * Check whether multiple pages exist.
     *
     * @return bool
     */
    public function hasPages(): bool
    {
        return (int) $this->config['last'] > 1;
    }

    /**
     * Get current active page number.
     *
     * @return int
     */
    public function current(): int
    {
        return (int) $this->config['current'];
    }

    /**
     * Get last available page number.
     *
     * @return int
     */
    public function last(): int
    {
        return (int) $this->config['last'];
    }

    /**
     * Get first page number inside the visible window.
     *
     * @return int
     */
    public function start(): int
    {
        return (int) $this->config['start'];
    }

    /**
     * Get last page number inside the visible window.
     *
     * @return int
     */
    public function end(): int
    {
        return (int) $this->config['end'];
    }

    /**
     * Check whether the current page is the first page.
     *
     * @return bool
     */
    public function onFirstPage(): bool
    {
        return $this->current() <= 1;
    }

    /**
     * Check whether pages remain after the current page.
     *
     * @return bool
     */
    public function hasMorePages(): bool
    {
        return $this->current() < $this->last();
    }

    /**
     * Check whether a leading ellipsis should appear after page 1.
     *
     * @return bool
     */
    public function showLeadingEllipsis(): bool
    {
        return $this->start() > 2;
    }

    /**
     * Check whether a trailing ellipsis should appear before the last page.
     *
     * @return bool
     */
    public function showTrailingEllipsis(): bool
    {
        return $this->end() < ($this->last() - 1);
    }

    /**
     * Build the target URL for a given page number.
     *
     * @param int $page Target page number.
     *
     * @return string
     */
    public function linkFor(int $page): string
    {
        return (string) $this->config['baseUrl'] . $page;
    }

    /**
     * Get active visual style (`default`, `pill`, `bordered`, `minimal`).
     *
     * @return string
     */
    public function style(): string
    {
        return (string) $this->config['style'];
    }

    /**
     * Get previous button text.
     *
     * @return string
     */
    public function prevLabel(): string
    {
        return (string) $this->config['prevLabel'];
    }

    /**
     * Get next button text.
     *
     * @return string
     */
    public function nextLabel(): string
    {
        return (string) $this->config['nextLabel'];
    }

    /**
     * Get extra container CSS classes.
     *
     * @return string
     */
    public function extraClass(): string
    {
        return (string) $this->config['class'];
    }

    /**
     * Get extra container HTML attributes.
     *
     * @return string
     */
    public function attrs(): string
    {
        return (string) $this->config['attrs'];
    }

    /**
     * Sanitize base pagination URL to avoid dangerous URI schemes.
     *
     * @param mixed $raw Raw base URL.
     *
     * @return string
     */
    private function sanitizeBaseUrl(mixed $raw): string
    {
        $url = is_scalar($raw) ? trim((string) $raw) : '?page=';

        if ($url === '' || preg_match('/^\s*(javascript|data|vbscript):/i', $url)) {
            return '?page=';
        }

        return $url;
    }
}
