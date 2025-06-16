<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    private $validator;
    private $passwordHasher;

    public function getEntity():User
    {
        self::bootKernel(); 
        $container = static::getContainer(); 
        $this->passwordHasher = $container->get('security.user_password_hasher');

        $user = new User();
        $user->setUsername('Admin test unit');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $user->setEmail('admintestunit@example.com');

        return $user;
    }

    public function assertHasErrors(User $user, int $number):void
    {
        self::bootKernel(); 
        $container = static::getContainer(); 
        $this->validator = $container->get('validator');

        $errors = $this->validator->validate($user);
        
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity():void
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testBlankUsername():void
    {
        $this->assertHasErrors($this->getEntity()->setUsername(''), 1);
    }

    public function testBlankPassword():void
    {
        $this->assertHasErrors($this->getEntity()->setPassword(''), 1);
    }

    public function testBlankEmail():void
    {
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    public function testInvalidEmail():void
    {
        $this->assertHasErrors($this->getEntity()->setEmail('1234'), 1);
    }
}