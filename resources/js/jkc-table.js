/**
 * Juksgraphic — dynamic table (Alpine.js components).
 *
 * Classic script (no import/export) so it can be inlined by the Blade view
 * or loaded by a bundler. Everything is exposed under `window.JkcTable`.
 *
 * Registration is idempotent and works whether Alpine is already running
 * (htmx body swap) or has not started yet (`alpine:init`).
 *
 * Tailwind note: class names below are written in full on purpose, never
 * concatenated, so that the Tailwind scanner can find them.
 */
(function () {
    "use strict";

    if (window.JkcTable) return;

    /**
     * Single source of the color variants shared by badge, progress and currency cells:
     * `badge` styles a badge, `bar` a progress bar, `text` an amount. `neutral` has no
     * bar (progress falls back to `primary`). Class names are written in full.
     */
    const VARIANTS = {
        success: {
            badge: "bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20",
            bar:   "bg-emerald-500",
            text:  "text-emerald-600 dark:text-emerald-400 font-semibold",
        },
        warning: {
            badge: "bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20",
            bar:   "bg-amber-500",
            text:  "text-amber-600 dark:text-amber-400 font-semibold",
        },
        danger: {
            badge: "bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20",
            bar:   "bg-rose-500",
            text:  "text-rose-600 dark:text-rose-400 font-semibold",
        },
        primary: {
            badge: "bg-primary/10 text-primary border-primary/20",
            bar:   "bg-primary",
            text:  "text-primary font-semibold",
        },
        info: {
            badge: "bg-sky-500/10 text-sky-600 dark:text-sky-400 border-sky-500/20",
            bar:   "bg-sky-500",
            text:  "text-sky-600 dark:text-sky-400 font-semibold",
        },
        neutral: {
            badge: "bg-surface-alt text-on-surface/70 dark:bg-surface-dark-alt dark:text-on-surface-dark/70 border-outline dark:border-outline-dark",
            text:  "text-on-surface dark:text-on-surface-dark font-semibold",
        },
    };

    /**
     * The variant called `name`, or the `fallback` one when it does not exist.
     */
    function variant(name, fallback) {
        return Object.prototype.hasOwnProperty.call(VARIANTS, name)
            ? VARIANTS[name]
            : VARIANTS[fallback];
    }

    const numberFormats = new Map();
    const dateFormats = new Map();

    /**
     * Currency formatter, created once per locale and currency.
     */
    function numberFormat(locale, currency) {
        const key = locale + "|" + currency;
        if (!numberFormats.has(key)) {
            numberFormats.set(
                key,
                new Intl.NumberFormat(locale, { style: "currency", currency }),
            );
        }
        return numberFormats.get(key);
    }

    /**
     * Date formatter, created once per `dateStyle`.
     */
    function dateFormat(style) {
        if (!dateFormats.has(style)) {
            dateFormats.set(
                style,
                new Intl.DateTimeFormat("fr-FR", { dateStyle: style }),
            );
        }
        return dateFormats.get(style);
    }

    /**
     * Root component: state, fetching, sorting, filtering, pagination.
     * The configuration is read from the `data-config` attribute; PHP is the
     * single source of defaults, the values below are only structural
     * placeholders that are always overwritten by `applyConfig()`.
     */
    function table() {
        return {
            // Config (overwritten by data-config)
            baseRoute: "",
            endpoint: "",
            headers: [],
            filters: [],
            withAction: [],
            selectable: false,
            perPage: 0,
            perPageOptions: [],

            // State
            rows: [],
            total: 0,
            currentPage: 1,
            lastPage: 1,
            search: "",
            activeFilters: {},
            sortKey: "",
            sortDir: "asc",
            loading: false,
            errorMessage: "",
            lastRequestId: 0,

            init() {
                this.applyConfig();
                this.fetchData();
            },

            applyConfig() {
                Object.assign(this, JSON.parse(this.$el.dataset.config));
                this.baseRoute = this.$el.dataset.jkcBaseRoute ?? "";
            },

            requestHeaders() {
                const csrfToken = document.querySelector(
                    "meta[name=csrf-token]",
                )?.content;
                return {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
                };
            },

            buildParams() {
                const params = new URLSearchParams({
                    page: this.currentPage,
                    per_page: this.perPage,
                });
                if (this.search.trim())
                    params.append("search", this.search.trim());
                if (this.sortKey) {
                    params.append("sort", this.sortKey);
                    params.append("direction", this.sortDir);
                }
                Object.entries(this.activeFilters).forEach(([key, value]) => {
                    if (value) params.append("filters[" + key + "]", value);
                });
                return params.toString();
            },

            applyResponse(json) {
                this.rows = json.data ?? [];
                this.total = json.total ?? 0;
                this.currentPage = json.current_page ?? 1;
                this.lastPage = json.last_page ?? 1;
            },

            async fetchData() {
                const requestId = ++this.lastRequestId;
                this.loading = true;
                this.errorMessage = "";

                try {
                    const response = await fetch(
                        this.endpoint + "?" + this.buildParams(),
                        { headers: this.requestHeaders() },
                    );
                    if (!response.ok)
                        throw new Error("HTTP " + response.status);
                    const json = await response.json();

                    if (requestId === this.lastRequestId) {
                        this.applyResponse(json);
                        this.processLinks();
                    }
                } catch (error) {
                    if (requestId === this.lastRequestId)
                        this.errorMessage = "Failed to load the data";
                } finally {
                    if (requestId === this.lastRequestId) this.loading = false;
                }
            },

            /**
             * Lets htmx see the links of the rows that Alpine has just rendered.
             */
            processLinks() {
                if (window.htmx && typeof window.htmx.process === "function")
                    this.$nextTick(() => window.htmx.process(this.$root));
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
                this.sortDir =
                    this.sortKey === key && this.sortDir === "asc"
                        ? "desc"
                        : "asc";
                this.sortKey = key;
                this.resetAndFetch();
            },

            goToPage(page) {
                const target = parseInt(page);
                if (
                    isNaN(target) ||
                    target < 1 ||
                    target > this.lastPage ||
                    target === this.currentPage
                )
                    return;
                this.currentPage = target;
                this.fetchData();
            },
        };
    }

    /**
     * Local state of one filter dropdown. Reads/writes the root through the
     * Alpine scope chain (`this.setFilter`).
     */
    function filter(definition) {
        return {
            isOpen: false,
            selectedValue: "",
            selectedLabel: definition.label,

            selectOption(value, label) {
                this.selectedValue = value;
                this.selectedLabel = value === "" ? definition.label : label;
                this.isOpen = false;
                this.setFilter(definition.key, value);
            },
            clearSelection() {
                this.selectOption("", definition.label);
            },
        };
    }

    /**
     * Local state of the "rows per page" dropdown.
     */
    function perPage() {
        return {
            isOpen: false,
            selectOption(value) {
                this.perPage = value;
                this.isOpen = false;
                this.resetAndFetch();
            },
        };
    }

    /**
     * Table body: selection, cell formatting and row actions.
     */
    function body() {
        return {
            selectedIds: [],

            safeUrl(url) {
                const text = String(url ?? "").trim();
                return /^(https?:\/\/|\/|#)/i.test(text) ? text : "#";
            },

            getCellValue(item, path) {
                if (!path) return "";
                return (
                    path
                        .split(".")
                        .reduce(
                            (current, key) =>
                                current != null ? current[key] : "",
                            item,
                        ) ?? ""
                );
            },

            getBadgeClasses(value, columnConfig) {
                const name =
                    columnConfig?.variants?.[value] ||
                    columnConfig?.defaultVariant ||
                    "neutral";
                return variant(name, "neutral").badge;
            },

            getProgressClasses(columnConfig) {
                const name = columnConfig?.variant || "primary";
                return variant(name, "primary").bar || VARIANTS.primary.bar;
            },

            formatCurrency(value, columnConfig) {
                const num = parseFloat(value);
                if (isNaN(num))
                    return { text: value, classes: VARIANTS.neutral.text };

                const currency = columnConfig?.currency || "USD";
                const locale = columnConfig?.locale || "en-US";
                const formatted = numberFormat(locale, currency).format(num);

                let name = columnConfig?.variant || "neutral";

                if (columnConfig?.rules && Array.isArray(columnConfig.rules)) {
                    for (const rule of columnConfig.rules) {
                        let matches = true;
                        if (rule.max !== undefined && num > rule.max)
                            matches = false;
                        if (rule.min !== undefined && num < rule.min)
                            matches = false;
                        if (rule.lt !== undefined && num >= rule.lt)
                            matches = false;
                        if (rule.gt !== undefined && num <= rule.gt)
                            matches = false;

                        if (matches) {
                            name = rule.variant;
                            break;
                        }
                    }
                }

                return {
                    text: formatted,
                    classes: variant(name, "neutral").text,
                };
            },

            formatDate(value, format = "medium") {
                if (!value) return "-";
                const date = new Date(value);
                if (isNaN(date.getTime())) return value;
                return dateFormat(format).format(date);
            },

            isAllSelected() {
                return (
                    this.rows.length > 0 &&
                    this.selectedIds.length === this.rows.length
                );
            },

            toggleSelectAll() {
                if (this.isAllSelected()) {
                    this.selectedIds = [];
                } else {
                    this.selectedIds = this.rows
                        .map((row) => row.id)
                        .filter(Boolean);
                }
                this.dispatchSelectionChange();
            },

            toggleSelectRow(id) {
                if (this.selectedIds.includes(id)) {
                    this.selectedIds = this.selectedIds.filter(
                        (item) => item !== id,
                    );
                } else {
                    this.selectedIds.push(id);
                }
                this.dispatchSelectionChange();
            },

            getSelectedItems() {
                return this.rows.filter((row) =>
                    this.selectedIds.includes(row.id),
                );
            },

            dispatchSelectionChange() {
                this.$dispatch("jkc-table-selection-change", {
                    selectedIds: this.selectedIds,
                    selectedItems: this.getSelectedItems(),
                });
            },

            /**
             * True when rows link to `baseRoute/id` (and `/edit`); without a baseRoute
             * the view and edit actions only emit their event.
             */
            canNavigate() {
                return this.baseRoute !== "";
            },
            rowUrl(item) {
                return this.baseRoute + "/" + encodeURIComponent(item.id ?? "");
            },
            editUrl(item) {
                return this.rowUrl(item) + "/edit";
            },

            /**
             * Emits a cancelable table event; returns false when a listener cancelled it.
             */
            emit(name, detail) {
                const event = new CustomEvent(name, {
                    bubbles: true,
                    cancelable: true,
                    detail,
                });
                this.$el.dispatchEvent(event);
                return !event.defaultPrevented;
            },

            /**
             * Click on a view/edit link: the event goes first, and cancelling it
             * (`preventDefault()`) cancels the navigation, htmx included.
             */
            follow(clickEvent, name, item) {
                if (this.emit("jkc-table-" + name, { item })) return;
                clickEvent.preventDefault();
                clickEvent.stopImmediatePropagation();
            },

            hasAction(name) {
                return this.withAction.includes(name);
            },
            triggerView(item) {
                if (this.hasAction("view"))
                    this.$dispatch("jkc-table-view", { item });
            },
            triggerEdit(item) {
                if (this.hasAction("edit"))
                    this.$dispatch("jkc-table-edit", { item });
            },
            triggerDelete(item) {
                if (this.hasAction("delete"))
                    this.$dispatch("jkc-table-delete", { item });
            },
        };
    }

    /**
     * Pagination: local "jump to page" input and numbered page list.
     */
    function pagination() {
        return {
            pageInput: 1,

            init() {
                this.pageInput = this.currentPage;
                this.$watch("currentPage", (val) => {
                    this.pageInput = val;
                });
            },

            pageList() {
                const lastPage = this.lastPage;
                const currentPage = this.currentPage;

                if (lastPage <= 1) return [1];
                const start = Math.max(2, currentPage - 1);
                const end = Math.min(lastPage - 1, currentPage + 1);
                const middle = Array.from(
                    { length: Math.max(0, end - start + 1) },
                    (_, index) => start + index,
                );
                return [
                    1,
                    ...(start > 2 ? ["..."] : []),
                    ...middle,
                    ...(end < lastPage - 1 ? ["..."] : []),
                    lastPage,
                ];
            },

            jumpToPage(target) {
                const pageNum = parseInt(target);

                if (isNaN(pageNum) || pageNum < 1 || pageNum > this.lastPage) {
                    this.pageInput = this.currentPage;
                    return;
                }
                this.goToPage(pageNum);
            },
        };
    }

    /**
     * Registers every component on the given Alpine instance.
     */
    function register(Alpine) {
        Alpine.data("jkcTable", table);
        Alpine.data("jkcTableFilter", filter);
        Alpine.data("jkcTablePerPage", perPage);
        Alpine.data("jkcTableBody", body);
        Alpine.data("jkcTablePagination", pagination);
    }

    window.JkcTable = { table, filter, perPage, body, pagination, register };

    if (window.Alpine) {
        register(window.Alpine);
    } else {
        document.addEventListener("alpine:init", () => register(window.Alpine));
    }
})();