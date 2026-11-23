@php
    use Juksgraphic\BladeComponents\Components\Cards\Card;
    use Juksgraphic\BladeComponents\Components\Cards\CardStyles;

   
    $resolvedBody    = $body ?? ($slot ?? '');
    $hasHeaderAction = isset($headerAction);
    $footerContent   = $footer ?? null;

    $card = new Card([
        'title'     => $title     ?? null,
        'subtitle'  => $subtitle  ?? null,
        'noPadding' => $noPadding ?? false,
        'class'     => $class     ?? '',
        'body'      => $resolvedBody,
    ]);

    $styles = new CardStyles($card);
@endphp

<!-- Root card container -->
<div class="{{ $styles->container() }}">

    <!-- Card header section -->
    @if ($card->hasHeader($hasHeaderAction))
        <div class="{{ $styles->header() }}">

            <!-- Title and optional subtitle -->
            <div class="min-w-0">
                @if ($card->title() !== null)
                    <h5 class="{{ $styles->title() }}">
                        {{ $card->title() }}
                    </h5>
                @endif

                @if ($card->subtitle() !== null)
                    <p class="{{ $styles->subtitle() }}">
                        {{ $card->subtitle() }}
                    </p>
                @endif
            </div>

            <!-- Right-aligned header action slot -->
            @if ($hasHeaderAction)
                <div class="{{ $styles->headerAction() }}">{!! $headerAction !!}</div>
            @endif

        </div>
    @endif

    <!-- Card body section -->
    @if ($card->hasBody())
        <div class="{{ $styles->body() }}">{!! $card->body() !!}</div>
    @endif

    <!-- Card footer section -->
    @if ($card->hasFooter($footerContent))
        <div class="{{ $styles->footer() }}">{!! $footerContent !!}</div>
    @endif

</div>
