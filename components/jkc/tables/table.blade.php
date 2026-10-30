@if (\Juksgraphic\BladeComponents\BladeComponents::shouldInline('jkc-table'))
    <script>{!! \Juksgraphic\BladeComponents\BladeComponents::inline('jkc-table') !!}</script>
@endif

@php
    use Juksgraphic\BladeComponents\Table\Table;

    $jkcTable = new Table();
    $jkcTable->setBaseRoute($baseRoute ?? '')
        ->setHeaders($headers ?? [])
        ->setColumns($columns ?? [])
        ->setFilters($filters ?? [])
        ->setWithAction($withAction ?? [])
        ->setSelectable($selectable ?? false)
        ->setPerPage($perPage ?? 5)
        ->setTableId($tableId ?? '')
        ->setLabels($labels ?? []);

    // The endpoint is whatever the caller defines; Table falls back to the baseRoute when none is given.
    if (is_string($endpoint ?? null) && trim($endpoint) !== '') {
        $jkcTable->setEndpoint($endpoint);
    }
@endphp

<div 
    data-config="{{ $jkcTable->toJson() }}"
    data-jkc-table-id="{{ $jkcTable->id() }}"
    data-jkc-base-route="{{ $jkcTable->getBaseRoute() }}"
    data-jkc-delete-modal="{{ $jkcTable->deleteModalId() }}"
    x-on:jkc-on-close-modal="onDeleteModalClosed($event)"
    x-on:jkc-table-refresh.window="refresh($event)"
    x-on:jkc-table-reset-filters.window="resetFilters($event)"
    class="flex flex-col gap-4 min-w-0 w-full"
    x-data="jkcTable">

    @include('jkc-internal.tables.toolbar')

    @include('jkc-internal.tables.error')

    <div class="w-full min-w-0 rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark overflow-hidden"
        x-data="jkcTableBody">
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="w-full text-left border-collapse">

                @include('jkc-internal.tables.head' , ['jkcTable' => $jkcTable])

                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                    <template x-for="(item, index) in rows" :key="item.id ?? index">
                        @include('jkc-internal.tables.row' , ['jkcTable' => $jkcTable])
                    </template>
                </tbody>
            </table>
        </div>

        @include('jkc-internal.tables.empty')
    </div>

    @include('jkc-internal.tables.pagination')

    @if (
        $jkcTable->hasAction('delete') && 
        $jkcTable->getBaseRoute() !== '')
        @include('jkc-internal.tables.delete-modal')
    @endif


</div>