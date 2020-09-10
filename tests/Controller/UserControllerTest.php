<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Exception\ViolationException;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
 */
final class UserControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();

        $content                          = [];
        $content['email']                 = 'test1@test.de';
        $content['password']              = '12345678';
        $content['passwordConfirmation']  = '12345678';
        $content['name']                  = 'Test1';
        $content['isAdmin']               = false;
        $content['level']                 = 0;
        $jsonContent                      = \json_encode($content);
        $client->request('POST', '/register', [], [], [], $jsonContent);

        $response = \json_decode($client->getResponse()->getContent());
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame('test1@test.de', $response->content->email);
    }

    public function testRegisterPasswordMismatch(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $this->expectException(ViolationException::class);

        $content                          = [];
        $content['email']                 = 'test1@test.de';
        $content['password']              = '12345678';
        $content['passwordConfirmation']  = '87654321';
        $content['name']                  = 'Test1';
        $content['isAdmin']               = false;
        $content['level']                 = 0;
        $jsonContent                      = \json_encode($content);
        $client->request('POST', '/register', [], [], [], $jsonContent);

        $response = \json_decode($client->getResponse()->getContent());
        static::assertSame(400, $client->getResponse()->getStatusCode());
        static::assertSame('Password confirmation is not equal to password', $response->detail);
    }

    public function testRegisterErrors(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $this->expectException(ViolationException::class);

        $content                          = [];
        $content['email']                 = 'test1@test.de';
        $content['password']              = '12345678';
        $content['passwordConfirmation']  = '12345678';
        $content['name']                  = 'Te';
        $content['isAdmin']               = false;
        $content['level']                 = 0;
        $jsonContent                      = \json_encode($content);
        $client->request('POST', '/register', [], [], [], $jsonContent);

        $response = \json_decode($client->getResponse()->getContent());
        static::assertSame(400, $client->getResponse()->getStatusCode());
        static::assertCount(1, $response->violations);
    }

    public function testRegisterUserExists(): void
    {
        $client = static::createClient();

        $content                          = [];
        $content['email']                 = 'test@test.de';
        $content['password']              = '12345678';
        $content['passwordConfirmation']  = '12345678';
        $content['name']                  = 'Test';
        $content['isAdmin']               = false;
        $content['level']                 = 0;
        $jsonContent                      = \json_encode($content);
        $client->request('POST', '/register', [], [], [], $jsonContent);

        $response = \json_decode($client->getResponse()->getContent());
        static::assertSame(400, $client->getResponse()->getStatusCode());
        static::assertSame('Es existiert bereits ein Nutzer mit dieser E-Mail!', $response->error);
    }

    public function testGetProfile(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user           = $userRepository->findOneBy(['email' => 'test@test.de']);
        $client->loginUser($user);

        $client->request('GET', '/profile');
        $response = \json_decode($client->getResponse()->getContent());
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame('test@test.de', $response->email);
    }
}
