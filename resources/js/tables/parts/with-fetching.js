/**
 * Loading rows, with a guard against out-of-order responses.
 */
import { checked } from "../helpers.js";

export function withFetching() {
    return {
        rows: [],
        total: 0,
        loading: false,
        errorMessage: "",
        lastRequestId: 0,

        init() {
            this.fetchData();
        },

        requestHeaders() {
            const csrf = document.querySelector(
                "meta[name=csrf-token]",
            )?.content;
            return {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                ...(csrf && { "X-CSRF-TOKEN": csrf }),
            };
        },

        applyResponse(json) {
            this.rows = json.data ?? [];
            this.total = json.total ?? 0;
            this.currentPage = json.current_page ?? 1;
            this.lastPage = json.last_page ?? 1;
        },

        async fetchData() {
            const id = ++this.lastRequestId;
            const isLatest = () => id === this.lastRequestId;
            this.loading = true;
            this.errorMessage = "";

            try {
                const response = checked(
                    await fetch(this.endpoint + "?" + this.buildParams(), {
                        headers: this.requestHeaders(),
                    }),
                );
                const json = await response.json();
                if (isLatest()) {
                    this.applyResponse(json);
                    this.processLinks();
                }
            } catch (error) {
                if (isLatest()) this.errorMessage = "Failed to load the data";
            } finally {
                if (isLatest()) this.loading = false;
            }
        },

        /** Lets htmx see the links of the rows Alpine has just rendered. */
        processLinks() {
            if (typeof window.htmx?.process === "function")
                this.$nextTick(() => window.htmx.process(this.$root));
        },
    };
}
