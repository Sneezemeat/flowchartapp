<?php

declare(strict_types=1);

namespace spec\App\DTO;

use App\DTO\UserDTO;
use App\Entity\Diagram;
use App\Entity\User;
use App\Repository\UserRepository;
use PhpSpec\ObjectBehavior;

class UserDTOSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('test@test.de', '12345678', 'test', 0);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(UserDTO::class);
        $this->name->shouldEqual('test');
        $this->email->shouldEqual('test@test.de');
        $this->password->shouldEqual('12345678');
    }

    public function it_should_allow_to_convert_to_entity(UserRepository $userRepository): void
    {
        $user           = new User('test@test.de', '12345678', 'test', 0);
        $diagram        = new Diagram('test', '{}', $user);
        $this->diagrams = [$diagram];
        $entity         = $this->toEntity();
        $entity->shouldHaveType(User::class);
        $entity->getName()->shouldReturn('test');
        $entity->getEmail()->shouldReturn('test@test.de');
        $entity->getPassword()->shouldReturn('12345678');
        $entity->getDiagrams()[0]->shouldEqual($diagram);
    }
}
