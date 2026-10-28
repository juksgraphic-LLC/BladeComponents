<?php

namespace Docs\services;

use DateTimeImmutable;

/**
 * Deterministic fake datasets used to demo the dynamic table (no database needed).
 *
 * Single responsibility: build rows. It never searches, filters, sorts or paginates.
 */
final class FakeTableData
{
    public const  TABLE_USERS = 'users';
    public const  TABLE_PRODUCTS = 'products';
    public const  TABLE_ORDERS = 'orders';

    private const USERS_COUNT = 57;
    private const PRODUCTS_COUNT = 43;
    private const ORDERS_COUNT = 120;
    private const REFERENCE_DATE = '2026-01-01';

    private const FIRST_NAMES = [
        'Alice',
        'Bruno',
        'Camille',
        'David',
        'Elodie',
        'Farid',
        'Gaelle',
        'Hugo',
        'Ines',
        'Jonas',
        'Karim',
        'Lea',
        'Mathis',
        'Nadia',
        'Omar',
        'Pauline',
        'Quentin',
    ];
    private const LAST_NAMES = [
        'Martin',
        'Bernard',
        'Dubois',
        'Thomas',
        'Robert',
        'Richard',
        'Petit',
        'Durand',
        'Leroy',
        'Moreau',
        'Simon',
    ];
    private const COMPANIES = ['Acme Corp', 'Globex', 'Initech', 'Umbrella', 'Hooli'];
    private const CITIES = ['Paris', 'Lyon', 'Marseille', 'Montréal', 'Port-au-Prince', 'Dakar', 'Abidjan'];
    private const USER_ROLES = ['admin', 'editor', 'author', 'viewer'];
    private const USER_STATUSES = ['active', 'pending', 'suspended'];

    private const PRODUCT_ADJECTIVES = ['Compact', 'Premium', 'Classic', 'Smart', 'Eco', 'Pro', 'Ultra'];
    private const PRODUCT_NOUNS = ['Lamp', 'Backpack', 'Speaker', 'Notebook', 'Bottle', 'Chair', 'Keyboard', 'Mug'];
    private const PRODUCT_CATEGORIES = ['Electronics', 'Books', 'Home', 'Sports', 'Toys', 'Garden'];

    private const ORDER_STATUSES = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
    private const ORDER_PAYMENT_METHODS = ['card', 'paypal', 'transfer', 'cash'];

    /**
     * Tells whether a fake table exists.
     *
     * @param string $tableName Name of the fake table.
     */
    public function hasTable(string $tableName): bool
    {
        return in_array($tableName, [self::TABLE_USERS, self::TABLE_PRODUCTS, self::TABLE_ORDERS], true);
    }

    /**
     * Returns every row of a fake table (empty array for an unknown table).
     *
     * @param  string $tableName Name of the fake table.
     * @return array<int, array<string, mixed>>
     */
    public function getRows(string $tableName): array
    {
        return match ($tableName) {
            self::TABLE_USERS => $this->buildUserRows(),
            self::TABLE_PRODUCTS => $this->buildProductRows(),
            self::TABLE_ORDERS => $this->buildOrderRows(),
            default => [],
        };
    }

    /** @return array<int, array<string, mixed>> */
    private function buildUserRows(): array
    {
        return array_map(fn(int $id): array => $this->buildUserRow($id), range(1, self::USERS_COUNT));
    }

