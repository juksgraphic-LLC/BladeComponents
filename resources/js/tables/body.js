/**
 * Table body: selection and row actions.
 */

export function body() {
    return {
        selectedIds: [],

        init() {
            this.$watch("rows", () => this.clearSelection());
        },

        clearSelection() {
            if (this.selectedIds.length === 0) return;
            this.selectedIds = [];
            this.dispatchSelectionChange();
        },

        isAllSelected() {
            return (
                this.rows.length > 0 &&
                this.selectedIds.length === this.rows.length
            );
        },

        toggleSelectAll() {
            this.selectedIds = this.isAllSelected()
                ? []
                : this.rows.map((row) => row.id).filter(Boolean);
            this.dispatchSelectionChange();
        },

        toggleSelectRow(id) {
            this.selectedIds = this.selectedIds.includes(id)
                ? this.selectedIds.filter((item) => item !== id)
                : [...this.selectedIds, id];
            this.dispatchSelectionChange();
        },

        getSelectedItems() {
            return this.rows.filter((row) => this.selectedIds.includes(row.id));
        },

        dispatchSelectionChange() {
            this.emit("jkc-table-selection-change", {
                selectedIds: this.selectedIds,
                selectedItems: this.getSelectedItems(),
            });
        },

        follow(clickEvent, name, item) {
            if (this.emit("jkc-table-" + name, { item })) return;
            clickEvent.preventDefault();
            clickEvent.stopImmediatePropagation();
        },

        hasAction(name) {
            return this.withAction.includes(name);
        },
        /** Emits the action event; false if the action is off or cancelled. */
        trigger(name, item) {
            return (
                this.hasAction(name) && this.emit("jkc-table-" + name, { item })
            );
        },
        triggerView(item) {
            this.trigger("view", item);
        },
        triggerEdit(item) {
            this.trigger("edit", item);
        },
        triggerDelete(item) {
            if (this.trigger("delete", item) && this.canNavigate())
                this.askDelete(item);
        },
    };
}
