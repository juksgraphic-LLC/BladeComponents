@php
    /**
     * Chart Card Component — Juksgraphic / Penguin UI
     *
     * Reusable chart wrapper built with Tailwind CSS, Alpine.js, and ApexCharts.
     * Works with Laravel Blade and BladeOne.
     *
     * @param string       $title         Card title displayed at the top. Default: 'Chart'.
     * @param string|null  $subtitle      Optional subtitle displayed under the title. Default: null.
     * @param string|null  $chartId       Unique DOM identifier for the chart. Default: auto-generated.
     * @param array        $options       ApexCharts configuration options array. Default: [].
     * @param bool         $mergeDefaults Whether to merge options with default chart theme settings. Default: true.
     * @param string       $wrapClass     Extra CSS classes for the card container wrapper. Default: ''.
     */

    /** @var string $title */
    $title = (string) ($title ?? 'Chart');

    /** @var string|null $subtitle */
    $subtitle = isset($subtitle) ? (string) $subtitle : null;

    /** @var string $chartId */
    $chartId = isset($chartId)
        ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $chartId)
        : 'chart-' . substr(md5(uniqid()), 0, 8);

    /** @var array<string, mixed> $options */
    $options = $options ?? [];

    /** @var bool $mergeDefaults */
    $mergeDefaults = (bool) ($mergeDefaults ?? true);

    /** @var string $wrapClass */
    $wrapClass = (string) ($wrapClass ?? '');

    $_defaults = [
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

    /** @var array<string, mixed> $_merged */
    $_merged = $mergeDefaults ? array_replace_recursive($_defaults, $options) : $options;

    /** @var string $jsons */
    $jsons = json_encode(
        $_merged,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE,
    );
@endphp

<div
    class="bg-surface dark:bg-surface-dark border border-outline dark:border-outline-dark rounded-radius overflow-hidden {{ $wrapClass }}">

    {{-- Header --}}
    <div class="px-5 pt-5 pb-3">
        <h5 class="text-sm font-semibold text-on-surface dark:text-on-surface-dark leading-tight">{{ $title }}
        </h5>
        @if ($subtitle)
            <p class="text-xs text-on-surface/60 dark:text-on-surface-dark/60 mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="px-4 pb-5">
        <div class="w-full" x-data="{
            chart: null,
            init() {
                if (this.chart) return;
                this.chart = new ApexCharts(document.getElementById('{{ $chartId }}'), {{ $jsons }});
                this.chart.render();
        
                requestAnimationFrame(() => {
                    this.chart.updateOptions({});
                });
            }
        }" x-init="init()">
            <div id="{{ $chartId }}" class="w-full"></div>
        </div>
    </div>
</div>
