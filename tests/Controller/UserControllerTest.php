<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $client->followRedirects();
        
        $newCrawler = $client->submitForm('Se connecter', [
            '_username' => 'admin',
            '_password' => 'password'
        ]);


        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('homepage');
    }

    public function testLoginBlankUsername(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $client->followRedirects();
        
        $newCrawler = $client->submitForm('Se connecter', [
            '_password' => 'password'
        ]);

        $this->assertRouteSame('login');
        $this->assertCount(1, $newCrawler->filter('div.alert-danger'));
    }

    public function testLoginBlankPassword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $client->followRedirects();
        
        $newCrawler = $client->submitForm('Se connecter', [
            '_username' => 'admin'
        ]);

        $this->assertRouteSame('login');
        $this->assertCount(1, $newCrawler->filter('div.alert-danger'));
    }

    public function testLoginInvalidCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $client->followRedirects();
        
        $newCrawler = $client->submitForm('Se connecter', [
            '_username' => 'admin1234',
            '_password' => 'password1234'
        ]);

        $this->assertRouteSame('login');
        $this->assertCount(1, $newCrawler->filter('div.alert-danger'));
    }

    public function testRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $client->followRedirects();
        
        $client->submitForm('Se connecter', [
            '_username' => 'admin',
            '_password' => 'password'
        ]);

        $client->clickLink('Créer un utilisateur');

        $this->assertRouteSame('user_create');

        $newCrawler = $client->submitForm('Ajouter', [
            'user[username]' => 'admin_test',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'admintest@example.com'
        ]);

        $this->assertRouteSame('user_list');
        $this->assertCount(1, $newCrawler->filter('div.alert-success'));
    }

    public function testRegisterBlankUsername(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $client->followRedirects();
        
        $client->submitForm('Se connecter', [
            '_username' => 'admin',
            '_password' => 'password'
        ]);

        $client->clickLink('Créer un utilisateur');

        $this->assertRouteSame('user_create');

        $newCrawler = $client->submitForm('Ajouter', [
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'admintest@example.com'
        ]);

        $this->assertRouteSame('user_create');
    }

    public function testRegisterBlankPassword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $client->followRedirects();
        
        $client->submitForm('Se connecter', [
            '_username' => 'admin',
            '_password' => 'password'
        ]);

        $client->clickLink('Créer un utilisateur');

        $this->assertRouteSame('user_create');

        $newCrawler = $client->submitForm('Ajouter', [
            'user[username]' => 'admin_test',
            'user[password][second]' => 'password',
            'user[email]' => 'admintest@example.com'
        ]);

        $this->assertRouteSame('user_create');
    }

    public function testRegisterBlankEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $client->followRedirects();
        
        $client->submitForm('Se connecter', [
            '_username' => 'admin',
            '_password' => 'password'
        ]);

        $client->clickLink('Créer un utilisateur');

        $this->assertRouteSame('user_create');

        $newCrawler = $client->submitForm('Ajouter', [
            'user[username]' => 'admin_test',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
        ]);

        $this->assertRouteSame('user_create');
    }

    public function testRegisterUsernameTaken(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $client->followRedirects();
        
        $client->submitForm('Se connecter', [
            '_username' => 'admin',
            '_password' => 'password'
        ]);

        $client->clickLink('Créer un utilisateur');

        $this->assertRouteSame('user_create');

        $newCrawler = $client->submitForm('Ajouter', [
            'user[username]' => 'admin_test',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'admintest@example.com'
        ]);

        $this->assertRouteSame('user_create');
        $this->assertSelectorTextContains('li', 'There is already an account with this username');
    }

    public function testRegisterEmailTaken(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $client->followRedirects();
        
        $client->submitForm('Se connecter', [
            '_username' => 'admin',
            '_password' => 'password'
        ]);

        $client->clickLink('Créer un utilisateur');

        $this->assertRouteSame('user_create');

        $newCrawler = $client->submitForm('Ajouter', [
            'user[username]' => 'admin_test_2',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'admintest@example.com'
        ]);

        $this->assertRouteSame('user_create');
        $this->assertSelectorTextContains('li', 'There is already an account with this email');
    }

    public function testUserListUnauthentified(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/users');

        $this->assertRouteSame('login');
    }

    public function testUserListRoleAdmin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $client->followRedirects();
        
        $client->submitForm('Se connecter', [
            '_username' => 'admin',
            '_password' => 'password'
        ]);

        $client->request('GET', '/users');

        $this->assertRouteSame('user_list');
    }

    public function testUserListRoleUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $client->followRedirects();
        
        $client->submitForm('Se connecter', [
            '_username' => 'user',
            '_password' => 'password'
        ]);

        $client->request('GET', '/users');

        $this->assertRouteSame('user_list');
    }
}