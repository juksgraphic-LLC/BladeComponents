<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Feedbacks;

/**
 * Tailwind classes and SVG icons for the global notifications stack.
 */
class NotificationStyles
{
    /**
     * Variant visual tokens for semantic notifications.
     *
     * @var array<string, array<string, string>>
     */
    private const SEMANTIC_VARIANTS = [
        'info' => [
            'card' => 'pointer-events-auto relative w-full overflow-hidden rounded-radius border border-info/50 bg-surface shadow-xl dark:bg-surface-dark',
            'inner' => 'flex items-center gap-2.5 p-3 md:p-4 bg-info/5',
            'icon' => 'rounded-full bg-info/15 p-0.5 text-info shrink-0',
            'title' => 'text-xs md:text-sm font-semibold text-info truncate',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" /></svg>',
        ],
        'success' => [
            'card' => 'pointer-events-auto relative w-full overflow-hidden rounded-radius border border-success/50 bg-surface shadow-xl dark:bg-surface-dark',
            'inner' => 'flex items-center gap-2.5 p-3 md:p-4 bg-success/5',
            'icon' => 'rounded-full bg-success/15 p-0.5 text-success shrink-0',
            'title' => 'text-xs md:text-sm font-semibold text-success truncate',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg>',
        ],
        'warning' => [
            'card' => 'pointer-events-auto relative w-full overflow-hidden rounded-radius border border-warning/50 bg-surface shadow-xl dark:bg-surface-dark',
            'inner' => 'flex items-center gap-2.5 p-3 md:p-4 bg-warning/5',
            'icon' => 'rounded-full bg-warning/15 p-0.5 text-warning shrink-0',
            'title' => 'text-xs md:text-sm font-semibold text-warning truncate',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" /></svg>',
        ],
        'danger' => [
            'card' => 'pointer-events-auto relative w-full overflow-hidden rounded-radius border border-danger/50 bg-surface shadow-xl dark:bg-surface-dark',
            'inner' => 'flex items-center gap-2.5 p-3 md:p-4 bg-danger/5',
            'icon' => 'rounded-full bg-danger/15 p-0.5 text-danger shrink-0',
            'title' => 'text-xs md:text-sm font-semibold text-danger truncate',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" /></svg>',
        ],
    ];

    /**
     * Get full-screen fixed overlay wrapper classes.
     *
     * @return string
     */
    public function overlay(): string
    {
        return 'pointer-events-none fixed inset-0 z-[9999] overflow-hidden';
    }

    /**
     * Get inner stage classes filling the overlay.
     *
     * @return string
     */
    public function stage(): string
    {
        return 'relative size-full';
    }

    /**
     * Get positioned stack container classes.
     *
     * @return string
     */
    public function stackContainer(): string
    {
        return 'pointer-events-none fixed z-[9999] flex w-full max-w-[280px] md:max-w-[400px] flex-col gap-2 md:gap-3 px-4 py-4 md:px-6 md:py-6 transition-all duration-300';
    }

    /**
     * Get Alpine dynamic `:class` expression for stack screen positioning.
     *
     * @return string
     */
    public function positionBinding(): string
    {
        return "{\n"
            . "    'top-0 left-0 items-start': position === 'top-left',\n"
            . "    'top-0 left-1/2 -translate-x-1/2 items-center': position === 'top-center',\n"
            . "    'top-0 right-0 items-end': position === 'top-right',\n"
            . "    'bottom-0 left-0 items-start': position === 'bottom-left',\n"
            . "    'bottom-0 left-1/2 -translate-x-1/2 items-center': position === 'bottom-center',\n"
            . "    'bottom-0 right-0 items-end': position === 'bottom-right'\n"
            . "}";
    }

    /**
     * Get semantic notification tokens for a given variant key.
     *
     * @param string $variant Semantic variant (`info`, `success`, `warning`, `danger`).
     *
     * @return array<string, string>
     */
    public function semanticVariant(string $variant): array
    {
        return self::SEMANTIC_VARIANTS[$variant] ?? self::SEMANTIC_VARIANTS['info'];
    }

    /**
     * Get message notification card wrapper classes.
     *
     * @return string
     */
    public function messageCard(): string
    {
        return 'pointer-events-auto relative w-full overflow-hidden rounded-radius border border-outline bg-surface shadow-xl dark:border-outline-dark dark:bg-surface-dark';
    }

    /**
     * Get message notification inner row classes.
     *
     * @return string
     */
    public function messageInner(): string
    {
        return 'flex items-start gap-3 p-3 md:p-4 bg-surface-alt dark:bg-surface-dark-alt';
    }

    /**
     * Get sender avatar image classes.
     *
     * @return string
     */
    public function avatar(): string
    {
        return 'size-8 md:size-10 rounded-full object-cover border border-outline shadow-sm shrink-0';
    }

    /**
     * Get sender name heading classes.
     *
     * @return string
     */
    public function senderName(): string
    {
        return 'text-xs md:text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong truncate';
    }

    /**
     * Get notification body message classes.
     *
     * @return string
     */
    public function messageBody(): string
    {
        return 'text-[11px] md:text-xs text-on-surface/70 dark:text-on-surface-dark/70 mt-0.5 leading-tight';
    }

    /**
     * Get close button classes.
     *
     * @return string
     */
    public function closeButton(): string
    {
        return 'ml-auto text-on-surface/50 hover:text-on-surface dark:hover:text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current';
    }
}
