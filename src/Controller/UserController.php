<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\UserAddDTO;
use App\DTO\UserDTO;
use App\Entity\User;
use App\Listener\ApiResponse;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController.
 */
class UserController extends AbstractController
{
    private UserService $userService;

    private ValidatorInterface $validator;

    /**
     * UserController constructor.
     */
    public function __construct(UserService $userService, ValidatorInterface $validator)
    {
        $this->userService = $userService;
        $this->validator   = $validator;
    }

    /**
     * Registers a new User after validating the created userDTO and if the password confirmation was correct.
     *
     * @Route("/register", name="api_register", methods={"POST"})
     */
    public function register(UserAddDTO $userDTO): ApiResponse
    {
        try {
            $content = ['content' => $this->userService->register($userDTO)];

            return new ApiResponse($content, 200);
        } catch (UniqueConstraintViolationException $exception) {
            $content = ['error' => 'Es existiert bereits ein Nutzer mit dieser E-Mail!'];

            return new ApiResponse($content, 400);
        }
    }

    /**
     * Returns the current logged in user.
     *
     * @Route("/profile", name="api_profile")
     * @IsGranted("ROLE_USER")
     */
    public function getProfile(): ?User
    {

        $user = $this->getUser();

        if ($user instanceof User) {
            return $user;
        }

        return null;
    }

    /**
     * Increases level of user by one.
     *
     * @Route("/user/level/increase", name="api_user_increase_level", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function increaseLevel(UserRepository $repository): ?User
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $user->increaseLevel();
            $repository->updateUser($user);
        }

        if ($user instanceof User) {
            return $user;
        }

        return null;
    }

    /**
     * set level of user.
     *
     * @Route("/user/level/set", name="api_user_set_level", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @param Request        $request
     * @param UserRepository $repository
     *
     * @return User|null
     */
    public function setLevel(Request $request, UserRepository $repository): ?User
    {
        $user  = $this->getUser();
        $level = (int) ($request->request->get('level'));
        if ($user instanceof User) {
            $user->setLevel($level);
            $repository->updateUser($user);
        }

        if ($user instanceof User) {
            return $user;
        }

        return null;
    }

    /**
     * set level of user.
     *
     * @Route("/user/usedMobile", name="api_user_set_used_mobile", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @param UserRepository $repository
     *
     * @return User|null
     */
    public function setUsedMobile(UserRepository $repository): ?User
    {
        $user  = $this->getUser();
        if ($user instanceof User) {
            $user->setUsedMobile(true);
            $repository->updateUser($user);
        }

        if ($user instanceof User) {
            return $user;
        }

        return null;
    }
}
