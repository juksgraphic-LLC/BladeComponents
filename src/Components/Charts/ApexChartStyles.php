<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Charts;

/**
 * Tailwind classes for the ApexCharts card component.
 */
class ApexChartStyles
{
    /**
     * Chart instance providing state and options.
     *
     * @var ApexChart
     */
    private ApexChart $chart;

    /**
     * Create the styles helper.
     *
     * @param ApexChart $chart Chart configuration instance.
     */
    public function __construct(ApexChart $chart)
    {
        $this->chart = $chart;
    }

    /**
     * Get root card container classes.
     *
     * @return string
     */
    public function container(): string
    {
        $classes = 'bg-surface dark:bg-surface-dark border border-outline dark:border-outline-dark rounded-radius overflow-hidden';

        if ($this->chart->wrapClass() !== '') {
            $classes .= ' ' . $this->chart->wrapClass();
        }

        return $classes;
    }

    /**
     * Get header container classes.
     *
     * @return string
     */
    public function header(): string
    {
        return 'px-5 pt-5 pb-3';
    }

    /**
     * Get title heading classes.
     *
     * @return string
     */
    public function title(): string
    {
        return 'text-sm font-semibold text-on-surface dark:text-on-surface-dark leading-tight';
    }

    /**
     * Get subtitle text classes.
     *
     * @return string
     */
    public function subtitle(): string
    {
        return 'text-xs text-on-surface/60 dark:text-on-surface-dark/60 mt-0.5';
    }

    /**
     * Get chart body wrapper classes.
     *
     * @return string
     */
    public function body(): string
    {
        return 'px-4 pb-5';
    }

    /**
     * Get chart canvas/mount wrapper classes.
     *
     * @return string
     */
    public function canvas(): string
    {
        return 'w-full';
    }
}
