import { SORT_COLUMN_PATTERN } from "./constants.js";
import { isEmptyValue } from "./filters.js";

/**
 * @typedef {Object} SortState
 * @property {string|null} column Sorted column, null when unsorted.
 * @property {string} direction "asc" or "desc".
 */

/**
 * Builds the starting sort state from the PHP config.
 *
 * @param {{column?: string|null, direction?: string}|null} initial PHP sort.
 * @param {Object<string, string>} directions Direction names (ASC, DESC).
 * @returns {SortState}
 */
export function buildInitialSort(initial, directions) {
  const source = initial || {};

  return {
    column: isEmptyValue(source.column) ? null : String(source.column),
    direction: isEmptyValue(source.direction)
      ? directions.ASC
      : String(source.direction),
  };
}

/**
 * Validates a sort request, or returns null when it is invalid.
 * An empty column clears the sort.
 *
 * @param {*} column Target column.
 * @param {*} direction Target direction (default used when empty).
 * @param {Object<string, string>} directions Direction names (ASC, DESC).
 * @param {string} fallback Direction used when none is given.
 * @returns {SortState|null}
 */
export function resolveSort(column, direction, directions, fallback) {
  if (isEmptyValue(column)) {
    return { column: null, direction: fallback };
  }

  const name = String(column);
  const next = isEmptyValue(direction)
    ? fallback
    : String(direction).toLowerCase();

  if (
    !SORT_COLUMN_PATTERN.test(name) ||
    !Object.values(directions).includes(next)
  ) {
    return null;
  }

  return { column: name, direction: next };
}

/**
 * Tells whether two sort states are identical.
 *
 * @param {SortState} a First state.
 * @param {SortState} b Second state.
 * @returns {boolean}
 */
export function isSameSort(a, b) {
  return a.column === b.column && a.direction === b.direction;
}
