<?php

namespace App\Factory;

use App\Entity\Tags;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class TagsFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Tags::class;
    }

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

    #[\Override]
    protected function initialize(): static
    {
        return $this
        ;
    }
    
}
