@php
    use Juksgraphic\BladeComponents\Components\Charts\ApexChart;
    use Juksgraphic\BladeComponents\Components\Charts\ApexChartStyles;

    
    $chart = new ApexChart([
        'title'         => $title    ?? 'Chart',
        'subtitle'      => $subtitle ?? null,
        'chartId'       => $chartId  ?? null,
        'options'       => $options  ?? [],
        'mergeDefaults' => $mergeDefaults ?? true,
        'wrapClass'     => $wrapClass ?? '',
    ]);

    $styles = new ApexChartStyles($chart);
@endphp

<!-- Root chart card container -->
<div class="{{ $styles->container() }}">

    <!-- Card header with title and optional subtitle -->
    <div class="{{ $styles->header() }}">
        <h5 class="{{ $styles->title() }}">
            {{ $chart->title() }}
        </h5>

        @if ($chart->subtitle() !== null)
            <p class="{{ $styles->subtitle() }}">
                {{ $chart->subtitle() }}
            </p>
        @endif
    </div>

    <!-- Chart body wrapper -->
    <div class="{{ $styles->body() }}">

        <!-- Alpine.js component mounting and rendering ApexCharts -->
        <div
            class="{{ $styles->canvas() }}"
            x-data="{
                chart: null,

                
                init() {
                    if (this.chart) return;

                    this.chart = new ApexCharts(
                        document.getElementById('{{ $chart->chartId() }}'),
                        {{ $chart->optionsJson() }}
                    );
                    this.chart.render();

                    
                    requestAnimationFrame(() => {
                        this.chart.updateOptions({});
                    });
                }
            }"
            x-init="init()"
        >
            <!-- Target DOM node for ApexCharts -->
            <div
                id="{{ $chart->chartId() }}"
                class="{{ $styles->canvas() }}"
            ></div>
        </div>

    </div>

</div>