    /**
     * Builds one user row, with a nested company to demo dotted column keys.
     *
     * @return array<string, mixed>
     */
    private function buildUserRow(int $id): array
    {
        $firstName = self::FIRST_NAMES[$id % count(self::FIRST_NAMES)];
        $lastName = self::LAST_NAMES[$id % count(self::LAST_NAMES)];
        $fullName = $firstName . ' ' . $lastName;

        return [
            'id' => $id,
            'avatar' => $this->buildAvatarDataUri($fullName, $id),
            'name' => $fullName,
            'email' => strtolower($firstName . '.' . $lastName . $id) . '@example.com',
            'website' => 'https://' . strtolower($lastName) . $id . '.example.com',
            'role' =>   self::USER_ROLES[$id % count(self::USER_ROLES)],
            'status' => self::USER_STATUSES[$id % count(self::USER_STATUSES)],
            'city' =>   self::CITIES[$id % count(self::CITIES)],
            'company' => ['name' => self::COMPANIES[$id % count(self::COMPANIES)]],
            'created_at' => $this->buildDate($id * 3),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function buildProductRows(): array
    {
        return array_map(fn(int $id): array => $this->buildProductRow($id), range(1, self::PRODUCTS_COUNT));
    }

    /**
     * Builds one product row.
     *
     * @return array<string, mixed>
     */
    private function buildProductRow(int $id): array
    {
        $stock = ($id * 7) % 60;
        $price =  number_format(5 + (($id * 37) % 480) + (($id * 13) % 100) / 100, 2, '.', '');

        return [
            'id' => $id,
            'sku' => 'SKU-' . str_pad((string) $id, 4, '0', STR_PAD_LEFT),
            'name' => self::PRODUCT_ADJECTIVES[$id % count(self::PRODUCT_ADJECTIVES)]
                . ' ' . self::PRODUCT_NOUNS[$id % count(self::PRODUCT_NOUNS)],
            'category' => self::PRODUCT_CATEGORIES[$id % count(self::PRODUCT_CATEGORIES)],
            'price' => $price,
            'stock' => $stock,
            'rating' => number_format(3 + (($id * 7) % 21) / 10, 1, '.', ''),
            'availability' => $this->resolveAvailability($stock),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function buildOrderRows(): array
    {
        return array_map(fn(int $id): array => $this->buildOrderRow($id), range(1, self::ORDERS_COUNT));
    }

    /**
     * Builds one order row.
     *
     * @return array<string, mixed>
     */
    private function buildOrderRow(int $id): array
    {
        $total = number_format(15 + (($id * 83) % 900) + (($id * 17) % 100) / 100, 2, '.', '');
        return [
            'id' => $id,
            'reference' => 'ORD-' . (10000 + $id),
            'customer' => $this->buildFullName($id * 2),
            'total' => $total,
            'task_progress' => number_format($total / 400 * 100 , 2),
            'status' => self::ORDER_STATUSES[$id % count(self::ORDER_STATUSES)],
            'payment_method' => self::ORDER_PAYMENT_METHODS[$id % count(self::ORDER_PAYMENT_METHODS)],
            'ordered_at' => $this->buildDate($id),
        ];
    }

    /** Returns a full name built from the first and last name pools. */
    private function buildFullName(int $index): string
    {
        return self::FIRST_NAMES[$index % count(self::FIRST_NAMES)]
            . ' ' . self::LAST_NAMES[$index % count(self::LAST_NAMES)];
    }

    /** Returns the availability label matching a stock quantity. */
    private function resolveAvailability(int $stock): string
    {
        return match (true) {
            $stock === 0 => 'unavailable',
            $stock <= 10 => 'limited',
            default => 'available',
        };
    }

    /** Returns the reference date shifted by a number of days, as Y-m-d. */
    private function buildDate(int $daysAfterReference): string
    {
        return (new DateTimeImmutable(self::REFERENCE_DATE))
            ->modify('+' . $daysAfterReference . ' days')
            ->format('Y-m-d');
    }

    /** Returns an SVG avatar with the initials of a name, as a data URI (works offline). */
    private function buildAvatarDataUri(string $fullName, int $seed): string
    {
        [$firstName, $lastName] = explode(' ', $fullName);
        $initials = strtoupper($firstName[0] . $lastName[0]);
        $hue = ($seed * 47) % 360;

        $svg = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80">'
                . '<rect width="80" height="80" fill="hsl(%d,55%%,45%%)"/>'
                . '<text x="50%%" y="50%%" dy=".35em" text-anchor="middle" font-family="sans-serif" font-size="30" fill="#fff">%s</text>'
                . '</svg>',
            $hue,
            $initials,
        );

        return 'data:image/svg+xml;charset=utf-8,' . rawurlencode($svg);
    }
}
