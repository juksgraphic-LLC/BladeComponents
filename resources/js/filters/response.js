import { META_KEYS, MESSAGES } from "./constants.js";

/**
 * @typedef {Object} PageMeta
 * @property {number|null} current_page Current page number.
 * @property {number|null} last_page Last page number.
 * @property {number|null} per_page Items per page.
 * @property {number|null} total Total item count.
 * @property {number|null} from First item index.
 * @property {number|null} to Last item index.
 */

/**
 * Reads a numeric meta value or null.
 *
 * @param {Object} source Meta source object.
 * @param {string} key Meta key.
 * @returns {number|null}
 */
export function pickNumber(source, key) {
  const raw = source[key];
  const value = Number(raw);

  return raw === null || raw === undefined || Number.isNaN(value)
    ? null
    : value;
}

/**
 * Normalizes pagination meta (paginator or API Resource).
 *
 * @param {Object|null} json Parsed response body.
 * @returns {PageMeta}
 */
export function normalizeMeta(json) {
  const body = json || {};
  const source =
    body.meta !== null && typeof body.meta === "object" ? body.meta : body;
  const meta = {};

  META_KEYS.forEach((key) => {
    meta[key] = pickNumber(source, key);
  });

  return meta;
}

/**
 * Extracts the items array from the response.
 *
 * @param {Object|null} json Parsed response body.
 * @returns {Array<Object>}
 */
export function extractItems(json) {
  return json && Array.isArray(json.data) ? json.data : [];
}

/**
 * Converts a request error to a plain error state.
 *
 * @param {Error} error Request error.
 * @returns {{status: number|null, message: string}}
 */
export function toErrorState(error) {
  return {
    status: error.status === undefined ? null : error.status,
    message: error.message || MESSAGES.REQUEST_FAILED,
  };
}
