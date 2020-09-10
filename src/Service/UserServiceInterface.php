<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\UserAddDTO;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Registers a new user.
     *
     * @return mixed
     */
    public function register(UserAddDTO $userDTO);
}
