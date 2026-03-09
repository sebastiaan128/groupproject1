<?php

namespace App\Factory;

use App\Entity\Vragen;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use App\Factory\ProfielFactory;

/**
 * @extends PersistentProxyObjectFactory<Vragen>
 */
final class VragenFactory extends PersistentProxyObjectFactory
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
        return Vragen::class;
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
            'titel' => substr(rtrim(self::faker()->sentence(4), '.'), 0, 45) . '?',
            'beschrijving' => self::faker()->paragraphs(2, true),
            'upvotes' => self::faker()->numberBetween(0, 100),
            'downvotes' => self::faker()->numberBetween(0, 50),
            'views' => self::faker()->numberBetween(1, 500),
            'status' => self::faker()->randomElement(['Open', 'Closed', 'Answering']),
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
            // ->afterInstantiate(function(Vragen $vragen): void {})
        ;
    }
    
}
