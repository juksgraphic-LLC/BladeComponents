/**
 * @typedef {{page: number}|{gap: true}} PageItem
 */

/**
 * Builds the page buttons: first, last, current ± siblings, with gaps.
 *
 * @param {number} current Current page number.
 * @param {number} last Last page number.
 * @param {number} siblings Pages kept on each side of the current one.
 * @returns {PageItem[]}
 */
export function buildPageItems(current, last, siblings) {
  const pages = new Set([1, last]);

  for (let page = current - siblings; page <= current + siblings; page++) {
    if (page >= 1 && page <= last) {
      pages.add(page);
    }
  }

  const items = [];
  let previous = 0;

  [...pages]
    .sort((a, b) => a - b)
    .forEach((page) => {
      if (page - previous > 1) {
        items.push({ gap: true });
      }

      items.push({ page });
      previous = page;
    });

  return items;
}

/**
 * Replaces {name} placeholders in a text template.
 *
 * @param {string} template Template, e.g. "Page {page}".
 * @param {Object<string, string|number>} values Values by placeholder name.
 * @returns {string}
 */
export function formatText(template, values) {
  return String(template).replace(/\{(\w+)\}/g, (match, name) =>
    Object.prototype.hasOwnProperty.call(values, name) ? String(values[name]) : match,
  );
}

/**
 * Tells whether a response is an empty page beyond the last one
 * (e.g. after deleting the last row of the last page).
 *
 * @param {Array} items Items of the response.
 * @param {{current_page: number|null, last_page: number|null}} meta Response meta.
 * @returns {boolean}
 */
export function isPastLastPage(items, meta) {
  return (
    items.length === 0 &&
    meta.current_page !== null &&
    meta.last_page !== null &&
    meta.current_page > 1 &&
    meta.current_page > meta.last_page
  );
}