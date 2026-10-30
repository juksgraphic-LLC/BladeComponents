<?php

declare(strict_types=1);

use Juksgraphic\BladeComponents\Table\TableCell;

describe('valueExpr', function () {
    it('compiles a simple key', function () {
        expect((new TableCell(['key' => 'name']))->valueExpr())->toBe('item?.name');
    });

    it('compiles a dot-notated path with optional chaining', function () {
        expect((new TableCell(['key' => 'user.profile.name']))->valueExpr())
            ->toBe('item?.user?.profile?.name');
    });

    it('uses bracket access for segments that are not valid identifiers', function () {
        expect((new TableCell(['key' => 'first-name']))->valueExpr())
            ->toBe('item?.["first-name"]');
    });

    it('keeps $ and _ in identifiers', function () {
        expect((new TableCell(['key' => '_id.$ref']))->valueExpr())
            ->toBe('item?._id?.$ref');
    });

    it('returns an empty string literal when the key is missing or blank', function () {
        expect((new TableCell([]))->valueExpr())->toBe("''")
            ->and((new TableCell(['key' => '   ']))->valueExpr())->toBe("''");
    });

    it('ignores empty segments', function () {
        expect((new TableCell(['key' => 'user..name']))->valueExpr())
            ->toBe('item?.user?.name');
    });

    it('prefers the override key over the definition key', function () {
        $cell = new TableCell(['key' => 'name']);

        expect($cell->valueExpr('email'))->toBe('item?.email')
            ->and($cell->valueExpr(3))->toBe('item?.["3"]');
    });

    it('cannot be used to inject code through the key', function () {
        $expr = (new TableCell(['key' => "a');alert(1);//"]))->valueExpr();

        expect($expr)->toBe('item?.["a\u0027);alert(1);\/\/"]');
    });
});

describe('avatarExpr', function () {
    it('defaults to the avatar key', function () {
        expect((new TableCell([]))->avatarExpr())->toBe('item?.avatar');
    });

    it('uses the configured avatarKey', function () {
        expect((new TableCell(['avatarKey' => 'user.photo']))->avatarExpr())
            ->toBe('item?.user?.photo');
    });
});

describe('subtitle', function () {
    it('compiles the subtitle key', function () {
        $cell = new TableCell(['subtitleKey' => 'email']);

        expect($cell->hasSubtitle())->toBeTrue()
            ->and($cell->subtitleExpr())->toBe('item?.email');
    });

    it('is empty when no subtitle key is set', function () {
        $cell = new TableCell([]);

        expect($cell->hasSubtitle())->toBeFalse()
            ->and($cell->subtitleExpr())->toBe('');
    });

    it('treats a blank or non-string key as no subtitle', function () {
        expect((new TableCell(['subtitleKey' => '  ']))->hasSubtitle())->toBeFalse()
            ->and((new TableCell(['subtitleKey' => 12]))->hasSubtitle())->toBeFalse()
            ->and((new TableCell(['subtitleKey' => '  ']))->subtitleExpr())->toBe('');
    });
});

describe('progressClass', function () {
    it('defaults to the primary bar', function () {
        expect((new TableCell([]))->progressClass())->toBe('bg-primary');
    });

    it('resolves the bar class of a variant', function (string $variant, string $class) {
        expect((new TableCell(['variant' => $variant]))->progressClass())->toBe($class);
    })->with([
        ['success', 'bg-emerald-500'],
        ['warning', 'bg-amber-500'],
        ['danger', 'bg-rose-500'],
        ['info', 'bg-sky-500'],
        ['primary', 'bg-primary'],
    ]);

    it('falls back to primary for neutral (no bar color) and unknown variants', function () {
        expect((new TableCell(['variant' => 'neutral']))->progressClass())->toBe('bg-primary')
            ->and((new TableCell(['variant' => 'nope']))->progressClass())->toBe('bg-primary');
    });
});

describe('badgeMapJson', function () {
    it('maps each value to the badge classes of its variant', function () {
        $cell = new TableCell(['variants' => ['active' => 'success', 'banned' => 'danger']]);
        $data = json_decode($cell->badgeMapJson(), true);

        expect($data['map']['active'])->toContain('emerald')
            ->and($data['map']['banned'])->toContain('rose')
            ->and($data['map'])->toHaveCount(2);
    });

    it('uses the neutral badge as default variant', function () {
        $data = json_decode((new TableCell([]))->badgeMapJson(), true);

        expect($data['map'])->toBe([])
            ->and($data['default'])->toContain('bg-surface-alt');
    });

    it('uses the configured default variant', function () {
        $data = json_decode((new TableCell(['defaultVariant' => 'info']))->badgeMapJson(), true);

        expect($data['default'])->toContain('sky-500');
    });

    it('falls back to neutral for unknown variant names', function () {
        $cell = new TableCell(['variants' => ['x' => 'unknown'], 'defaultVariant' => 'unknown']);
        $data = json_decode($cell->badgeMapJson(), true);

        expect($data['map']['x'])->toContain('bg-surface-alt')
            ->and($data['default'])->toContain('bg-surface-alt');
    });

    it('ignores a non-array variants definition', function () {
        $data = json_decode((new TableCell(['variants' => 'oops']))->badgeMapJson(), true);

        expect($data['map'])->toBe([]);
    });

    it('escapes characters that are unsafe inside an HTML attribute', function () {
        $json = (new TableCell(['variants' => ['<b>"x"&\'y\'' => 'success']]))->badgeMapJson();

        expect($json)->not->toContain('<')
            ->and($json)->not->toContain('>')
            ->and($json)->not->toContain("'")
            ->and(json_decode($json, true)['map'])->toHaveKey('<b>"x"&\'y\'');
    });
});
