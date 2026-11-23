import { MESSAGES } from "./constants.js";

/**
 * Creates an error carrying an HTTP status.
 *
 * @param {string} message Error message.
 * @param {number|null} status HTTP status.
 * @returns {Error}
 */
export function makeError(message, status) {
  const error = new Error(message);

  error.status = status;

  return error;
}

/**
 * Fetches and parses a JSON response.
 *
 * @param {string} url Request URL.
 * @param {AbortSignal} signal Abort signal.
 * @returns {Promise<Object>}
 * @throws {Error} On network error, bad status or bad JSON.
 */
export async function fetchJson(url, signal) {
  const response = await fetch(url, {
    signal,
    headers: {
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
  });
  const body = await response.json().catch(() => null);

  if (!response.ok) {
    throw makeError(
      (body && body.message) || response.statusText || MESSAGES.REQUEST_FAILED,
      response.status,
    );
  }

  if (body === null) {
    throw makeError(MESSAGES.INVALID_JSON, response.status);
  }

  return body;
}
