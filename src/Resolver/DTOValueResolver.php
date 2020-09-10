<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Exception\ViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DTOValueResolver.
 */
class DTOValueResolver implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;

    private ValidatorInterface $validator;

    /**
     * DTOResolver constructor.
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator  = $validator;
    }

    /**
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        $argType = $argument->getType();

        return ($request->isMethod('POST') || $request->isMethod('PUT')) && $argType !== null &&
            0 === \strpos($argType, 'App\\DTO\\');
    }

    /**
     * Returns the possible value(s).
     *
     * @return iterable<mixed>
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $jsonData = $request->getContent();
        $argType  = $argument->getType();
        if ($argType === null) {
            throw new BadMethodCallException('Invalid Type');
        }

        $object     = $this->serializer->deserialize($jsonData, $argType, 'json');
        $violations = $this->validator->validate($object);

        if (0 < \count($violations)) {
            throw new ViolationException($violations, 'Invalid fields');
        }
        yield $object;
    }
}
