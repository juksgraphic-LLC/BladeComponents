import { buildInitialValues, isEmptyValue, withFilter } from "./filters.js";
import { buildQueryString, buildUrl } from "./query.js";
import { resolvePage, resolvePerPage, syncSettings } from "./pagination.js";
import { buildInitialSort, isSameSort, resolveSort } from "./sort.js";
import { extractItems, normalizeMeta, toErrorState } from "./response.js";
import { fetchJson } from "./http.js";
import { bindCommands, emitEvent } from "./events.js";

/**
 * @typedef {Object} FilterBarConfig
 * @property {string} id Bar identifier.
 * @property {string} endpoint Data endpoint URL.
 * @property {number} page First page number.
 * @property {number} perPage Items per page.
 * @property {boolean} autoload Fetch on init.
 * @property {{enabled: boolean, placeholder: string, debounce: number}} search Search settings.
 * @property {Array<Object>} filters Filter definitions.
 * @property {Object<string, string|string[]>|Array} initialFilters Defaults sent first.
 * @property {{column: string|null, direction: string}} sort Initial sort.
 * @property {Object<string, string>} directions Direction names (ASC, DESC).
 * @property {Object<string, string>} events Event names.
 * @property {Object<string, string>} commands Command names.
 */

/**
 * Creates one filter bar Alpine component instance.
 *
 * @param {FilterBarConfig} config Configuration built by PHP.
 * @returns {Object}
 */
