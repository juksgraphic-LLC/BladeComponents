{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
@php
    $jkcCheckAttrs =    ':class="isAllSelected() ? \'opacity-100\' : \'opacity-0\'"';
    $jkcSortAscAttrs =  ':class="sortKey === column.key && sortDir === \'asc\' ? \'text-primary\' : \'opacity-30\'"';
    $jkcSortDescAttrs = ':class="sortKey === column.key && sortDir === \'desc\' ? \'text-primary\' : \'opacity-30\'"';
@endphp

<thead
    class="text-xs text-on-surface/50 dark:text-on-surface-dark/50 uppercase border-b border-outline dark:border-outline-dark">
    <tr>
        @if ($jkcTable->selectable())
            <th scope="col" class="px-4 py-3 w-10 text-center">
                <div x-on:click="toggleSelectAll()"
                    :class="isAllSelected() ?
                        'border-primary bg-primary dark:border-primary-dark dark:bg-primary-dark' :
                        'border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt'"
                    class="flex h-5 w-5 mx-auto shrink-0 items-center justify-center rounded border transition-all duration-150 cursor-pointer group hover:border-primary dark:hover:border-primary-dark">
                    @include('jkc-internal.tables.icon', [
                        'jkcIcon' => 'check',
                        'jkcIconClass' => 'h-3.5 w-3.5 text-white transition-opacity duration-150',
                        'jkcIconStroke' => '',
                        'jkcIconAttrs' => $jkcCheckAttrs,
                    ])
                </div>
            </th>
        @endif

        <template x-for="column in headers" :key="column.key">
            <th scope="col" class="px-6 py-3 font-semibold tracking-wider whitespace-nowrap">
                <template x-if="column.sortable">
                    <button type="button" x-on:click="sortBy(column.key)"
                        class="flex items-center gap-1.5 hover:text-primary transition-colors focus:outline-none uppercase tracking-wider">
                        <span x-text="column.label"></span>
                        <span class="flex flex-col -space-y-1">
                            @include('jkc-internal.tables.icon', [
                                'jkcIcon' => 'chevron-up-mini',
                                'jkcIconClass' => 'size-2.5',
                                'jkcIconStroke' => '',
                                'jkcIconAttrs' => $jkcSortAscAttrs,
                            ])
                            @include('jkc-internal.tables.icon', [
                                'jkcIcon' => 'chevron-down-mini',
                                'jkcIconClass' => 'size-2.5',
                                'jkcIconStroke' => '',
                                'jkcIconAttrs' => $jkcSortDescAttrs,
                            ])
                        </span>
                    </button>
                </template>
                <template x-if="!column.sortable">
                    <span x-text="column.label"></span>
                </template>
            </th>
        </template>


        @if (!empty($jkcTable->toArray()['withAction']))
            <th scope="col" class="px-6 py-3 font-semibold tracking-wider text-right whitespace-nowrap">Actions
            </th>
        @endif
    </tr>
</thead>
