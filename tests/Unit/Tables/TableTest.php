<?php

declare(strict_types=1);

use Juksgraphic\BladeComponents\Table\Table;
use Juksgraphic\BladeComponents\Table\TableColumn;
use Juksgraphic\BladeComponents\Table\TableRow;

describe('defaults', function () {
    it('starts with an empty configuration', function () {
        $table = new Table();

        expect($table->getBaseRoute())->toBe('')
            ->and($table->selectable())->toBeFalse()
            ->and($table->perPage)->toBe(5)
            ->and($table->rawHeaders())->toBe([])
            ->and($table->columns())->toBe([]);
    });
});

describe('fluent setters', function () {
    it('returns the same instance to allow chaining', function () {
        $table = new Table();

        expect($table->setBaseRoute('/a'))->toBe($table)
            ->and($table->setEndpoint('/e'))->toBe($table)
            ->and($table->setHeaders([]))->toBe($table)
            ->and($table->setColumns([]))->toBe($table)
            ->and($table->setFilters([]))->toBe($table)
            ->and($table->setWithAction([]))->toBe($table)
            ->and($table->setSelectable())->toBe($table)
            ->and($table->setPerPage(10))->toBe($table)
            ->and($table->setTableId('t'))->toBe($table)
            ->and($table->setLabels([]))->toBe($table);
    });

    it('removes the trailing slash of the base route', function () {
        expect((new Table())->setBaseRoute('/users///')->getBaseRoute())->toBe('/users');
    });

    it('never accepts less than 1 row per page', function () {
        expect((new Table())->setPerPage(0)->perPage)->toBe(1)
            ->and((new Table())->setPerPage(-3)->perPage)->toBe(1)
            ->and((new Table())->setPerPage(20)->perPage)->toBe(20);
    });

    it('enables selection by default when called without argument', function () {
        expect((new Table())->setSelectable()->selectable())->toBeTrue()
            ->and((new Table())->setSelectable(false)->selectable())->toBeFalse();
    });

    it('reindexes the actions', function () {
        $table = (new Table())->setWithAction([3 => 'edit', 7 => 'delete']);

        expect($table->withAction)->toBe(['edit', 'delete']);
    });
});

describe('endpoint', function () {
 
   it('uses the given endpoint', function () {
        $table = (new Table())->setBaseRoute('/users')->setEndpoint('/api/users');

        expect($table->getEndpoint())->toBe('/api/users');
    });
});

describe('identifiers', function () {
    it('uses the given table id, trimmed', function () {
        expect((new Table())->setTableId('  users  ')->id())->toBe('users');
    });

    it('derives a stable id from the endpoint', function () {
        $table = (new Table())->setEndpoint('/users/table');

        expect($table->id())->toBe('jkc-table-' . hash('crc32b', '/users/table'))
            ->and($table->id())->toBe($table->id());
    });

    it('gives different ids to different endpoints', function () {
        $a = (new Table())->setEndpoint('/a/table')->id();
        $b = (new Table())->setEndpoint('/b/table')->id();

        expect($a)->not->toBe($b);
    });

    it('derives the id when the table id is blank', function () {
        $table = (new Table())->setEndpoint('/x')->setTableId('   ');

        expect($table->id())->toStartWith('jkc-table-');
    });

    it('builds a delete modal id safe for HTML attributes', function () {
        $table = (new Table())->setTableId('my table!<x>');

        expect($table->deleteModalId())->toBe('mytablex-delete');
    });
});

describe('actions', function () {
    it('checks the enabled actions', function () {
        $table = (new Table())->setWithAction(['view', 'delete']);

        expect($table->hasAction('view'))->toBeTrue()
            ->and($table->hasAction('edit'))->toBeFalse();
    });

    it('builds a TableRow with the base route and the actions', function () {
        $row = (new Table())->setBaseRoute('/users')->setWithAction(['edit'])->row();

        expect($row)->toBeInstanceOf(TableRow::class)
            ->and($row->canNavigate())->toBeTrue()
            ->and($row->hasAction('edit'))->toBeTrue()
            ->and($row->hasAction('view'))->toBeFalse();
    });
});

describe('columns', function () {
    it('uses headers when both headers and columns are set', function () {
        $table = (new Table())
            ->setHeaders([['key' => 'a']])
            ->setColumns([['key' => 'b']]);

        expect($table->rawHeaders())->toBe([['key' => 'a']]);
    });

    it('falls back to columns when there are no headers', function () {
        $table = (new Table())->setColumns([['key' => 'b']]);

        expect($table->rawHeaders())->toBe([['key' => 'b']]);
    });

    it('builds one TableColumn per definition', function () {
        $columns = (new Table())
            ->setColumns([['key' => 'name'], ['key' => 'email', 'type' => 'link']])
            ->columns();

        expect($columns)->toHaveCount(2)
            ->and($columns[0])->toBeInstanceOf(TableColumn::class)
            ->and($columns[0]->type())->toBe('text')
            ->and($columns[1]->type())->toBe('link');
    });
});

describe('serialization', function () {
    it('exposes the configuration used by the JavaScript component', function () {
        $data = (new Table())
            ->setBaseRoute('/users')
            ->setHeaders([['key' => 'name']])
            ->setFilters([['key' => 'status', 'options' => ['a' => 'A']]])
            ->setWithAction(['view'])
            ->setSelectable()
            ->setPerPage(7)
            ->setLabels(['na' => 'n/a'])
            ->toArray();

        expect($data)->toHaveKeys([
            'endpoint',
            'headers',
            'filters',
            'withAction',
            'selectable',
            'perPage',
            'perPageOptions',
            'labels',
        ])
            ->and($data['endpoint'])->toBe('/users')
            ->and($data['headers'])->toBe([['key' => 'name']])
            ->and($data['filters'][0]['options'])->toBe([['value' => 'a', 'label' => 'A']])
            ->and($data['withAction'])->toBe(['view'])
            ->and($data['selectable'])->toBeTrue()
            ->and($data['perPage'])->toBe(7)
            ->and($data['perPageOptions'])->toBe([5, 7, 10, 25, 50, 100])
            ->and($data['labels']['na'])->toBe('n/a')
            ->and($data['labels']['loadingError'])->toBe('Failed to load the data');
    });

    it('produces JSON equal to the array', function () {
        $table = (new Table())->setBaseRoute('/users')->setPerPage(10);

        expect(json_decode($table->toJson(), true))->toBe($table->toArray());
    });

    it('escapes the JSON so it is safe inside an HTML attribute', function () {
        $json = (new Table())
            ->setLabels(['na' => '<script>alert("x")</script> & \'y\''])
            ->toJson();

        expect($json)->not->toContain('<')
            ->and($json)->not->toContain('>')
            ->and($json)->not->toContain('"x"')
            ->and($json)->not->toContain("'")
            ->and(json_decode($json, true)['labels']['na'])
            ->toBe('<script>alert("x")</script> & \'y\'');
    });

    it('keeps unicode characters readable', function () {
        expect((new Table())->setLabels(['na' => 'Élodie'])->toJson())->toContain('Élodie');
    });

    it('replaces invalid UTF-8 instead of failing', function () {
        $json = (new Table())->setLabels(['na' => "bad\xB1utf8"])->toJson();

        expect($json)->not->toBe('')
            ->and(json_decode($json, true))->toBeArray();
    });
});
