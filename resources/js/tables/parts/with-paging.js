/**
 * Current / last page. Needs: fetchData().
 */

export function withPaging() {
    return {
        currentPage: 1,
        lastPage: 1,

        goToPage(page) {
            const target = parseInt(page);
            const valid = target >= 1 && target <= this.lastPage;
            if (!valid || target === this.currentPage) return;
            this.currentPage = target;
            this.fetchData();
        },
    };
}