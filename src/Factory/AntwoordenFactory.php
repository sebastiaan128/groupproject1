<?php

namespace App\Factory;

use App\Entity\Antwoorden;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use App\Factory\VragenFactory;
use App\Factory\ProfielFactory;

final class AntwoordenFactory extends PersistentProxyObjectFactory
{
    public function __construct() {}

    #[\Override]
    public static function class(): string
    {
        return Antwoorden::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'beschrijving' => substr(self::faker()->paragraph(2), 0, 250),
            'upvotes' => self::faker()->numberBetween(0, 50),
            'downvotes' => self::faker()->numberBetween(0, 20),
            'vraag' => VragenFactory::random(),
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
