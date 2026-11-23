/** Minimum options count before the dropdown shows its search field. */
export const SEARCH_THRESHOLD = 8;

/** Name of the DOM event a dropdown dispatches on every change. */
export const CHANGE_EVENT = 'filter-select-change';

/** Default UI texts (overridable through the `texts` config key). */
export const DEFAULT_TEXTS = Object.freeze({
    search: 'Rechercher…',
    empty: 'Aucun résultat',
    clear: 'Effacer',
});

/** Keyboard keys handled by the dropdown. */
export const KEYS = Object.freeze({
    HOME: 'Home',
    END: 'End',
});