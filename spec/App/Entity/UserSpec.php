<?php

declare(strict_types=1);

namespace spec\App\Entity;

use App\Entity\Diagram;
use App\Entity\User;
use PhpSpec\ObjectBehavior;

class UserSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('test@test.de', '12345678', 'test', 0);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(User::class);
        $this->getName()->shouldEqual('test');
        $this->getPassword()->shouldEqual('12345678');
        $this->getEmail()->shouldEqual('test@test.de');
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

    public function it_should_be_constructed_with_email(): void
    {
        $this->getEmail()->shouldReturn('test@test.de');
    }

    public function it_should_allow_to_set_email(): void
    {
        $this->setEmail('test1@test.de');
        $this->getEmail()->shouldReturn('test1@test.de');
    }

    public function it_should_allow_to_get_username(): void
    {
        $this->getUsername()->shouldReturn('test@test.de');
    }

    public function it_should_allow_to_set_roles(): void
    {
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];
        $this->setRoles($roles);
        $this->getRoles()->shouldReturn($roles);
    }

    public function it_should_allow_to_get_roles(): void
    {
        $roles = ['ROLE_USER'];
        $this->getRoles()->shouldReturn($roles);
    }

    public function it_should_be_constructed_with_password(): void
    {
        $this->getPassword()->shouldReturn('12345678');
    }

    public function it_should_allow_to_set_password(): void
    {
        $this->setPassword('87654321');
        $this->getPassword()->shouldReturn('87654321');
    }

    public function it_should_generate_random_uuid(): void
    {
        $this->getId()->shouldBeRandomUuid();
    }

    public function it_should_allow_to_add_diagram(): void
    {
        $diagram = new Diagram('test', '{}', $this->getWrappedObject());
        $this->addDiagram($diagram);
        $this->getDiagrams()->shouldContain($diagram);
    }

    public function it_should_allow_to_get_diagrams(): void
    {
        $this->getDiagrams()->shouldHaveCount(0);
    }

    public function it_should_allow_to_remove_diagram(): void
    {
        $diagram = new Diagram('test', '{}', $this->getWrappedObject());
        $this->addDiagram($diagram);
        $this->getDiagrams()->shouldContain($diagram);

        $this->removeDiagram($diagram);
        $this->getDiagrams()->shouldNotContain($diagram);
    }

    public function it_should_not_allow_to_erase_credentials(): void
    {
        $this->eraseCredentials()->shouldReturn(null);
    }
}
