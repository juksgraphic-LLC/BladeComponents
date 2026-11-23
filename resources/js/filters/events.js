/**
 * Dispatches a bar event on window.
 *
 * @param {string} name Event name.
 * @param {Object} detail Event detail.
 * @returns {void}
 */
export function emitEvent(name, detail) {
    window.dispatchEvent(new CustomEvent(name, { detail }));
}

/**
 * Tells whether a message targets the given bar.
 *
 * @param {CustomEvent} event Received event.
 * @param {string} barId Bar identifier.
 * @returns {boolean}
 */
export function isForBar(event, barId) {
    return Boolean(event.detail) && event.detail.barId === barId;
}

/**
 * Listens to named window events, only for one bar.
 *
 * @param {Object<string, string>} names Event names by key.
 * @param {string} barId Bar identifier.
 * @param {Object<string, function(Object): void>} handlers Handlers by key.
 * @returns {function(): void} Function removing the listeners.
 */
export function bindListeners(names, barId, handlers) {
    const bindings = Object.keys(handlers).map((key) => {
        const name = names[key];
        const listener = (event) => {
            if (isForBar(event, barId)) {
                handlers[key](event.detail);
            }
        };

        window.addEventListener(name, listener);

        return { name, listener };
    });

    return () => {
        bindings.forEach(({ name, listener }) => window.removeEventListener(name, listener));
    };
}

/**
 * Listens to the bar commands on window.
 *
 * @param {{id: string, commands: Object<string, string>}} config Bar configuration.
 * @param {Object<string, function(Object): void>} handlers Handlers by command key.
 * @returns {function(): void} Function removing the listeners.
 */
export function bindCommands(config, handlers) {
    return bindListeners(config.commands, config.id, handlers);
}