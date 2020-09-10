<?php

declare(strict_types=1);

namespace spec\App\Matcher;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\BasicMatcher;
use Ramsey\Uuid\Uuid;

final class RandomUuidMatcher extends BasicMatcher
{
    public function supports(string $name, $subject, array $arguments): bool
    {
        return \in_array($name, ['beRandomUuid'], true)
            && \gettype($subject) === 'object'
            && $subject instanceof Uuid;
    }

    protected function matches($subject, array $arguments): bool
    {
        return \strlen($subject->toString()) > 5
            && $subject !== null
            && !$subject->equals(Uuid::uuid4())
            && $subject->getInteger() !== 0
            && $subject->getInteger() !== 1
            && $subject->toString() !== '';
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        return new FailureException(\sprintf(
            'Expected to be a random uuid'
        ));
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        return new FailureException(\sprintf(
            'Expected not to be a random uuid'
        ));
    }
}
