<?php

declare(strict_types=1);

namespace App\Repository;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface DiagramRepositoryInterface.
 */
interface UserRepositoryInterface
{
    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void;
}
