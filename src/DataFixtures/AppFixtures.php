<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Diagram;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User('test@test.de', '12345678', 'Test', 0);
        $manager->persist($user);
        $diagram = new Diagram('Test', '{}', $user);
        $manager->persist($diagram);

        $manager->flush();
    }
}
