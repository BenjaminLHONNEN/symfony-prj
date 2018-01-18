<?php

namespace GameBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testTable()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertContains('Name', $client->getResponse()->getContent());
    }

    public function testGamesList()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertContains('Hearts of Iron IV', $client->getResponse()->getContent());
    }


    public function testGamesDetail()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/game/detail/1');
        $this->assertContains('Hearts of Iron IV', $client->getResponse()->getContent());
    }

    public function testConnexion()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler
            ->filter('a:contains("Sign in")')
            ->eq(0)
            ->link()
        ;
        $crawler = $client->click($link);

        $this->assertContains('Sign In', $client->getResponse()->getContent());

        $link = $crawler
            ->filter('a:contains("Sign up")')
            ->eq(0)
            ->link()
        ;
        $crawler = $client->click($link);

        $this->assertContains('Sign Up', $client->getResponse()->getContent());
    }

    public function testIsSignIn()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler
            ->filter('a:contains("Sign in")')
            ->eq(0)
            ->link()
        ;
        $crawler = $client->click($link);

        $this->assertContains('Sign In', $client->getResponse()->getContent());

        $form = $crawler->selectButton('username')->form(array(
            'name' => 'Ryan',
        ));


        $this->assertContains('benjamin.lhonnen@ynov.com', $client->getResponse()->getContent());
    }
}
