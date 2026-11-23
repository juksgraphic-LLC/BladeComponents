/** Error messages used by the core. */
export const MESSAGES = {
  REQUEST_FAILED: "Request failed",
  INVALID_JSON: "Invalid JSON response",
};

/** Meta keys read from the response. */
export const META_KEYS = [
  "current_page",
  "last_page",
  "per_page",
  "total",
  "from",
  "to",
];

/** Query parameter names sent to the endpoint. */
export const PARAMS = Object.freeze({
  PAGE:      "page",
  PER_PAGE:  "per_page",
  SEARCH:    "search",
  SORT:      "sort",
  DIRECTION: "direction",
});

/** Allowed sort column pattern (same as PHP FilterNormalizer::KEY_PATTERN). */
export const SORT_COLUMN_PATTERN = /^[A-Za-z0-9_.\-]+$/;