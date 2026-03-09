<?php

namespace App\Factory;

use App\Entity\Antwoorden;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use App\Factory\VragenFactory;
use App\Factory\ProfielFactory;

/**
 * @extends PersistentProxyObjectFactory<Antwoorden>
 */
final class AntwoordenFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct() {}

    #[\Override]
    public static function class(): string
    {
        return Antwoorden::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
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

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Antwoorden $antwoorden): void {})
        ;
    }
}