export default function filterBar(config) {
  return {
    config:  config,
    page:    config.page,
    perPage: config.perPage,
    search: "",
    filterValues: {},
    sort: buildInitialSort(config.sort, config.directions),
    items: [],
    meta: null,
    loading: false,
    error: null,
    _controller: null,
    _timer: null,
    _unbind: null,

    /**
     * Applies initial filters, listens to commands, loads.
     *
     * @returns {void}
     */
    init() {
      this.filterValues = buildInitialValues(this.config.initialFilters);
      this._unbind = bindCommands(this.config, {
        SET_PAGE: (detail) => this.setPage(detail.page),
        SET_PER_PAGE: (detail) => this.setPerPage(detail.perPage),
        SET_SORT: (detail) => this.setSort(detail.sort, detail.direction),
        REFRESH: () => this.refresh(),
      });

      if (this.config.autoload) {
        this.load();
      }
    },

    /**
     * Stops listeners, timers and the pending request.
     *
     * @returns {void}
     */
    destroy() {
      if (this._unbind) {
        this._unbind();
      }

      this.cancelScheduledLoad();
      this.abortPending();
    },

    /**
     * Emits a bar event carrying the bar id.
     *
     * @param {string} key Event key (see config.events).
     * @param {Object} payload Event detail without barId.
     * @returns {void}
     */
    emit(key, payload) {
      emitEvent(
        this.config.events[key],
        Object.assign({ barId: this.config.id }, payload),
      );
    },

    /**
     * Returns a copy of the current sort, safe to hand out.
     *
     * @returns {{column: string|null, direction: string}}
     */
    currentSort() {
      return Object.assign({}, this.sort);
    },

    /**
     * Builds the query string from the current state.
     *
     * @returns {string}
     */
    query() {
      return buildQueryString({
        page: this.page,
        perPage: this.perPage,
        search: this.search,
        filters: this.filterValues,
        sort: this.sort,
      });
    },

    /**
     * Builds the request URL from the current state.
     *
     * @returns {string}
     */
    url() {
      return buildUrl(this.config.endpoint, this.query());
    },

    /**
     * Aborts the request in flight, if any.
     *
     * @returns {void}
     */
    abortPending() {
      if (this._controller) {
        this._controller.abort();
      }
    },

    /**
     * True when a search or at least one filter is set.
     * Sort is not a filter: it does not count.
     *
     * @returns {boolean}
     */
    get hasActiveFilters() {
      return (
        !isEmptyValue(this.search) ||
        Object.values(this.filterValues).some((value) => !isEmptyValue(value))
      );
    },

    /**
     * Cancels a scheduled (debounced) load.
     *
     * @returns {void}
     */
    cancelScheduledLoad() {
      clearTimeout(this._timer);
      this._timer = null;
    },

    /**
     * Schedules a load after the search debounce delay.
     *
     * @returns {void}
     */
    scheduleLoad() {
      this.cancelScheduledLoad();
      this._timer = setTimeout(() => this.load(), this.config.search.debounce);
    },

    /**
     * Marks the start of a request.
     *
     * @returns {void}
     */
    startLoading() {
      this.loading = true;
      this.error = null;
    },

    /**
     * Stores items and meta, then syncs page settings.
     *
     * @param {Object} json Parsed response body.
     * @returns {{items: Array<Object>, meta: Object}}
     */
    applyResponse(json) {
      const items = extractItems(json);
      const meta = normalizeMeta(json);
      const synced = syncSettings(
        { page: this.page, perPage: this.perPage },
        meta,
      );

      this.items = items;
      this.meta = meta;
      this.page = synced.page;
      this.perPage = synced.perPage;

      return { items, meta };
    },

    /**
     * Stores a request error.
     *
     * @param {Error} error Request error.
     * @returns {{status: number|null, message: string}}
     */
    applyError(error) {
      const state = toErrorState(error);

      this.error = state;

      return state;
    },

    /**
     * Emits the change event with the current filters, search and sort.
     *
     * @returns {void}
     */
    emitChange() {
      this.emit("CHANGE", {
        filters: Object.assign({}, this.filterValues),
        search: this.search,
        sort: this.currentSort(),
        query: this.query(),
      });
    },

    /**
     * Fetches data for the current state.
     * A newer request cancels the previous one.
     *
     * @returns {Promise<void>}
     */
    async load() {
      this.cancelScheduledLoad();
      this.abortPending();

      const controller = new AbortController();
      const url = this.url();

      this._controller = controller;
      this.startLoading();
      this.emit("LOADING", { url });

      try {
        const json = await fetchJson(url, controller.signal);

        if (this._controller === controller) {
          this.emit(
            "LOADED",
            Object.assign(this.applyResponse(json), {
              raw: json,
              url,
              sort: this.currentSort(),
            }),
          );
        }
      } catch (error) {
        if (error.name !== "AbortError" && this._controller === controller) {
          this.emit("ERROR", Object.assign({ url }, this.applyError(error)));
        }
      } finally {
        if (this._controller === controller) {
          this._controller = null;
          this.loading = false;
        }
      }
    },

    /**
     * Sets one filter value and reloads from page 1.
     *
     * @param {string} key Filter key.
     * @param {string|string[]|null} value New value.
     * @returns {void}
     */
    setFilter(key, value) {
      this.filterValues = withFilter(this.filterValues, key, value);
      this.page = 1;
      this.emitChange();
      this.load();
    },

    /**
     * Sets the search term and reloads after the debounce.
     *
     * @param {string} value Search term.
     * @returns {void}
     */
    setSearch(value) {
      this.search = value;
      this.page = 1;
      this.emitChange();
      this.scheduleLoad();
    },

    /**
     * Clears filters and search (sort is kept), then reloads.
     * Emits reset, then change with the cleared state.
     *
     * @returns {void}
     */
    resetFilters() {
      this.filterValues = {};
      this.search = "";
      this.page = 1;
      this.emit("RESET", {});
      this.emitChange();
      this.load();
    },

    /**
     * Sets the sort and reloads from page 1.
     * An empty column clears the sort; invalid input is ignored.
     *
     * @param {string|null} column Column to sort by.
     * @param {string} [direction] "asc" or "desc".
     * @returns {void}
     */
    setSort(column, direction) {
      const next = resolveSort(
        column,
        direction,
        this.config.directions,
        this.config.sort.direction,
      );

      if (next === null || isSameSort(next, this.sort)) {
        return;
      }

      this.sort = next;
      this.page = 1;
      this.emitChange();
      this.load();
    },

    /**
     * Goes to a page when it is valid.
     *
     * @param {number|string} page Target page number.
     * @returns {void}
     */
    setPage(page) {
      const next = resolvePage(page, this.page, this.meta);

      if (next === null) {
        return;
      }

      this.page = next;
      this.load();
    },

    /**
     * Sets items per page and reloads from page 1.
     *
     * @param {number|string} value Items per page.
     * @returns {void}
     */
    setPerPage(value) {
      const next = resolvePerPage(value);

      if (next === null) {
        return;
      }

      this.perPage = next;
      this.page = 1;
      this.load();
    },

    /**
     * Reloads with the current state.
     *
     * @returns {void}
     */
    refresh() {
      this.load();
    },
  };
}