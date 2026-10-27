<?php

use eftec\bladeone\BladeOne;

function renderApexChart(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

    return $blade->run('jkc.charts.apex', $data);
}

it('renders default chart card with title and auto-generated chart id', function () {
    $html = renderApexChart([
        'title' => 'Revenue Analytics',
    ]);

    $decodedHtml = html_entity_decode($html);

    expect($decodedHtml)->toContain('Revenue Analytics')
        ->toContain('bg-surface')
        ->toContain('new ApexCharts(document.getElementById(\'chart-')
        ->toContain('id="chart-');
});

it('renders chart card with subtitle and custom chart id', function () {
    $html = renderApexChart([
        'title' => 'Sales Performance',
        'subtitle' => 'Monthly revenue growth for 2026',
        'chartId' => 'custom-sales-chart',
    ]);

    $decodedHtml = html_entity_decode($html);

    expect($decodedHtml)->toContain('Sales Performance')
        ->toContain('Monthly revenue growth for 2026')
        ->toContain('document.getElementById(\'custom-sales-chart\')')
        ->toContain('id="custom-sales-chart"');
});

it('merges custom options with default apexcharts theme configuration', function () {
    $html = renderApexChart([
        'title' => 'User Traffic',
        'options' => [
            'chart' => [
                'type' => 'line',
            ],
            'series' => [
                [
                    'name' => 'Visitors',
                    'data' => [30, 40, 35, 50, 49, 60],
                ],
            ],
        ],
        'mergeDefaults' => true,
    ]);

    $decodedHtml = html_entity_decode($html);

    expect($decodedHtml)->toContain('"type":"line"')
        ->toContain('"name":"Visitors"')
        ->toContain('"data":[30,40,35,50,49,60]')
        ->toContain('"background":"transparent"')
        ->toContain('#7367F0');
});

it('overrides default configuration when mergeDefaults is false', function () {
    $html = renderApexChart([
        'title' => 'Minimal Chart',
        'options' => [
            'chart' => ['type' => 'bar'],
            'series' => [['data' => [10, 20]]],
        ],
        'mergeDefaults' => false,
    ]);

    $decodedHtml = html_entity_decode($html);

    expect($decodedHtml)->toContain('"type":"bar"')
        ->not->toContain('#7367F0');
});

it('applies custom wrapper classes properly', function () {
    $html = renderApexChart([
        'title' => 'Custom Style Chart',
        'wrapClass' => 'shadow-lg border-primary/20',
    ]);

    expect($html)->toContain('shadow-lg border-primary/20');
});
