import {
  CHANGE_EVENT,
  DEFAULT_TEXTS,
  KEYS,
  SEARCH_THRESHOLD,
} from "./constants.js";
import {
  describeSelection,
  filterOptions,
  hasSelection,
  isSelected,
  normalizeOptions,
  normalizeSelection,
  toggleValue,
  wrapIndex,
} from "./options.js";

/**
 * Alpine component for a light single/multi select dropdown.
 * It never talks to the bar directly: it dispatches a DOM event.
 *
 * @param {{key: string
 * , label?: string,
 *  placeholder?: string,
 *  summary?: string,
 *  multiple?: boolean,
 *  options?: Object|Array,
 *  texts?: Object}} config
 * @returns {Object} Alpine data object.
 */
export default function filterSelect(config) {
  const multiple = Boolean(config.multiple);
  const options = normalizeOptions(config.options);

  return {
    key: config.key,
    label: config.label ?? config.key,
    placeholder: config.placeholder ?? config.label ?? config.key,
    summary: config.summary ?? config.label ?? config.key,
    multiple,
    options,
    texts: { ...DEFAULT_TEXTS, ...(config.texts ?? {}) },
    uid: `fs-${config.key}-${Math.random().toString(36).slice(2, 8)}`,
    selection: normalizeSelection(null, multiple),
    open: false,
    query: "",
    activeIndex: -1,

    /** @returns {boolean} True when the search field must be shown. */
    get showSearch() {
      return this.options.length >= SEARCH_THRESHOLD;
    },

    /** @returns {Array} Options matching the current query. */
    get visibleOptions() {
      return filterOptions(this.options, this.query);
    },

    /** @returns {boolean} True when something is selected. */
    get hasValue() {
      return hasSelection(this.selection, this.multiple);
    },

    /** @returns {string} Text of the trigger button. */
    get triggerLabel() {
      return describeSelection(
        this.options,
        this.selection,
        this.multiple,
        this.placeholder,
        this.summary,
      );
    },

    /**
     * Align the selection with the bar's value (initial load, reset).
     * @param {*} value
     * @returns {void}
     */
    sync(value) {
      this.selection = normalizeSelection(value, this.multiple);
    },

    /**
     * Build the DOM id of an option.
     * @param {number} index
     * @returns {string}
     */
    optionId(index) {
      return `${this.uid}-opt-${index}`;
    },

    /**
     * Id of the highlighted option, for aria-activedescendant.
     * @returns {string|null}
     */
    activeId() {
      return this.open && this.activeIndex >= 0
        ? this.optionId(this.activeIndex)
        : null;
    },

    /**
     * Tell whether an option is selected.
     * @param {{value: string}} option
     * @returns {boolean}
     */
    isPicked(option) {
      return isSelected(this.selection, option.value, this.multiple);
    },

    /**
     * Open the list and highlight the first selected option.
     * @returns {void}
     */
    openList() {
      this.open = true;
      this.query = "";
      this.activeIndex = Math.max(
        0,
        this.options.findIndex((o) => this.isPicked(o)),
      );
      this.$nextTick(() => this.focusEntry());
    },

    /**
     * Close the list without losing the selection.
     * @param {boolean} [refocus=false] Give focus back to the trigger.
     * @returns {void}
     */
    closeList(refocus = false) {
      this.open = false;
      this.query = "";
      this.activeIndex = -1;
      if (refocus) this.$refs.trigger.focus();
    },

    /**
     * Open the list if closed, close it otherwise.
     * @returns {void}
     */
    toggleList() {
      this.open ? this.closeList() : this.openList();
    },

    /**
     * Focus the search field if any, then scroll to the active option.
     * @returns {void}
     */
    focusEntry() {
      if (this.showSearch) this.$refs.search?.focus();
      this.scrollToActive();
    },

    /**
     * Keep the highlighted option visible in the scrolling list.
     * @returns {void}
     */
    scrollToActive() {
      document
        .getElementById(this.optionId(this.activeIndex))
        ?.scrollIntoView({ block: "nearest" });
    },

    /**
     * Reset the highlight when the query changes.
     * @returns {void}
     */
    onQueryInput() {
      this.activeIndex = this.visibleOptions.length ? 0 : -1;
    },

    /**
     * Move the highlight; opens the list first if it is closed.
     * @param {number} delta
     * @returns {void}
     */
    moveActive(delta) {
      if (!this.open) return this.openList();
      this.activeIndex = wrapIndex(
        this.activeIndex,
        delta,
        this.visibleOptions.length,
      );
      this.scrollToActive();
    },

    /**
     * Jump the highlight to the first or last option.
     * @param {string} edge KEYS.HOME or KEYS.END.
     * @returns {void}
     */
    jumpActive(edge) {
      if (!this.open || this.visibleOptions.length === 0) return;
      this.activeIndex =
        edge === KEYS.HOME ? 0 : this.visibleOptions.length - 1;
      this.scrollToActive();
    },

    /**
     * Enter key: open the list, or pick the highlighted option.
     * @returns {void}
     */
    onEnter() {
      if (!this.open) return this.openList();
      const option = this.visibleOptions[this.activeIndex];
      if (option) this.pick(option);
    },

    /**
     * Apply a clicked option, then notify the bar.
     * @param {{value: string}} option
     * @returns {void}
     */
    pick(option) {
      this.selection = this.multiple
        ? toggleValue(this.selection, option.value)
        : option.value;
      this.commit();
      if (!this.multiple) this.closeList(true);
    },

    /**
     * Empty the selection and notify the bar.
     * @returns {void}
     */
    clear() {
      this.selection = normalizeSelection(null, this.multiple);
      this.commit();
    },

    /**
     * Dispatch the change event consumed by the bar.
     * @returns {void}
     */
    commit() {
      this.$dispatch(CHANGE_EVENT, { key: this.key, value: this.selection });
    },
  };
}
