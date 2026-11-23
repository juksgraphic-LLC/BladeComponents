/**
 * Validates a target page, or returns null.
 *
 * @param {number|string} page Target page number.
 * @param {number} current Current page number.
 * @param {{last_page: number|null}|null} meta Current page meta.
 * @returns {number|null}
 */
export function resolvePage(page, current, meta) {
    const next = parseInt(page, 10);
    const last = meta ? meta.last_page : null;

    if (Number.isNaN(next) || next < 1 || next === current || (last !== null && next > last)) {
        return null;
    }

    return next;
}

/**
 * Validates an items-per-page value, or returns null.
 *
 * @param {number|string} value Items per page.
 * @returns {number|null}
 */
export function resolvePerPage(value) {
    const next = parseInt(value, 10);

    return Number.isNaN(next) || next < 1 ? null : next;
}

/**
 * Aligns page settings with what the backend returned.
 *
 * @param {{page: number, perPage: number}} settings Current settings.
 * @param {{current_page: number|null, per_page: number|null}} meta Response meta.
 * @returns {{page: number, perPage: number}}
 */
export function syncSettings(settings, meta) {
    return {
        page: meta.current_page === null ? settings.page : meta.current_page,
        perPage: meta.per_page === null ? settings.perPage : meta.per_page,
    };
}