<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Table;

/**
 * Handles row-level attributes, routing strategies, and row action states.
 */
final class TableRow
{
    /**
     * @param array<int, mixed> $actions
     */
    public function __construct(
        private readonly string $baseRoute,
        private readonly array $actions,
    ) {
    }

    /**
     * Determines whether client-side navigation via baseRoute is active.
     */
    public function canNavigate(): bool
    {
        return $this->baseRoute !== '';
    }

    /**
     * Indicates if a specific action (view, edit, delete) is allowed for the row.
     */
    public function hasAction(string $name): bool
    {
        return in_array($name, $this->actions, true);
    }

    /**
     * Returns whether any row actions are configured.
     */
    public function hasAnyAction(): bool
    {
        return ! empty($this->actions);
    }

    /**
     * Returns the HTML tag to use for action triggers (link vs button).
     */
    public function actionTag(): string
    {
        return $this->canNavigate() ? 'a' : 'button';
    }

    /**
     * Generates Alpine attributes for the view action trigger.
     */
    public function viewAttrs(): string
    {
        return $this->canNavigate()
            ? ':href="rowUrl(item)" @click="follow($event, \'view\', item)"'
            : 'type="button" x-on:click="triggerView(item)"';
    }

    /**
     * Generates Alpine attributes for the edit action trigger.
     */
    public function editAttrs(): string
    {
        return $this->canNavigate()
            ? ':href="editUrl(item)" @click="follow($event, \'edit\', item)"'
            : 'type="button" x-on:click="triggerEdit(item)"';
    }
}
