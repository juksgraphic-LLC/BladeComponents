<?php

use eftec\bladeone\BladeOne;
use Juksgraphic\BladeComponents\BladeComponents;

// Scripts are inlined once per process: start every test from a clean state.
beforeEach(fn () => BladeComponents::resetScripts());
afterEach(fn () => BladeComponents::resetScripts());

/**
 * Safety net for `jkc.tables.dynamic-table` (step 0 of the refactoring).
 *
 * These tests freeze the PHP-side contract of the component: input variables
 * are normalized into the JSON stored in the `data-config` attribute. They
 * assert on the *decoded* JSON, never on a raw string, so that later steps
 * (JS extraction, partials, cells) can change the markup freely as long as
 * the config stays the same.
 */
function renderDynamicTable(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

    return $blade->run('jkc.tables.table', $data);
}

/**
 * Returns the raw (still HTML-escaped) value of the `data-config` attribute.
 */
function dynamicTableRawConfig(string $html): string
{
    preg_match('/data-config="([^"]*)"/', $html, $matches);

    return $matches[1] ?? '';
}

/**
 * Renders the component and returns its decoded `data-config` JSON.
 *
 * @return array<string, mixed>
 */
function dynamicTableConfig(array $data = []): array
{
    $raw = dynamicTableRawConfig(renderDynamicTable($data));

    $config = json_decode(html_entity_decode($raw, ENT_QUOTES, 'UTF-8'), true);

    return is_array($config) ? $config : [];
}

/**
 * The labels the component ships with (mirrors TableLabels::DEFAULT_LABELS).
 *
 * @return array<string, string>
 */
function dynamicTableDefaultLabels(): array
{
    return [
        'loadingError' => 'Failed to load the data',
        'deleteError' => 'Failed to delete the item',
        'imagePlaceholder' => '/assets/img/placeholder.png',
        'avatarPlaceholder' => '/assets/img/avatar-placeholder.png',
        'na' => 'N/A',
        'empty' => '-',
    ];
}

/**
 * Offset of the inlined table script, or false when it is absent.
 *
 * Tolerant of minification: `window.JkcTable = {` and `window.JkcTable={` both match.
 */
function dynamicTableScriptOffset(string $html): int|false
{
    return preg_match('/window\.JkcTable\s*=\s*\{/', $html, $matches, PREG_OFFSET_CAPTURE) === 1
        ? $matches[0][1]
        : false;
}

/**
 * Whether the inlined script registers an Alpine component under this name.
 *
 * Tolerant of minification: the receiver of `.data(` may have been renamed
 * (`Alpine.data(`, `e.data(`, ...) and the quotes may be single or double.
 */
function dynamicTableRegistersComponent(string $html, string $name): bool
{
    return preg_match('/\.data\(\s*["\']' . preg_quote($name, '/') . '["\']\s*,/', $html) === 1;
}

// ---------------------------------------------------------------------------
// Defaults and shape of the contract
// ---------------------------------------------------------------------------

it('renders without any variable and falls back to the defaults', function () {
    $config = dynamicTableConfig();

    expect($config)->toBe([
        'endpoint' => '',
        'headers' => [],
        'filters' => [],
        'withAction' => [],
        'selectable' => false,
        'perPage' => 5,
        'perPageOptions' => [5, 10, 25, 50, 100],
        'labels' => dynamicTableDefaultLabels(),
    ]);
});

it('exposes exactly the documented config keys', function () {
    $config = dynamicTableConfig(['baseRoute' => '/admin/posts']);

    expect(array_keys($config))->toBe([
        'endpoint',
        'headers',
        'filters',
        'withAction',
        'selectable',
        'perPage',
        'perPageOptions',
        'labels',
    ]);
});

it('renders the Alpine root with its config attribute', function () {
    $html = renderDynamicTable(['baseRoute' => '/admin/posts']);

    expect($html)->toContain('data-config="')
                 ->toContain('x-data=');
});

// ---------------------------------------------------------------------------
// baseRoute and endpoint
// ---------------------------------------------------------------------------

it('uses the baseRoute as the endpoint when none is given, without appending anything', function () {
    $config = dynamicTableConfig(['baseRoute' => '/admin/posts']);

    expect($config['endpoint'])->toBe('/admin/posts');
});

