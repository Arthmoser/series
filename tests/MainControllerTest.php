<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Serie\'s detail');
    }

    public function testCreateSerieIsWorkingIfNotLogged(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/serie/add');

        $this->assertResponseRedirects('/login', 302);

    }

    public function testCreateSerieIsWorking(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/serie/add');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'arthur.moser@hotmail.fr']);

        $client->loginUser($user);

        $this->assertResponseRedirects('/login', 302);

    }

}
