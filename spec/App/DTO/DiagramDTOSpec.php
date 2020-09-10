<?php

declare(strict_types=1);

namespace spec\App\DTO;

use App\DTO\DiagramDTO;
use App\Entity\Diagram;
use App\Entity\User;
use App\Repository\UserRepository;
use PhpSpec\ObjectBehavior;

class DiagramDTOSpec extends ObjectBehavior
{
    private User $user;

    public function let(): void
    {
        $this->user = new User('test@test.de', '123456', 'test', 0);
        $this->beConstructedWith('test', '{}');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(DiagramDTO::class);
        $this->name->shouldEqual('test');
        $this->data->shouldEqual('{}');
    }

    public function it_should_allow_to_convert_to_entity(UserRepository $userRepository): void
    {
        $entity = $this->toEntity($this->user);
        $entity->shouldHaveType(Diagram::class);
        $entity->getName()->shouldReturn($this->name);
        $entity->getData()->shouldReturn($this->data);
        $entity->getUser()->shouldReturn($this->user);
    }

    public function it_should_allow_to_convert_from_entity(): void
    {
        $diagram = new Diagram('test', '{}', $this->user);
        $this::fromEntity($diagram)->shouldHaveType(DiagramDTO::class);
        $this->name->shouldEqual('test');
        $this->data->shouldEqual('{}');
    }
}
