/**
 * Root component: one `x-data="jkcTable"` built from the parts.
 * Order matters for `init()`: config first, fetching last (first request).
 */
import { compose }      from "./helpers.js";
import { withConfig }   from "./parts/with-config.js";
import { withQuery }    from "./parts/with-query.js";
import { withPaging }   from "./parts/with-paging.js";
import { withCommands } from "./parts/with-commands.js";
import { withDeletion } from "./parts/with-deletion.js";
import { withFetching } from "./parts/with-fetching.js";

export function table() {
    return compose(
        withConfig(),
        withQuery(),
        withPaging(),
        withCommands(),
        withDeletion(),
        withFetching(),
    );
}
