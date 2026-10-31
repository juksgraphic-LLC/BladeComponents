{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
@php
    /**
     * The one place where the table's SVG icons live.
     *
     * Parameters (always pass all four, an include also sees the caller's variables):
     *   $jkcIcon       icon name, a key of $jkcIcons below
     *   $jkcIconClass  classes of the icon element (size, colour, transitions)
     *   $jkcIconStroke stroke width of an outline icon, '' keeps the icon default
     *   $jkcIconAttrs  extra raw attributes, e.g. an Alpine binding, '' for none
     *
     * 'solid' icons are filled 20x20 glyphs ('evenodd' => true adds the evenodd fill rule),
     * the others are 24x24 outlines.
     * Add an icon = add one entry here.
     */
    $jkcIcons = [
        'eye' => [
            'solid' => false,
            'stroke' => '2',
            'paths' => [
                'M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
            ],
        ],
        'pencil' => [
            'solid' => false,
            'stroke' => '2',
            'paths' => [
                'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
            ],
        ],
        'trash' => [
            'solid' => false,
            'stroke' => '2',
            'paths' => [
                'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
            ],
        ],
        'check' => [
            'solid' => false,
            'stroke' => '3',
            'paths' => [
                'M5 13l4 4L19 7',
            ],
        ],
        'inbox' => [
            'solid' => false,
            'stroke' => '1.5',
            'paths' => [
                'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4',
            ],
        ],
        'search' => [
            'solid' => false,
            'stroke' => '2',
            'paths' => [
                'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
            ],
        ],
        'chevron-down' => [
            'solid' => false,
            'stroke' => '2',
            'paths' => [
                'M19 9l-7 7-7-7',
            ],
        ],
        'x-mini' => [
            'solid' => true,
            'evenodd' => false,
            'paths' => [
                'M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z',
            ],
        ],
        'chevron-left-mini' => [
            'solid' => true,
            'evenodd' => true,
            'paths' => [
                'M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z',
            ],
        ],
        'chevron-right-mini' => [
            'solid' => true,
            'evenodd' => true,
            'paths' => [
                'M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z',
            ],
        ],
        'check-circle-mini' => [
            'solid' => true,
            'evenodd' => true,
            'paths' => [
                'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
            ],
        ],
        'x-circle-mini' => [
            'solid' => true,
            'evenodd' => true,
            'paths' => [
                'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z',
            ],
        ],
        'external-link' => [
            'solid' => false,
            'stroke' => '2',
            'paths' => [
                'M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14',
            ],
        ],
        'chevron-up-mini' => [
            'solid' => true,
            'evenodd' => true,
            'paths' => [
                'M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z',
            ],
        ],
        'chevron-down-mini' => [
            'solid' => true,
            'evenodd' => true,
            'paths' => [
                'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z',
            ],
        ],
    ];

    $jkcIconDef = $jkcIcons[$jkcIcon ?? ''] ?? null;
    $jkcIconSolid = $jkcIconDef['solid'] ?? false;
    $jkcIconEvenOdd = $jkcIconDef['evenodd'] ?? false;
    $jkcIconWidth = ($jkcIconStroke ?? '') !== '' ? $jkcIconStroke : ($jkcIconDef['stroke'] ?? '2');
@endphp
@if ($jkcIconDef !== null)
    @if ($jkcIconSolid)
        <svg xmlns="http://www.w3.org/2000/svg" class="{{ $jkcIconClass ?? '' }}" viewBox="0 0 20 20" fill="currentColor" {!! $jkcIconAttrs ?? '' !!}>
    @else
        <svg xmlns="http://www.w3.org/2000/svg" class="{{ $jkcIconClass ?? '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $jkcIconWidth }}" {!! $jkcIconAttrs ?? '' !!}>
    @endif
        @foreach ($jkcIconDef['paths'] as $jkcIconPath)
            @if ($jkcIconSolid && $jkcIconEvenOdd)
                <path fill-rule="evenodd" d="{{ $jkcIconPath }}" clip-rule="evenodd" />
            @elseif ($jkcIconSolid)
                <path d="{{ $jkcIconPath }}" />
            @else
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $jkcIconPath }}" />
            @endif
        @endforeach
    </svg>
@endif