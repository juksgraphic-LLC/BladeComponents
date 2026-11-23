import filterBar from "./filters/bar.js";
import filterSelect from "./filters/selects/index.js";
import filterPager from "./filters/pager/index.js";

/**
 * Registers the filter bar components in Alpine.
 */

if (!window.filterBar) {
    const register = (Alpine) => {
        Alpine.data("filterBar", filterBar);
        Alpine.data("filterSelect", filterSelect);
        Alpine.data("filterPager", filterPager);
    };

    if (window.Alpine) {
        register(window.Alpine);
    } else {
        document.addEventListener("alpine:init", () => register(window.Alpine));
    }
}
