<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\UserAddDTO;
use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserServiceInterface as UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    protected UserRepository $userRepository;

    private EntityManagerInterface $entityManager;

    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * UserService constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        $this->entityManager   = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository  = $userRepository;
    }

    /**
     * Registers a new User from an userDTO after encoding the password and adds the admin role if required.
     */
    public function register(UserAddDTO $userDTO): User
    {
        $user             = $userDTO->toEntity();
        $encodePassword   = $this->passwordEncoder->encodePassword($user, $userDTO->password);

        $user->setPassword($encodePassword);
        if ($userDTO->isAdmin) {
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
