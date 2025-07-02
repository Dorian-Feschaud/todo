<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;

class TaskTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    public function getEntity():Task
    {
        $user = new User();
        $task = new Task();
        $task->setTitle('Test title');
        $task->setContent('Test content');
        $task->setAuthor($user);

        return $task;
    }

    public function assertHasErrors(Task $task, int $number):void
    {
        self::bootKernel(); 
        $container = static::getContainer(); 
        $this->validator = $container->get('validator');

        $errors = $this->validator->validate($task);
        
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

    public function testInvalidBlankTitleEntity():void
    {
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }

    public function testInvalidBlankContentEntity():void
    {
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }
}