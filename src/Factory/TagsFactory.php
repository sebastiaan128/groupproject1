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
        $tags = [
            'PHP', 'JavaScript', 'Python', 'Java', 'C#', 'C++', 'Rust',
            'HTML', 'CSS', 'React', 'Vue', 'Angular', 'Node.js',
            'Laravel', 'Symfony', 'Django', 'Spring Boot',
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis',
            'Bootstrap', 'Tailwind', 'SCSS',
            'TypeScript', 'Git', 'Docker', 'AWS', 'Azure',
            'REST API', 'GraphQL', 'Agile', 'DevOps',
            'Web Development', 'Mobile Development', 'Data Science',
            'Machine Learning', 'AI', 'Cloud Computing'
        ];

        return [
            'naam' => self::faker()->randomElement($tags),
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
