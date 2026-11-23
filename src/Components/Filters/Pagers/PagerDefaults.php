<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Pagers;

/**
 * Default pager values.
 */
final class PagerDefaults
{
    /** Default values by name. */
    public const ITEMS = [
        'PER_PAGE_OPTIONS' => [10, 25, 50],
        'SIBLINGS'         => 2,
        'SUMMARY'          => true,
        'PER_PAGE'         => true,
        'PAGES'            => true,
    ];

    /** Default texts by key. {name} marks a placeholder. */
    public const TEXTS = [
        'summary'  => 'Affichage de {from} à {to} sur {total} résultats',
        'per_page' => 'Lignes par page :',
        'nav'      => 'Pagination',
        'previous' => 'Précédent',
        'next'     => 'Suivant',
        'page'     => 'Page {page}',
    ];
}
