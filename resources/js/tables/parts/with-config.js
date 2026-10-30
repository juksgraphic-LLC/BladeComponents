/**
 * Configuration read from `data-config`; PHP stays the source of defaults.
 */

export function withConfig() {
    return {
        tableId: "",
        baseRoute: "",
        deleteModal: "",
        endpoint: "",
        headers: [],
        filters: [],
        withAction: [],
        selectable: false,
        perPage: 0,
        perPageOptions: [],

        init() {
            const data = this.$el.dataset;
            Object.assign(this, JSON.parse(data.config));
            this.tableId = data.jkcTableId ?? "";
            this.baseRoute = data.jkcBaseRoute ?? "";
            this.deleteModal = data.jkcDeleteModal ?? "";
        },

        /** True when rows link to `baseRoute/id` and can be deleted there. */
        canNavigate() {
            return this.baseRoute !== "";
        },
        rowUrl(item) {
            return this.baseRoute + "/" + encodeURIComponent(item.id ?? "");
        },
        editUrl(item) {
            return this.rowUrl(item) + "/edit";
        },
    };
}
