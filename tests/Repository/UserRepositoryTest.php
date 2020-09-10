<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @internal
 * @coversNothing
 */
final class UserRepositoryTest extends KernelTestCase
{
    /**
     * var \Doctrine\ORM\EntityManager.
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testUpgradePassword(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@test.de']);
        $this->entityManager->getRepository(User::class)->upgradePassword($user, '87654321');
        static::assertSame('87654321', $user->getPassword());
    }

    public function testUpgradePasswordNoUserClass(): void
    {
        $user = new class() implements UserInterface {
            public function getRoles(): void
            {
            }

            public function getPassword(): void
            {
            }

            public function getSalt(): void
            {
            }

            public function getUsername(): void
            {
            }

            public function eraseCredentials(): void
            {
            }
        };
        $this->expectException(UnsupportedUserException::class);
        $this->entityManager->getRepository(User::class)->upgradePassword($user, '87654321');
    }
}
