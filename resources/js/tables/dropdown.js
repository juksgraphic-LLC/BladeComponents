/**
 * Dropdowns shared by the filters and the rows-per-page selector.
 */
import { plain } from "./helpers.js";

const SEARCH_THRESHOLD = 8;

export function dropdown(source) {
    return {
        isOpen: false,
        query: "",
        clearable: false,
        ...source,

        hasValue() {
            return String(this.currentValue()) !== "";
        },
        canClear() {
            return this.clearable && this.hasValue();
        },
        isChosen(value) {
            return String(this.currentValue()) === String(value);
        },
        currentLabel() {
            const found = this.hasValue()
                ? this.choices().find((c) => this.isChosen(c.value))
                : null;
            return found ? found.label : this.placeholder();
        },

        hasSearch() {
            return this.choices().length > SEARCH_THRESHOLD;
        },
        isSearching() {
            return this.query.trim() !== "";
        },
        shownChoices() {
            if (!this.isSearching()) return this.choices();
            const needle = plain(this.query.trim());
            return this.choices().filter((c) =>
                plain(c.label).includes(needle),
            );
        },

        toggle() {
            this.isOpen = !this.isOpen;
            this.query = "";
            if (this.isOpen) this.$nextTick(() => this.$refs.query?.focus());
        },
        close() {
            this.isOpen = false;
            this.query = "";
        },
        choose(value) {
            this.close();
            this.pick(value);
        },
        clear() {
            this.choose("");
        },
        chooseFirst() {
            const first = this.shownChoices()[0];
            if (first) this.choose(first.value);
        },
    };
}

export function filter(definition) {
    return dropdown({
        clearable: true,
        placeholder: () => definition.label,
        choices: () => definition.options ?? [],
        currentValue() {
            return this.activeFilters[definition.key] ?? "";
        },
        pick(value) {
            this.setFilter(definition.key, value);
        },
    });
}

export function perPage() {
    return dropdown({
        placeholder() {
            return String(this.perPage);
        },
        choices() {
            return this.perPageOptions.map((o) => ({
                value: o,
                label: String(o),
            }));
        },
        currentValue() {
            return this.perPage;
        },
        pick(value) {
            this.perPage = value;
            this.resetAndFetch();
        },
    });
}