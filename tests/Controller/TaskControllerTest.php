<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testNewTask(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');
        $client->followRedirects();

        $nbDivInit = count($crawler->filter('div.thumbnail'));

        $client->clickLink('Créer une tâche');
        
        $newCrawler = $client->submitForm('Ajouter', [
            'task[title]' => 'test title',
            'task[content]' => 'test content'
        ]);

        $nbDivFinal = count($newCrawler->filter('div.thumbnail'));

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list');
        $this->assertCount(1, $newCrawler->filter('div.alert-success'));
        $this->assertSame($nbDivFinal, $nbDivInit + 1);
    }

    public function testNewTaskBlankTitle(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/create');
        $client->followRedirects();
        
        $client->submitForm('Ajouter', [
            'task[content]' => 'test content'
        ]);

        $this->assertRouteSame('task_create');
    }

    public function testNewTaskBlankContent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/create');
        $client->followRedirects();
        
        $client->submitForm('Ajouter', [
            'task[title]' => 'test title'
        ]);

        $this->assertRouteSame('task_create');
    }

    public function testDeleteTask(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');
        $client->followRedirects();

        $nbDivInit = count($crawler->filter('div.thumbnail'));

        $form = $crawler->filter('div.thumbnail')->first()->filter('form')->last()->form();

        $newCrawler = $client->submit($form);

        $nbDivFinal = count($newCrawler->filter('div.thumbnail'));

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list');
        $this->assertCount(1, $newCrawler->filter('div.alert-success'));
        $this->assertSame($nbDivFinal, $nbDivInit - 1);
    }

    public function testToggleTask(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');
        $client->followRedirects();

        $nbSpanRemoveInit = count($crawler->filter('span.glyphicon-remove'));
        $nbSpanOkInit = count($crawler->filter('span.glyphicon-ok'));

        $form = $crawler->filter('div.thumbnail')->first()->filter('form')->first()->form();

        $newCrawler = $client->submit($form);

        $nbSpanRemoveFinal = count($newCrawler->filter('span.glyphicon-remove'));
        $nbSpanOkFinal = count($newCrawler->filter('span.glyphicon-ok'));

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list');
        $this->assertCount(1, $newCrawler->filter('div.alert-success'));
        $this->assertSame($nbSpanRemoveFinal, $nbSpanRemoveInit - 1);
        $this->assertSame($nbSpanOkFinal, $nbSpanOkInit + 1);
    }

    public function testEditTask(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');
        $client->followRedirects();

        $taskLink = $crawler->filter('div.thumbnail')->first()->filter('a')->first()->link();

        $client->click($taskLink);

        $newCrawler = $client->submitForm('Modifier', [
            'task[title]' => 'test title modif',
            'task[content]' => 'test content modif'
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list');
        $this->assertCount(1, $newCrawler->filter('div.alert-success'));
    }
}