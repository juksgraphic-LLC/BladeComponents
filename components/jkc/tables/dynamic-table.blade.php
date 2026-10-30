@php
    /**
     * Data Table Component (Alpine.js + fetch) — Juksgraphic / Penguin UI
     *
     * @param string               $baseRoute       Base URL of the resource. Default: ''.
     * @param string               $endpoint        JSON endpoint returning paginated rows. Default: "{baseRoute}/table".
     * @param array<int, array>    $headers         Columns [key, label, type, sortable, variants]. Alias: $columns. Default: [].
     * @param array<int, array>    $filters         Filters [key, label, options]. Options: [value => label]. Default: [].
     * @param array<int, string>   $withAction      Enabled row actions: view, edit, delete. Default: [].
     * @param bool                 $selectable      Enable checkbox selection column for bulk actions. Default: false.
     * @param int                  $perPage         Initial rows per page. Default: 5.
     * @param string               $tableId         Identifier of this table. Default: "jkc-table-" + hash of the endpoint.
     */

    
    $jkcTable = \Juksgraphic\BladeComponents\Table\TableConfig::fromInput([
        'baseRoute'  => $baseRoute  ?? null,
        'endpoint'   => $endpoint   ?? null,
        'headers'    => $headers    ?? null,
        'columns'    => $columns    ?? null,
        'filters'    => $filters    ?? null,
        'withAction' => $withAction ?? null,
        'selectable' => $selectable ?? null,
        'perPage'    => $perPage    ?? null,
        'tableId'    => $tableId    ?? null,
    ]);

    /** 
     * Alpine components of the table (resources/js/jkc-table.js):
     * inlined once per page, unless a bundler loads them.
    */
    $jkcTableScript = \Juksgraphic\BladeComponents\BladeComponents::shouldInline('jkc-table')
        ? (string) file_get_contents(\Juksgraphic\BladeComponents\BladeComponents::script('jkc-table'))
        : '';
@endphp

@if ($jkcTableScript !== '')
    <script>
        {!! $jkcTableScript !!}
    </script>
@endif

<div data-config="{{ $jkcTable->toJson() }}" data-jkc-table-id="{{ $jkcTable->id() }}"
    data-jkc-base-route="{{ $jkcTable->getBaseRoute() }}"
    class="flex flex-col gap-4 min-w-0 w-full" x-data="jkcTable">

    @include('jkc-internal.tables.toolbar')

    @include('jkc-internal.tables.error')

    <div class="w-full min-w-0 rounded-radius border border-outline
       dark:border-outline-dark bg-surface
       dark:bg-surface-dark overflow-hidden"
        x-data="jkcTableBody">
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="w-full text-left border-collapse">

                @include('jkc-internal.tables.head')

                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                    <template x-for="(item, index) in rows" :key="item.id ?? index">
                        @include('jkc-internal.tables.row' ,['jkcTable' => $jkcTable])
                    </template>
                </tbody>
            </table>
        </div>

        @include('jkc-internal.tables.empty')
    </div>

    @include('jkc-internal.tables.pagination')

</div>