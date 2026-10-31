/**
 * Juksgraphic — dynamic table (Alpine.js components). Entry point.
 *
 * Everything is exposed under `window.JkcTable`. Registration is idempotent and
 * works whether Alpine is already running (htmx body swap) or not yet started
 * (`alpine:init`).
 *
 * Tailwind note: class names in the parts are written in full on purpose, never
 * concatenated, so that the Tailwind scanner can find them.
 */
import { table } from "./tables/table.js";
import { filter, perPage } from "./tables/dropdown.js";
import { body } from "./tables/body.js";
import { pagination } from "./tables/pagination.js";

if (!window.JkcTable) {
    const register = (Alpine) => {
        Alpine.data("jkcTable", table);
        Alpine.data("jkcTableFilter", filter);
        Alpine.data("jkcTablePerPage", perPage);
        Alpine.data("jkcTableBody", body);
        Alpine.data("jkcTablePagination", pagination);
    };

    window.JkcTable = { table, filter, perPage, body, pagination, register };

    if (window.Alpine) register(window.Alpine);
    else document.addEventListener("alpine:init", () => register(window.Alpine));
}