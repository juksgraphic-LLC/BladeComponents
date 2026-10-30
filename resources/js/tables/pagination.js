/**
 * Pagination: "jump to page" input and numbered page list.
 */
import { buildPageList } from "./helpers.js";

export function pagination() {
    return {
        pageInput: 1,

        init() {
            this.pageInput = this.currentPage;
            this.$watch("currentPage", (page) => (this.pageInput = page));
        },

        pageList() {
            return buildPageList(this.currentPage, this.lastPage);
        },

        jumpToPage(target) {
            const page = parseInt(target);
            if (isNaN(page) || page < 1 || page > this.lastPage) {
                this.pageInput = this.currentPage;
                return;
            }
            this.goToPage(page);
        },
    };
}
