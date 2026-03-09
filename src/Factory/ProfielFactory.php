<?php

namespace App\Factory;

use App\Entity\Profiel;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Profiel>
 */
final class ProfielFactory extends PersistentProxyObjectFactory
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
        return Profiel::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        $studies = [
            "Informatica",
            "HBO-ICT",
            "HBO-OPEN-ICT",
            "Creative Business",
            "Communicatie",
            "Commerciële Economie",
            "Bedrijfskunde",
            "Finance & Control",
            "Verpleegkunde",
            "Social Work",
            "Leraar Basisonderwijs (PABO)",
            "Technische Bedrijfskunde",
            "Built Environment",
            "Game Development",
            "Software Development"
        ];

        return [
            'name' => self::faker()->firstName() . ' ' . self::faker()->lastName(),
            'email' => self::faker()->unique()->safeEmail(),
            'studie' => self::faker()->randomElement($studies),
            'jaar' => self::faker()->numberBetween(1, 4),
            'bio' => self::faker()->sentence(10, true),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Profiel $profiel): void {})
        ;
    }
    
}
