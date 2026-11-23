/**
 * @typedef {{value: string, label: string}} SelectOption
 */

/**
 * Tell whether a raw value counts as "no value".
 * @param {*} value
 * @returns {boolean}
 */
export function isBlank(value) {
    return value === null || value === undefined || value === '';
}

/**
 * Turn a map or a list of {value, label} into a list of SelectOption.
 * @param {Object|Array|null|undefined} raw
 * @returns {SelectOption[]}
 */
export function normalizeOptions(raw) {
    if (Array.isArray(raw)) {
        return raw
            .filter((o) => o && typeof o === 'object' && 'value' in o)
            .map((o) => ({ value: String(o.value), label: String(o.label ?? o.value) }));
    }

    if (raw && typeof raw === 'object') {
        return Object.entries(raw).map(([value, label]) => ({ value, label: String(label) }));
    }

    return [];
}

/**
 * Coerce an incoming value to the selection shape (string or string[]).
 * @param {*} value
 * @param {boolean} multiple
 * @returns {string|string[]}
 */
export function normalizeSelection(value, multiple) {
    if (multiple) {
        const list = Array.isArray(value) ? value : isBlank(value) ? [] : [value];
        return list.filter((v) => !isBlank(v)).map(String);
    }

    return isBlank(value) || Array.isArray(value) ? '' : String(value);
}

/**
 * Lowercase and strip accents so "ete" matches "Été".
 * @param {string} text
 * @returns {string}
 */
export function foldText(text) {
    return String(text).normalize('NFD').replace(/\p{M}/gu, '').toLowerCase();
}

/**
 * Keep the options whose label contains the query.
 * @param {SelectOption[]} options
 * @param {string} query
 * @returns {SelectOption[]}
 */
export function filterOptions(options, query) {
    const needle = foldText(query.trim());

    if (needle === '') return options;

    return options.filter((o) => foldText(o.label).includes(needle));
}

/**
 * Add the value to the list, or remove it if already present.
 * @param {string[]} list
 * @param {string} value
 * @returns {string[]}
 */
export function toggleValue(list, value) {
    return list.includes(value) ? list.filter((v) => v !== value) : [...list, value];
}

/**
 * Tell whether an option is part of the current selection.
 * @param {string|string[]} selection
 * @param {string} value
 * @param {boolean} multiple
 * @returns {boolean}
 */
export function isSelected(selection, value, multiple) {
    return multiple ? selection.includes(value) : selection === value;
}

/**
 * Tell whether the selection holds something.
 * @param {string|string[]} selection
 * @param {boolean} multiple
 * @returns {boolean}
 */
export function hasSelection(selection, multiple) {
    return multiple ? selection.length > 0 : selection !== '';
}

/**
 * Build the text shown on the trigger button.
 * @param {SelectOption[]} options
 * @param {string|string[]} selection
 * @param {boolean} multiple
 * @param {string} placeholder Shown when nothing is selected.
 * @param {string} [summary=placeholder] Prefix of the count shown for 2+ values.
 * @returns {string}
 */
export function describeSelection(options, selection, multiple, placeholder, summary = placeholder) {
    const labelOf = (value) => options.find((o) => o.value === value)?.label ?? value;

    if (!hasSelection(selection, multiple)) return placeholder;
    if (!multiple) return labelOf(selection);
    if (selection.length === 1) return labelOf(selection[0]);

    return `${summary} (${selection.length})`;
}

/**
 * Move an index by a delta, wrapping around. Returns -1 for an empty list.
 * @param {number} index
 * @param {number} delta
 * @param {number} length
 * @returns {number}
 */
export function wrapIndex(index, delta, length) {
    if (length === 0) return -1;
    if (index < 0) return delta > 0 ? 0 : length - 1;

    return (index + delta + length) % length;
}