it('strips the trailing slash of the baseRoute', function () {
    $config = dynamicTableConfig(['baseRoute' => '/admin/posts/']);

    expect($config['endpoint'])->toBe('/admin/posts');
});

it('keeps an explicit endpoint untouched', function () {
    $config = dynamicTableConfig([
        'baseRoute' => '/admin/posts',
        'endpoint' => '/api/posts?scope=all',
    ]);

    expect($config['endpoint'])->toBe('/api/posts?scope=all');
});

it('lets the caller choose any endpoint, whatever its last segment is', function () {
    expect(dynamicTableConfig(['endpoint' => 'admin/users'])['endpoint'])->toBe('admin/users')
        ->and(dynamicTableConfig(['endpoint' => 'admin/table'])['endpoint'])->toBe('admin/table')
        ->and(dynamicTableConfig(['baseRoute' => '/admin', 'endpoint' => '/admin/users'])['endpoint'])->toBe('/admin/users');
});

it('never appends "/table" to the endpoint', function () {
    expect(dynamicTableConfig(['baseRoute' => '/admin/users'])['endpoint'])->not->toEndWith('/table')
        ->and(dynamicTableConfig(['baseRoute' => '/admin/users/'])['endpoint'])->not->toEndWith('/table')
        ->and(dynamicTableConfig()['endpoint'])->not->toContain('table');
});


it('trims the whitespace around the baseRoute and the endpoint', function () {
    expect(dynamicTableConfig(['baseRoute' => '  /admin/posts/ '])['endpoint'])->toBe('/admin/posts')
        ->and(dynamicTableConfig(['endpoint' => ' /api/posts '])['endpoint'])->toBe('/api/posts');
});

it('gives an explicit endpoint priority over the baseRoute', function () {
    $config = dynamicTableConfig(['baseRoute' => '/admin/posts', 'endpoint' => '/api/other']);

    expect($config['endpoint'])->toBe('/api/other');
});

// ---------------------------------------------------------------------------
// headers / columns
// ---------------------------------------------------------------------------

it('accepts the `columns` alias when `headers` is not provided', function () {
    $columns = [['label' => 'Name', 'key' => 'name', 'type' => 'text']];

    $config = dynamicTableConfig(['columns' => $columns]);

    expect($config['headers'])->toBe($columns);
});

it('gives `headers` priority over the `columns` alias', function () {
    $headers = [['label' => 'Title', 'key' => 'title', 'type' => 'text']];
    $columns = [['label' => 'Other', 'key' => 'other', 'type' => 'text']];

    $config = dynamicTableConfig(['headers' => $headers, 'columns' => $columns]);

    expect($config['headers'])->toBe($headers);
});

it('falls back to an empty list when headers is not an array', function () {
    $config = dynamicTableConfig(['headers' => 'not-an-array']);

    expect($config['headers'])->toBe([]);
});

it('passes every column key through without altering it', function () {
    $headers = [
        ['label' => 'Company', 'key' => 'company.name', 'type' => 'text', 'sortable' => true],
        [
            'label' => 'Status',
            'key' => 'status',
            'type' => 'badge',
            'variants' => ['active' => 'success', 'banned' => 'danger'],
            'defaultVariant' => 'neutral',
        ],
        [
            'label' => 'Budget',
            'key' => 'budget',
            'type' => 'currency',
            'currency' => 'EUR',
            'locale' => 'fr-FR',
            'rules' => [['lt' => 100, 'variant' => 'danger'], ['gt' => 1000, 'variant' => 'success']],
        ],
        [
            'label' => 'Member',
            'key' => 'name',
            'type' => 'avatar_text',
            'avatarKey' => 'avatar',
            'subtitleKey' => 'email',
        ],
        ['label' => 'Joined', 'key' => 'created_at', 'type' => 'date', 'dateFormat' => 'short'],
    ];

    $config = dynamicTableConfig(['headers' => $headers]);

    expect($config['headers'])->toBe($headers);
});

// ---------------------------------------------------------------------------
// perPage and perPageOptions
// ---------------------------------------------------------------------------

it('uses 5 rows per page by default', function () {
    $config = dynamicTableConfig();

    expect($config['perPage'])->toBe(5);
});

it('does not duplicate a perPage value that is already an option', function () {
    $config = dynamicTableConfig(['perPage' => 10]);

    expect($config['perPage'])->toBe(10)
                              ->and($config['perPageOptions'])->toBe([5, 10, 25, 50, 100]);
});

