<?php

namespace App\Factory;

use App\Entity\Tags;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Tags>
 */
final class TagsFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Tags::class;
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
            'bootstrap' => self::faker()->boolean(),
            'c' => self::faker()->boolean(),
            'cplus' => self::faker()->boolean(),
            'csharp' => self::faker()->boolean(),
            'css' => self::faker()->boolean(),
            'html' => self::faker()->boolean(),
            'java' => self::faker()->boolean(),
            'javascript' => self::faker()->boolean(),
            'laravel' => self::faker()->boolean(),
            'mysql' => self::faker()->boolean(),
            'nodejs' => self::faker()->boolean(),
            'php' => self::faker()->boolean(),
            'python' => self::faker()->boolean(),
            'react' => self::faker()->boolean(),
            'rust' => self::faker()->boolean(),
            'scss' => self::faker()->boolean(),
            'symfony' => self::faker()->boolean(),
            'tailwind' => self::faker()->boolean(),
            'typescript' => self::faker()->boolean(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Tags $tags): void {})
        ;
    }
}
