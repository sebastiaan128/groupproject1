<?php

namespace App\Factory;

use App\Entity\Vragen;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use App\Factory\ProfielFactory;

final class VragenFactory extends PersistentProxyObjectFactory
{
    public function __construct() {}

    #[\Override]
    public static function class(): string
    {
        return Vragen::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'titel' => substr(rtrim(self::faker()->sentence(4), '.'), 0, 45) . '?',
            'beschrijving' => self::faker()->paragraphs(2, true),
            'upvotes' => self::faker()->numberBetween(0, 100),
            'downvotes' => self::faker()->numberBetween(0, 50),
            'views' => self::faker()->numberBetween(1, 500),
            'status' => self::faker()->randomElement(['Open', 'Closed', 'Answering']),
            'profiel' => ProfielFactory::random(),
        ];
    }

    #[\Override]
    protected function initialize(): static
    {
        return $this
        ;
    }
    
}
