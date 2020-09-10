<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ViolationException.
 */
class ViolationException extends HttpException
{
    /**
     * @phpstan-ignore-next-line
     */
    private ConstraintViolationListInterface $violationList;

    /**
     * ViolationException constructor.
     *
     * @phpstan-ignore-next-line
     *
     * @param string $message
     */
    public function __construct(ConstraintViolationListInterface $violationList, $message = '')
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, $message);
        $this->violationList = $violationList;
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function getViolationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }
}