it('adds a custom perPage to the options and keeps them sorted', function () {
    $config = dynamicTableConfig(['perPage' => 15]);

    expect($config['perPage'])->toBe(15)
                              ->and($config['perPageOptions'])->toBe([5, 10, 15, 25, 50, 100]);
});

it('enforces a minimum perPage of 1', function () {
    $zero = dynamicTableConfig(['perPage' => 0]);
    $negative = dynamicTableConfig(['perPage' => -7]);

    expect($zero['perPage'])->toBe(1)
                            ->and($zero['perPageOptions'])->toBe([1, 5, 10, 25, 50, 100])
                            ->and($negative['perPage'])->toBe(1);
});

it('casts a numeric string perPage to an integer', function () {
    $config = dynamicTableConfig(['perPage' => '25']);

    expect($config['perPage'])->toBe(25);
});

// ---------------------------------------------------------------------------
// selectable and withAction
// ---------------------------------------------------------------------------

it('is not selectable by default', function () {
    expect(dynamicTableConfig()['selectable'])->toBeFalse();
});

it('casts selectable to a boolean', function () {
    expect(dynamicTableConfig(['selectable' => true])['selectable'])->toBeTrue()
        ->and(dynamicTableConfig(['selectable' => 1])['selectable'])->toBeTrue()
        ->and(dynamicTableConfig(['selectable' => 0])['selectable'])->toBeFalse();
});

it('keeps the enabled row actions in order', function () {
    $config = dynamicTableConfig(['withAction' => ['view', 'edit', 'delete']]);

    expect($config['withAction'])->toBe(['view', 'edit', 'delete']);
});

it('reindexes withAction so it is always encoded as a JSON list', function () {
    $config = dynamicTableConfig(['withAction' => [2 => 'edit', 5 => 'delete']]);

    expect($config['withAction'])->toBe(['edit', 'delete']);
});

it('falls back to no actions when withAction is not an array', function () {
    $config = dynamicTableConfig(['withAction' => 'view']);

    expect($config['withAction'])->toBe([]);
});

// ---------------------------------------------------------------------------
// filters
// ---------------------------------------------------------------------------

it('normalizes filter options given as a value => label map', function () {
    $config = dynamicTableConfig([
        'filters' => [
            [
                'key' => 'status',
                'label' => 'Status',
                'options' => ['active' => 'Active', 'banned' => 'Banned'],
            ],
        ],
    ]);

    expect($config['filters'])->toBe([
        [
            'key' => 'status',
            'label' => 'Status',
            'options' => [
                ['value' => 'active', 'label' => 'Active'],
                ['value' => 'banned', 'label' => 'Banned'],
            ],
        ],
    ]);
});

it('keeps filter options that are already a list of {value, label}', function () {
    $options = [
        ['value' => 'fr', 'label' => 'France'],
        ['value' => 'ht', 'label' => 'Haïti'],
    ];

    $config = dynamicTableConfig([
        'filters' => [['key' => 'country', 'label' => 'Country', 'options' => $options]],
    ]);

    expect($config['filters'][0]['options'])->toBe($options);
});

it('casts numeric map keys of filter options to string values', function () {
    $config = dynamicTableConfig([
        'filters' => [['key' => 'published', 'label' => 'Published', 'options' => [1 => 'Yes', 0 => 'No']]],
    ]);

    expect($config['filters'][0]['options'])->toBe([
        ['value' => '1', 'label' => 'Yes'],
        ['value' => '0', 'label' => 'No'],
    ]);
});

it('gives a filter without options an empty options list', function () {
    $config = dynamicTableConfig([
        'filters' => [['key' => 'status', 'label' => 'Status']],
    ]);

    expect($config['filters'][0]['options'])->toBe([]);
});

it('falls back to no filters when filters is not an array', function () {
    $config = dynamicTableConfig(['filters' => 'status']);

    expect($config['filters'])->toBe([]);
});

// ---------------------------------------------------------------------------
// labels
// ---------------------------------------------------------------------------

it('ships the default labels', function () {
    expect(dynamicTableConfig()['labels'])->toBe(dynamicTableDefaultLabels());
});

it('overrides only the labels it is given and keeps the other defaults', function () {
    $config = dynamicTableConfig(['labels' => ['na' => 'Non disponible', 'empty' => '—']]);

    expect($config['labels'])->toBe(array_merge(dynamicTableDefaultLabels(), [
        'na' => 'Non disponible',
        'empty' => '—',
    ]));
});

