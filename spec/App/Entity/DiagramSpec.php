<?php

declare(strict_types=1);

namespace spec\App\Entity;

use App\Entity\Diagram;
use App\Entity\User;
use PhpSpec\ObjectBehavior;

class DiagramSpec extends ObjectBehavior
{
    private User $user;

    public function let(): void
    {
        $this->user = new User('test@test.de', '123456', 'test', 0);
        $this->beConstructedWith('test', '{}', $this->user);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Diagram::class);
        $this->getName()->shouldEqual('test');
        $this->getData()->shouldEqual('{}');
        $this->getUser()->shouldEqual($this->user);
    }

    public function it_should_be_constructed_with_name(): void
    {
        $this->getName()->shouldReturn('test');
    }

    public function it_should_allow_to_set_name(): void
    {
        $this->setName('test');
        $this->getName()->shouldReturn('test');
    }

    public function it_should_be_constructed_with_data(): void
    {
        $this->getData()->shouldReturn('{}');
    }

    public function it_should_allow_to_set_data(): void
    {
        $this->setData('{}');
        $this->getData()->shouldReturn('{}');
    }

    public function it_should_generate_random_uuid(): void
    {
        $this->getId()->shouldBeRandomUuid();
    }

    public function it_should_allow_to_set_user(): void
    {
        $user = new User('test1@test.de', '123456', 'test1', 0);
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }
}
