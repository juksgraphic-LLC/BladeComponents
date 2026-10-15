<?php

use eftec\bladeone\BladeOne;

function renderCarousel(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);
    
    $defaults = [
        'variant'       => 'slides-only',
        'slides'        => [
            ['img' => 'https://example.com/slide1.jpg', 'alt' => 'Slide 1', 'caption_title' => 'Title 1', 'caption_text' => 'Text 1'],
            ['img' => 'https://example.com/slide2.jpg', 'alt' => 'Slide 2'],
        ],
        'autoplay'      => false,
        'autoplayDelay' => 3000,
        'loop'          => true,
        'height'        => 'h-72',
        'wrapClass'     => '',
        'dark'          => false,
        'id'            => 'swiper-test',
        'effect'        => null,
    ];

    return $blade->run('jkc.media.carousel', array_merge($defaults, $data));
}

it('renders default carousel with slides correctly', function () {
    $html = renderCarousel([
        'slides' => [
            ['img' => 'https://example.com/img.jpg', 'alt' => 'Test Image']
        ]
    ]);

    expect($html)->toContain('https://example.com/img.jpg')
                 ->toContain('Test Image')
                 ->toContain('swiper-wrapper')
                 ->toContain('x-data="{');
});

it('renders controls when variant is controls', function () {
    $html = renderCarousel([
        'variant' => 'controls',
    ]);

    expect($html)->toContain('Previous slide')
                 ->toContain('Next slide');
});

it('renders indicators when variant is indicators', function () {
    $html = renderCarousel([
        'variant' => 'indicators',
        'slides'  => [
            ['img' => 'https://example.com/1.jpg'],
            ['img' => 'https://example.com/2.jpg'],
        ],
    ]);

    expect($html)->toContain('Go to slide 1')
                 ->toContain('Go to slide 2');
});

it('renders slide captions when provided in slide data', function () {
    $html = renderCarousel([
        'variant' => 'captions',
        'slides'  => [
            ['img' => 'https://example.com/1.jpg', 'caption_title' => 'My Title', 'caption_text' => 'My Description'],
        ],
    ]);

    expect($html)->toContain('My Title')
                 ->toContain('My Description');
});