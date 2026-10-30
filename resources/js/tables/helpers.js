/**
 * Pure helpers, no Alpine.
 */

export function checked(response) {
    if (!response.ok) throw new Error("HTTP " + response.status);
    return response;
}

export function plain(text) {
    return String(text ?? "")
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .toLowerCase();
}

export function buildPageList(current, last) {
    if (last <= 1) return [1];
    const start = Math.max(2, current - 1);
    const end = Math.min(last - 1, current + 1);
    const middle = Array.from(
        { length: Math.max(0, end - start + 1) },
        (_, i) => start + i,
    );
    return [
        1,
        ...(start > 2 ? ["..."] : []),
        ...middle,
        ...(end < last - 1 ? ["..."] : []),
        last,
    ];
}

export function compose(...parts) {
    const result = {};
    const inits = [];

    parts.forEach(({ init, ...rest }) => {
        if (init) inits.push(init);
        Object.defineProperties(
            result,
            Object.getOwnPropertyDescriptors(rest),
        );
    });

    result.init = function () {
        inits.forEach((fn) => fn.call(this));
    };
    return result;
}