it('falls back to the default labels when labels is not an array', function () {
    $config = dynamicTableConfig(['labels' => 'fr']);

    expect($config['labels'])->toBe(dynamicTableDefaultLabels());
});

// ---------------------------------------------------------------------------
// Escaping
// ---------------------------------------------------------------------------

it('never leaks raw HTML characters into the data-config attribute', function () {
    $html = renderDynamicTable([
        'headers' => [['label' => '<script>alert("x")</script> O\'Neil & Co', 'key' => 'name', 'type' => 'text']],
    ]);

    $raw = dynamicTableRawConfig($html);

    expect($raw)->not->toBe('')
                ->and($raw)->not->toContain('<')
                ->and($raw)->not->toContain('>')
                ->and($raw)->not->toContain("'")
                ->and($html)->not->toContain('<script>alert');
});

it('round-trips special characters through the data-config attribute', function () {
    $label = '<b>"Quoted"</b> O\'Neil & Étudiants';

    $config = dynamicTableConfig([
        'headers' => [['label' => $label, 'key' => 'name', 'type' => 'text']],
    ]);

    expect($config['headers'][0]['label'])->toBe($label);
});

// ---------------------------------------------------------------------------
// Real-world call: admin "Posts" page
// ---------------------------------------------------------------------------

it('freezes the config of the real admin Posts table', function () {
    // The page defines `$columns` and passes it through `headers`.
    $columns = [
        ['label' => 'ID', 'key' => 'id', 'type' => 'badge', 'sortable' => true],
        ['label' => 'Cover', 'key' => 'cover', 'type' => 'image', 'sortable' => false],
        ['label' => 'Title', 'key' => 'title', 'type' => 'text', 'sortable' => true],
        ['label' => 'Category', 'key' => 'category', 'type' => 'text', 'sortable' => true],
        ['label' => 'Author', 'key' => 'author_name', 'type' => 'text', 'sortable' => false],
        ['label' => 'Published', 'key' => 'is_published', 'type' => 'badge', 'sortable' => true],
        ['label' => 'Published At', 'key' => 'published_at', 'type' => 'text', 'sortable' => true],
        ['label' => 'Created At', 'key' => 'created_at', 'type' => 'text', 'sortable' => true],
    ];

    // The endpoint is never derived with a "/table" suffix: the page states it.
    $config = dynamicTableConfig([
        'baseRoute' => '/admin/posts',
        'endpoint' => '/admin/posts/table',
        'headers' => $columns,
        'withAction' => ['view', 'edit', 'delete'],
        'perPage' => 10,
    ]);

    expect($config)->toBe([
        'endpoint' => '/admin/posts/table',
        'headers' => $columns,
        'filters' => [],
        'withAction' => ['view', 'edit', 'delete'],
        'selectable' => false,
        'perPage' => 10,
        'perPageOptions' => [5, 10, 25, 50, 100],
        'labels' => dynamicTableDefaultLabels(),
    ]);
});

// ---------------------------------------------------------------------------
// Step 1: Alpine code lives in resources/js/jkc-table.js
// ---------------------------------------------------------------------------

it('inlines the table script that registers the Alpine components', function () {
    $html = renderDynamicTable(['baseRoute' => '/admin/posts']);

    // The bundle in dist/ may be minified: assert on the registration, not on the formatting.
    expect($html)->toContain('window.JkcTable')
                 ->and(dynamicTableRegistersComponent($html, 'jkcTable'))->toBeTrue()
                 ->and(dynamicTableRegistersComponent($html, 'jkcTableBody'))->toBeTrue();
});

it('places the script before the Alpine root so the components exist when it initializes', function () {
    $html = renderDynamicTable(['baseRoute' => '/admin/posts']);

    expect(strpos($html, 'window.JkcTable'))->not->toBeFalse()
        ->and(strpos($html, 'window.JkcTable'))->toBeLessThan(strpos($html, 'data-config="'));
});

it('only references registered components in x-data attributes', function () {
    $html = renderDynamicTable(['baseRoute' => '/admin/posts']);

    preg_match_all('/x-data="([^"]*)"/', $html, $matches);

    $allowed = ['jkcTable', 'jkcTableFilter(filter)', 'jkcTablePerPage', 'jkcTableBody', 'jkcTablePagination', '{}'];

    expect(array_diff(array_unique($matches[1]), $allowed))->toBe([]);
});

