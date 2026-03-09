<?php

namespace App\Factory;

use App\Entity\Vragen;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

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
            'beschrijving' => self::faker()->realText(50),
            'downvotes' => self::faker()->numberBetween(1, 50),
            'status' => self::faker()->randomElement(['open', 'closed']),
            'titel' => rtrim(self::faker()->sentence(6), '.') . '?',
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
