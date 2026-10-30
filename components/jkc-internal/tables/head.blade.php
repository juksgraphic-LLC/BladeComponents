{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
<thead
    class="text-xs text-on-surface/50 dark:text-on-surface-dark/50 uppercase border-b border-outline dark:border-outline-dark">
    <tr>
        <template x-if="selectable">
            <th scope="col" class="px-4 py-3 w-10 text-center">
                <div x-on:click="toggleSelectAll()"
                    :class="isAllSelected() ?
                        'border-primary bg-primary dark:border-primary-dark dark:bg-primary-dark' :
                        'border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt'"
                    class="flex h-5 w-5 mx-auto shrink-0 items-center justify-center rounded border transition-all duration-150 cursor-pointer group hover:border-primary dark:hover:border-primary-dark">
                    <svg :class="isAllSelected() ? 'opacity-100' : 'opacity-0'"
                        class="h-3.5 w-3.5 text-white transition-opacity duration-150" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </th>
        </template>

        {{-- DYNAMIC HEADERS --}}
        <template x-for="column in headers" :key="column.key">
            <th scope="col" class="px-6 py-3 font-semibold tracking-wider whitespace-nowrap">
                <template x-if="column.sortable">
                    <button type="button" x-on:click="sortBy(column.key)"
                        class="flex items-center gap-1.5 hover:text-primary transition-colors focus:outline-none uppercase tracking-wider">
                        <span x-text="column.label"></span>
                        <span class="flex flex-col -space-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5"
                                :class="sortKey === column.key && sortDir === 'asc' ? 'text-primary' :
                                    'opacity-30'"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5"
                                :class="sortKey === column.key && sortDir === 'desc' ? 'text-primary' :
                                    'opacity-30'"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>
                </template>
                <template x-if="!column.sortable">
                    <span x-text="column.label"></span>
                </template>
            </th>
        </template>

        {{-- ACTIONS COLUMN --}}
        <template x-if="withAction.length > 0">
            <th scope="col" class="px-6 py-3 font-semibold tracking-wider text-right whitespace-nowrap">Actions
            </th>
        </template>
    </tr>
</thead>
