<?php

namespace App\Factory;

use App\Entity\Profiel;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class ProfielFactory extends PersistentProxyObjectFactory
{
    public function __construct() {}

    #[\Override]
    public static function class(): string
    {
        return Profiel::class;
    }

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
            'bio' => substr(self::faker()->sentence(10, true), 0, 249),
        ];
    }

    #[\Override]
    protected function initialize(): static
    {
        return $this
        ;
    }
    
}
