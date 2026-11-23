import { emitEvent, bindListeners } from "../events.js";
import { resolvePage, resolvePerPage } from "../pagination.js";
import { buildPageItems, formatText, isPastLastPage } from "../pages.js";

/**
 * @typedef {Object} PagerConfig
 * @property {string} id DOM id prefix.
 * @property {string} barId Id of the filter bar to follow.
 * @property {number[]} perPageOptions Allowed page sizes.
 * @property {number} siblings Pages shown around the current one.
 * @property {Object<string, string>} texts UI texts and templates.
 * @property {Object<string, string>} events Bar event names.
 * @property {Object<string, string>} commands Bar command names.
 */

/**
 * Creates one pager Alpine component instance.
 * It reads `meta` from the bar events and drives the bar with commands.
 * It never calls the endpoint itself.
 *
 * @param {PagerConfig} config Configuration built by PHP.
 * @returns {Object}
 */
export default function filterPager(config) {
  return {
    config: config,
    meta: null,
    loading: true,
    failed: false,
    perPage: null,
    _unbind: null,

    /**
     * Starts following the bar.
     *
     * @returns {void}
     */
    init() {
      this._unbind = bindListeners(this.config.events, this.config.barId, {
        LOADING: () => this.onLoading(),
        LOADED: (detail) => this.onLoaded(detail),
        ERROR: () => this.onError(),
      });
    },

    /**
     * Stops following the bar.
     *
     * @returns {void}
     */
    destroy() {
      if (this._unbind) {
        this._unbind();
      }
    },

    /**
     * Sends a command to the bar.
     *
     * @param {string} key Command key (see config.commands).
     * @param {Object} payload Command detail without barId.
     * @returns {void}
     */
    send(key, payload) {
      emitEvent(
        this.config.commands[key],
        Object.assign({ barId: this.config.barId }, payload),
      );
    },

    /**
     * Marks a request in flight.
     *
     * @returns {void}
     */
    onLoading() {
      this.loading = true;
      this.failed = false;
    },

    /**
     * Stores the new meta, and leaves a page that no longer exists.
     *
     * @param {{items: Array, meta: Object}} detail Loaded event detail.
     * @returns {void}
     */
    onLoaded(detail) {
      this.meta = detail.meta;
      this.perPage = detail.meta.per_page;
      this.loading = false;
      this.failed = false;

      if (isPastLastPage(detail.items, detail.meta)) {
        this.goTo(Math.max(1, detail.meta.last_page));
      }
    },

    /**
     * Marks the last request as failed.
     *
     * @returns {void}
     */
    onError() {
      this.loading = false;
      this.failed = true;
    },

    /**
     * Asks the bar for a page when it is valid.
     *
     * @param {number|string} page Target page number.
     * @returns {void}
     */
    goTo(page) {
      const next = resolvePage(page, this.current, this.meta);

      if (next === null || this.loading) {
        return;
      }

      this.send("SET_PAGE", { page: next });
    },

    /**
     * Goes to the previous page.
     *
     * @returns {void}
     */
    prev() {
      this.goTo(this.current - 1);
    },

    /**
     * Goes to the next page.
     *
     * @returns {void}
     */
    next() {
      this.goTo(this.current + 1);
    },

    /**
     * Asks the bar for another page size.
     *
     * @param {number|string} value New items per page.
     * @returns {void}
     */
    changePerPage(value) {
      const next = resolvePerPage(value);

      if (next === null) {
        return;
      }

      this.send("SET_PER_PAGE", { perPage: next });
    },

    /**
     * Tells whether a page is the current one.
     *
     * @param {number} page Page number.
     * @returns {boolean}
     */
    isCurrent(page) {
      return page === this.current;
    },

    /**
     * Accessible label of a page button.
     *
     * @param {number} page Page number.
     * @returns {string}
     */
    pageLabel(page) {
      return formatText(this.config.texts.page, { page });
    },

    /** @returns {boolean} True once a response has been received. */
    get hasMeta() {
      return this.meta !== null;
    },

    /** @returns {number} Current page, 1 before any response. */
    get current() {
      return this.meta && this.meta.current_page !== null
        ? this.meta.current_page
        : 1;
    },

    /** @returns {number} Last page, 1 when unknown. */
    get last() {
      return this.meta && this.meta.last_page !== null
        ? this.meta.last_page
        : 1;
    },

    /** @returns {boolean} True when there is more than one page. */
    get hasPages() {
      return !this.failed && this.last > 1;
    },

    /** @returns {boolean} True when a previous page exists. */
    get canPrev() {
      return !this.loading && this.current > 1;
    },

    /** @returns {boolean} True when a next page exists. */
    get canNext() {
      return !this.loading && this.current < this.last;
    },

    /** @returns {import("../pages.js").PageItem[]} Buttons to render. */
    get pageItems() {
      return this.meta
        ? buildPageItems(this.current, this.last, this.config.siblings)
        : [];
    },

    /** @returns {string} "Showing x to y of z" text, empty on error. */
    get summary() {
      if (!this.meta || this.failed) {
        return "";
      }

      return formatText(this.config.texts.summary, {
        from: this.meta.from === null ? 0 : this.meta.from,
        to: this.meta.to === null ? 0 : this.meta.to,
        total: this.meta.total === null ? 0 : this.meta.total,
      });
    },
  };
}
