<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Charts;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * ApexCharts card configuration and merged options helper.
 */
class ApexChart extends Component
{
    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'title' => 'Chart',
        'subtitle' => null,
        'chartId' => null,
        'options' => [],
        'mergeDefaults' => true,
        'wrapClass' => '',
    ];

    /**
     * Default ApexCharts theme and layout options.
     *
     * @var array<string, mixed>
     */
    public const CHART_DEFAULTS = [
        'chart' => [
            'width' => '100%',
            'background' => 'transparent',
            'foreColor' => '#adb5bd',
            'toolbar' => ['show' => false],
            'zoom' => ['enabled' => false],
            'fontFamily' => 'inherit',
            'animations' => ['enabled' => true, 'easing' => 'easeinout', 'speed' => 600],
        ],
        'theme' => ['mode' => 'dark'],
        'colors' => ['#7367F0', '#28C76F', '#FF9F43', '#00CFE8', '#EA5455', '#9B59B6'],
        'grid' => ['borderColor' => 'rgba(255,255,255,0.06)', 'strokeDashArray' => 4],
        'tooltip' => ['theme' => 'dark', 'style' => ['fontSize' => '12px']],
        'legend' => [
            'position' => 'top',
            'horizontalAlign' => 'left',
            'labels' => ['colors' => '#adb5bd'],
            'markers' => ['width' => 20, 'height' => 6, 'radius' => 3],
            'itemMargin' => ['horizontal' => 10],
        ],
        'xaxis' => [
            'axisBorder' => ['show' => false],
            'axisTicks' => ['show' => false],
            'labels' => ['style' => ['colors' => '#adb5bd']],
        ],
        'yaxis' => ['labels' => ['style' => ['colors' => '#adb5bd']]],
        'dataLabels' => ['enabled' => false],
        'stroke' => ['curve' => 'smooth', 'width' => 2],
    ];

    /**
     * Create and sanitize the chart card configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $title = is_scalar($this->config['title']) ? (string) $this->config['title'] : 'Chart';
        $subtitle = is_scalar($this->config['subtitle']) ? (string) $this->config['subtitle'] : null;

        $this->config['title'] = $title !== '' ? $title : 'Chart';
        $this->config['subtitle'] = ($subtitle !== null && $subtitle !== '') ? $subtitle : null;
        $this->config['chartId'] = $this->sanitizeChartId($this->config['chartId']);
        $this->config['options'] = is_array($this->config['options']) ? $this->config['options'] : [];
        $this->config['mergeDefaults'] = (bool) $this->config['mergeDefaults'];
        $this->config['wrapClass'] = is_scalar($this->config['wrapClass']) ? trim((string) $this->config['wrapClass']) : '';
    }

    /**
     * Get the card title.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->config['title'];
    }

    /**
     * Get the optional subtitle.
     *
     * @return string|null
     */
    public function subtitle(): ?string
    {
        return $this->config['subtitle'];
    }

    /**
     * Get the sanitized chart DOM identifier.
     *
     * @return string
     */
    public function chartId(): string
    {
        return $this->config['chartId'];
    }

    /**
     * Get extra wrapper CSS classes.
     *
     * @return string
     */
    public function wrapClass(): string
    {
        return $this->config['wrapClass'];
    }

    /**
     * Get resolved ApexCharts options array.
     *
     * @return array<string, mixed>
     */
    public function chartOptions(): array
    {
        return $this->config['mergeDefaults']
            ? array_replace_recursive(self::CHART_DEFAULTS, $this->config['options'])
            : $this->config['options'];
    }

    /**
     * Encode resolved ApexCharts options as a safe JSON string.
     *
     * @param int $flags JSON encoding flags.
     *
     * @return string
     */
    public function optionsJson(int $flags = self::JSON_FLAGS): string
    {
        return json_encode($this->chartOptions(), $flags | JSON_THROW_ON_ERROR);
    }

    /**
     * Build a safe DOM ID for the chart element.
     *
     * @param mixed $chartId Raw chart identifier.
     *
     * @return string
     */
    private function sanitizeChartId(mixed $chartId): string
    {
        $clean = is_scalar($chartId) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $chartId) : '';

        return ($clean !== null && $clean !== '') ? $clean : 'chart-' . substr(bin2hex(random_bytes(4)), 0, 8);
    }
}
