/**
 * Table events and commands received from outside.
 */

export function withCommands() {
    return {
        /** Cancelable event whose `detail` starts with `tableId`; false if cancelled. */
        emit(name, detail) {
            const event = new CustomEvent(name, {
                bubbles: true,
                cancelable: true,
                detail: { tableId: this.tableId, ...detail },
            });
            this.$el.dispatchEvent(event);
            return !event.defaultPrevented;
        },

        /** True when the command targets this table (no target = all tables). */
        isTargeted(target) {
            return !target || target === this.tableId;
        },

        /** `jkc-table-refresh`: reloads with the same page, search, sort, filters. */
        refresh(event) {
            if (this.isTargeted(event.detail)) this.fetchData();
        },

        /** `jkc-table-reset-filters`: clears filters, back to page 1 (if any applied). */
        resetFilters(event) {
            if (!this.isTargeted(event?.detail)) return;
            if (!Object.values(this.activeFilters).some(Boolean)) return;
            this.activeFilters = {};
            this.resetAndFetch();
        },
    };
}