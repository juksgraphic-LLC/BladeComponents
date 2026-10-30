/**
 * Delete confirmation modal. Needs: rowUrl(), requestHeaders(), fetchData().
 */
import { checked } from "../helpers.js";

export function withDeletion() {
    return {
        pendingDelete: null,
        deleting: false,
        deleteError: "",

        askDelete(item) {
            this.pendingDelete = item;
            this.deleteError = "";
            this.$dispatch("jkc-open-modal", this.deleteModal);
        },

        closeDeleteModal() {
            this.$dispatch("jkc-close-modal", this.deleteModal);
        },

        /** The modal tells when it closes (cancel, close button, backdrop). */
        onDeleteModalClosed(event) {
            if (event.detail?.id !== this.deleteModal) return;
            this.pendingDelete = null;
            this.deleteError = "";
        },

        async confirmDelete() {
            const item = this.pendingDelete;
            if (!item || this.deleting) return;

            this.deleting = true;
            this.deleteError = "";

            try {
                checked(
                    await fetch(this.rowUrl(item), {
                        method: "DELETE",
                        headers: this.requestHeaders(),
                    }),
                );
                this.closeDeleteModal();
                
                if (this.rows.length <= 1 && this.currentPage > 1)
                    this.currentPage--;
                
                await this.fetchData();
            } catch (error) {
                this.deleteError = "Failed to delete the item";
            } finally {
                this.deleting = false;
            }
        },
    };
}
