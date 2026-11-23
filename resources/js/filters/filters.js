/**
 * Tells whether a filter value carries no information.
 *
 * @param {*} value Raw filter value.
 * @returns {boolean}
 */
export function isEmptyValue(value) {
    return value === null
        || value === undefined
        || value === ''
        || (Array.isArray(value) && value.length === 0);
}

/**
 * Casts a filter value to a string or a string list.
 *
 * @param {*} value Raw filter value.
 * @returns {string|string[]}
 */
export function normalizeValue(value) {
    if (Array.isArray(value)) {
        return value.map(String).filter((item) => item !== '');
    }

    return isEmptyValue(value) ? '' : String(value);
}

/**
 * Builds the starting filter values from PHP defaults.
 *
 * @param {Object<string, string|string[]>|Array|null} initial Default filters.
 * @returns {Object<string, string|string[]>}
 */
export function buildInitialValues(initial) {
    const source = initial || {};
    const values = {};

    Object.keys(source).forEach((key) => {
        values[key] = normalizeValue(source[key]);
    });

    return values;
}

/**
 * Returns a copy of the values with one filter changed.
 *
 * @param {Object<string, string|string[]>} values Current values.
 * @param {string} key Filter key.
 * @param {*} value New raw value.
 * @returns {Object<string, string|string[]>}
 */
export function withFilter(values, key, value) {
    return Object.assign({}, values, { [key]: normalizeValue(value) });
}