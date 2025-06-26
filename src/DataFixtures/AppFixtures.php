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
                    'email' => 'admin@example.com',
                    'roles' => ['ROLE_ADMIN']
                ];
            }
        );
        UserFactory::createOne(
            static function () {
                return [
                    'username' => 'user',
                    'email' => 'user@example.com',
                    'roles' => ['ROLE_USER']
                ];
            }
        );
        UserFactory::createOne(
            static function () {
                return [
                    'username' => 'anonyme',
                    'email' => 'anonyme@example.com',
                    'roles' => ['ROLE_USER']
                ];
            }
        );
        UserFactory::createMany(5);
        TaskFactory::createMany(10, static function () {
            return [
                'author' => UserFactory::random()
            ];
        });
        TaskFactory::createMany(5, static function () {
            return [
                'author' => UserFactory::find(['username' => 'anonyme'])
            ];
        });

        $manager->flush();
    }
}
