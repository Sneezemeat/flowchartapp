<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Exception\ViolationException;
use App\Repository\DiagramRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
 */
final class DiagramControllerTest extends WebTestCase
{
    public function testGetDiagram(): void
    {
        $client = static::createClient();

        $diagramRepository = static::$container->get(DiagramRepository::class);
        $userRepository    = static::$container->get(UserRepository::class);

        $diagram = $diagramRepository->findOneBy(['name' => 'Test']);
        $user    = $userRepository->findOneBy(['email' => 'test@test.de']);

        $client->loginUser($user);
        $client->request('GET', '/diagram/'.$diagram->getId());

        $response = \json_decode($client->getResponse()->getContent());
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame('Test', $response->name);
        static::assertSame('test@test.de', $response->user->email);
        static::assertIsString($response->data);
    }

    public function testAddDiagram(): void
    {
        $client = static::createClient();

        $userRepository    = static::$container->get(UserRepository::class);
        $diagramRepository = static::$container->get(DiagramRepository::class);
        $user              = $userRepository->findOneBy(['email' => 'test@test.de']);
        $client->loginUser($user);

        $content         = [];
        $content['name'] = 'Test1';
        $content['data'] = '{}';
        $jsonContent     = \json_encode($content);
        $client->request('POST', '/diagram/add', [], [], [], $jsonContent);
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertNotNull($diagramRepository->findOneBy(['name' => 'Test1']));
    }

    public function testAddDiagramErrors(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $this->expectException(ViolationException::class);
        $userRepository    = static::$container->get(UserRepository::class);
        $user              = $userRepository->findOneBy(['email' => 'test@test.de']);
        $client->loginUser($user);

        $content         = [];
        $content['name'] = 'T';
        $content['data'] = '{}';
        $jsonContent     = \json_encode($content);

        $client->request('POST', '/diagram/add', [], [], [], $jsonContent);

        $response = \json_decode($client->getResponse()->getContent());
        static::assertSame(400, $client->getResponse()->getStatusCode());
        static::assertCount(1, $response->violations);
    }

    public function testUpdateDiagram(): void
    {
        $client = static::createClient();

        $userRepository    = static::$container->get(UserRepository::class);
        $diagramRepository = static::$container->get(DiagramRepository::class);

        $diagram = $diagramRepository->findOneBy(['name' => 'Test']);
        $user    = $userRepository->findOneBy(['email' => 'test@test.de']);
        $client->loginUser($user);

        $content         = [];
        $content['name'] = 'Test1';
        $content['data'] = '{Update}';
        $jsonContent     = \json_encode($content);
        $client->request('PUT', '/diagram/update/'.$diagram->getId(), [], [], [], $jsonContent);

        $response = \json_decode($client->getResponse()->getContent());
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame('Test1', $response->name);
        static::assertSame('test@test.de', $response->user->email);
        static::assertSame('{Update}', $response->data);
    }

    public function testUpdateDiagramErrors(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $this->expectException(ViolationException::class);
        $userRepository    = static::$container->get(UserRepository::class);
        $diagramRepository = static::$container->get(DiagramRepository::class);

        $diagram = $diagramRepository->findOneBy(['name' => 'Test']);
        $user    = $userRepository->findOneBy(['email' => 'test@test.de']);
        $client->loginUser($user);

        $content         = [];
        $content['name'] = 'T';
        $content['data'] = '{Update}';
        $jsonContent     = \json_encode($content);
        $client->request('PUT', '/diagram/update/'.$diagram->getId(), [], [], [], $jsonContent);

        $response = \json_decode($client->getResponse()->getContent());
        static::assertSame(400, $client->getResponse()->getStatusCode());
        static::assertCount(1, $response->violations);
    }

    public function testRemoveDiagram(): void
    {
        $client = static::createClient();

        $userRepository    = static::$container->get(UserRepository::class);
        $diagramRepository = static::$container->get(DiagramRepository::class);

        $diagram = $diagramRepository->findOneBy(['name' => 'Test']);
        $user    = $userRepository->findOneBy(['email' => 'test@test.de']);
        $client->loginUser($user);

        $client->request('DELETE', '/diagram/remove/'.$diagram->getId());
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertNull($diagramRepository->find($diagram->getId()));
    }

    public function testGetAll(): void
    {
        $client = static::createClient();

        $userRepository    = static::$container->get(UserRepository::class);
        $user              = $userRepository->findOneBy(['email' => 'test@test.de']);
        $client->loginUser($user);

        $client->request('GET', '/diagram/all/1');
        $response = \json_decode($client->getResponse()->getContent());
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame(1, $response->max);
        static::assertSame(5, $response->limit);
        static::assertCount(1, $response->diagrams);
    }
}
