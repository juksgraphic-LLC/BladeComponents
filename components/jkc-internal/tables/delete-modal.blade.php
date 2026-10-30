{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
{{--
    Delete confirmation, built on the jkc.modals.modal component. Everything the modal reads is passed
    explicitly (@include inherits the scope of the page, so a page $header, $body or $footer must not leak in).
    The modal sits inside the table root, so its footer reaches the table methods through the Alpine scope chain.
--}}
@component('jkc.modals.modal', [
    'id' => $jkcTable->deleteModalId(),
    'title' => 'Confirm deletion',
    'size' => 'sm',
    'position' => null,
    'centered' => true,
    'static' => false,
    'scrollable' => false,
    'animation' => 'fade',
    'showClose' => true,
    'header' => null,
])
    @slot('body')
        <p class="text-sm text-on-surface dark:text-on-surface-dark">Are you sure you want to delete this item?</p>
        <p x-show="deleteError" x-text="deleteError" role="alert" class="mt-3 text-sm text-rose-600 dark:text-rose-400"></p>
    @endslot
    @slot('footer')
        <button type="button" x-on:click="closeDeleteModal()" :disabled="deleting"
            class="px-4 py-2 text-sm font-medium text-on-surface dark:text-on-surface-dark bg-surface-alt dark:bg-surface-dark-alt hover:bg-surface dark:hover:bg-surface-dark border border-outline dark:border-outline-dark rounded-radius transition-colors disabled:opacity-50">
            Cancel
        </button>
        <button type="button" x-on:click="confirmDelete()" :disabled="deleting"
            class="px-4 py-2 text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 rounded-radius transition-colors disabled:opacity-50">
            <span x-text="deleting ? 'Deleting…' : 'Delete'"></span>
        </button>
    @endslot
@endcomponent
