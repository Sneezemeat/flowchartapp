<?php

declare(strict_types=1);

namespace spec\App\Service;

use App\DTO\UserAddDTO;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserServiceSpec extends ObjectBehavior
{
    public function let(EntityManager $entityManager, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository): void
    {
        $this->beConstructedWith($entityManager, $passwordEncoder, $userRepository);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(UserService::class);
    }

    public function it_should_allow_to_register(EntityManager $entityManager, UserPasswordEncoderInterface $passwordEncoder): void
    {
        $userDTO = new UserAddDTO('test@test.de', '12345678', 'test', 0, false);

        $passwordEncoder->encodePassword(Argument::any(), $userDTO->password)->willReturn('12345678');
        $passwordEncoder->encodePassword(Argument::any(), $userDTO->password)->shouldBeCalled();
        $entityManager->persist(Argument::any())->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();
        $this->register($userDTO, false);
    }

    public function it_should_allow_to_register_admin(EntityManager $entityManager, UserPasswordEncoderInterface $passwordEncoder): void
    {
        $userDTO = new UserAddDTO('test@test.de', '12345678', 'test', 0, false);
        $passwordEncoder->encodePassword(Argument::any(), $userDTO->password)->willReturn('12345678');
        $passwordEncoder->encodePassword(Argument::any(), $userDTO->password)->shouldBeCalled();
        $entityManager->persist(Argument::any())->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();
        $this->register($userDTO, true);
    }
}