// ---------------------------------------------------------------------------
// Step 2: the script is delivered once per page, without any @stack
// ---------------------------------------------------------------------------

/**
 * Renders a page that includes the table twice.
 */
function renderTwoDynamicTables(): string
{
    $views = [__DIR__ . '/../../components', __DIR__ . '/../Fixtures/views'];
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

    return $blade->run('two-tables');
}

it('inlines the script only once when a page contains two tables', function () {
    $html = renderTwoDynamicTables();

    expect(preg_match_all('/window\.JkcTable\s*=\s*\{/', $html))->toBe(1)
        ->and(substr_count($html, 'data-config="'))->toBe(2);
});

it('keeps the single script before the first table', function () {
    $html = renderTwoDynamicTables();

    // `false < int` is true in PHP: make sure the script is really there before comparing offsets.
    expect(dynamicTableScriptOffset($html))->not->toBeFalse()
        ->and(dynamicTableScriptOffset($html))->toBeLessThan(strpos($html, 'data-config="'));
});

it('does not inline the script when a bundler loads it', function () {
    BladeComponents::useBundledScripts();

    $html = renderDynamicTable(['baseRoute' => '/admin/posts']);

    expect($html)->toContain('x-data="jkcTable"')
                 ->and($html)->not->toContain('window.JkcTable')
                 ->and(dynamicTableConfig(['baseRoute' => '/admin/posts'])['endpoint'])->toBe('/admin/posts');
});

it('does not require a stack in the layout', function () {
    expect(renderDynamicTable(['baseRoute' => '/admin/posts']))->not->toContain('@stack');
});

it('inlines the script again after a reset', function () {
    renderDynamicTable();
    $afterFirst = renderDynamicTable();

    BladeComponents::resetScripts();
    $afterReset = renderDynamicTable();

    expect($afterFirst)->not->toContain('window.JkcTable')
                       ->and($afterReset)->toContain('window.JkcTable');
});

// ---------------------------------------------------------------------------
// Step 3: normalization lives in TableConfig, the view exposes the table id
// ---------------------------------------------------------------------------

/**
 * Returns the value of the `data-jkc-table-id` attribute of the rendered table.
 */
function dynamicTableId(array $data = []): string
{
    preg_match('/data-jkc-table-id="([^"]*)"/', renderDynamicTable($data), $matches);

    return html_entity_decode($matches[1] ?? '', ENT_QUOTES, 'UTF-8');
}

it('exposes the table id as an attribute, outside of the Alpine config', function () {
    $html = renderDynamicTable(['baseRoute' => '/admin/posts', 'tableId' => 'posts']);

    expect($html)->toContain('data-jkc-table-id="posts"')
                 ->and(dynamicTableConfig(['tableId' => 'posts']))->not->toHaveKey('tableId');
});

it('derives a stable table id from the endpoint', function () {
    expect(dynamicTableId(['baseRoute' => '/admin/posts']))->toMatch('/^jkc-table-[0-9a-f]{8}$/')
        ->and(dynamicTableId(['baseRoute' => '/admin/posts']))->toBe(dynamicTableId(['baseRoute' => '/admin/posts']))
        ->and(dynamicTableId(['baseRoute' => '/admin/users']))->not->toBe(dynamicTableId(['baseRoute' => '/admin/posts']));
});

it('derives the table id from the resolved endpoint, so two endpoints give two ids', function () {
    expect(dynamicTableId(['endpoint' => '/admin/users']))->not->toBe(dynamicTableId(['endpoint' => '/admin/table']))
        ->and(dynamicTableId(['baseRoute' => '/admin/posts/']))->toBe(dynamicTableId(['baseRoute' => '/admin/posts']))
        ->and(dynamicTableId(['baseRoute' => '/admin/posts']))->toBe(dynamicTableId(['endpoint' => '/admin/posts']));
});

it('does not capture an `$id` variable inherited from the page scope', function () {
    expect(dynamicTableId(['baseRoute' => '/admin/posts', 'id' => 99]))->toMatch('/^jkc-table-[0-9a-f]{8}$/');
});

it('escapes the table id in the attribute', function () {
    $html = renderDynamicTable(['tableId' => 'a"><script>x</script>']);

    expect($html)->not->toContain('a"><script>x');
});