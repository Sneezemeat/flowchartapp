<?php

declare(strict_types=1);

namespace spec\App\Resolver;

use App\Resolver\DTOValueResolver;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTOValueResolverSpec extends ObjectBehavior
{
    public function let(SerializerInterface $serializer, ValidatorInterface $validator): void
    {
        $this->beConstructedWith($serializer, $validator);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(DTOValueResolver::class);
    }

    public function it_should__not_resolve_invalid_type(Request $request, ArgumentMetadata $argument): void
    {
        $request->getContent()->willReturn('{}');
        $argument->getType()->willReturn(null);

        $this->resolve($request, $argument)->shouldThrow(BadMethodCallException::class)->duringCurrent();
    }
}
