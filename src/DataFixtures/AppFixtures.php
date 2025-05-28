<?php

namespace App\DataFixtures;

use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(
            static function () {
                return [
                    'username' => 'admin',
                    'email' => 'admin@example.com'
                ];
            }
        );
        UserFactory::createMany(5);
        TaskFactory::createMany(10);

        $manager->flush();
    }
}
