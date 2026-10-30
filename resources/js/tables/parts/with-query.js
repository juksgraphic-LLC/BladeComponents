/**
 * Search, sort and filters. Needs: fetchData(), currentPage.
 */

export function withQuery() {
    return {
        search: "",
        activeFilters: {},
        sortKey: "",
        sortDir: "asc",

        buildParams() {
            const params = new URLSearchParams({
                page: this.currentPage,
                per_page: this.perPage,
            });
            const search = this.search.trim();
            if (search) params.append("search", search);
            if (this.sortKey) {
                params.append("sort", this.sortKey);
                params.append("direction", this.sortDir);
            }
            Object.entries(this.activeFilters).forEach(
                ([key, value]) =>
                    value && params.append("filters[" + key + "]", value),
            );
            return params.toString();
        },

        resetAndFetch() {
            this.currentPage = 1;
            this.fetchData();
        },
        setFilter(key, value) {
            this.activeFilters[key] = value;
            this.resetAndFetch();
        },
        sortBy(key) {
            const sameKey = this.sortKey === key;
            this.sortDir  = sameKey && this.sortDir === "asc" ? "desc" : "asc";
            this.sortKey  = key;
            this.resetAndFetch();
        },
    };
}
