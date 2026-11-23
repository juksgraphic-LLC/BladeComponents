import { isEmptyValue } from "./filters.js";
import { PARAMS } from "./constants.js";

/**
 * Appends non-empty filters as filters[key] params.
 *
 * @param {URLSearchParams} params Target params.
 * @param {Object<string, string|string[]>} filters Filter values by key.
 * @returns {void}
 */
export function appendFilters(params, filters) {
  Object.keys(filters).forEach((key) => {
    const value = filters[key];

    if (isEmptyValue(value)) {
      return;
    }

    if (Array.isArray(value)) {
      value.forEach((item) => params.append("filters[" + key + "][]", item));
      return;
    }

    params.append("filters[" + key + "]", value);
  });
}

/**
 * Appends the sort params when a column is set.
 *
 * @param {URLSearchParams} params Target params.
 * @param {{column: string|null, direction: string}|null|undefined} sort Sort state.
 * @returns {void}
 */
export function appendSort(params, sort) {
  if (!sort || isEmptyValue(sort.column)) {
    return;
  }

  params.append(PARAMS.SORT, sort.column);
  params.append(PARAMS.DIRECTION, sort.direction);
}

/**
 * Builds the query string from the bar state.
 *
 * @param {{page: number,
 *  perPage: number,
 *  search: string,
 *  filters: Object,
 *  sort?: Object}} state Bar state.
 * @returns {string}
 */
export function buildQueryString(state) {
  const params = new URLSearchParams();
  const term = String(
    state.search === null || state.search === undefined ? "" : state.search,
  ).trim();

  params.append(PARAMS.PAGE, state.page);
  params.append(PARAMS.PER_PAGE, state.perPage);

  if (term !== "") {
    params.append(PARAMS.SEARCH, term);
  }

  appendFilters(params, state.filters);
  appendSort(params, state.sort);

  return params.toString();
}

/**
 * Joins the endpoint and the query string.
 *
 * @param {string} endpoint Endpoint URL.
 * @param {string} queryString Query string without "?".
 * @returns {string}
 */
export function buildUrl(endpoint, queryString) {
  return endpoint + (endpoint.indexOf("?") === -1 ? "?" : "&") + queryString;
}
