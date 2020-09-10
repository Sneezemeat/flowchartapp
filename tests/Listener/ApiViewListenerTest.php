<?php

declare(strict_types=1);

namespace App\Tests\Listener;

use App\Exception\ViolationException;
use App\Listener\ApiViewListener;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\BadMethodCallException;

/**
 * @internal
 * @coversNothing
 */
final class ApiViewListenerTest extends KernelTestCase
{
    /** @var ApiViewListener */
    private $apiViewListener;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        //        $serializer = $this->getMockBuilder(Serializer::class)
        //            ->setMethods(['serialize'])
        //            ->getMock();
        $normalizers = [new ObjectNormalizer()];
        $encoders    = [new JsonEncoder()];
        $serializer  = new Serializer($normalizers, $encoders);

        $request       = Request::createFromGlobals();
        $violationList = new ConstraintViolationList();

        $this->apiViewListener = new ApiViewListener(true, $serializer);
    }

    public function testOnViolationException(): void
    {
        $kernel = self::bootKernel();

        $request            = Request::createFromGlobals();
        $violationList      = new ConstraintViolationList();
        $violationException = new ViolationException($violationList);
        $exceptionEvent     = new ExceptionEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $violationException);

        $this->apiViewListener->onViolationException($exceptionEvent);

        static::assertSame('{}', $exceptionEvent->getResponse()->getContent());
        static::assertSame(400, $exceptionEvent->getResponse()->getStatusCode());
    }

    public function testOnViolationExceptionWithHttpException(): void
    {
        $kernel = self::bootKernel();

        $request        = Request::createFromGlobals();
        $httpException  = new HttpException(400, 'message');
        $exceptionEvent = new ExceptionEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $httpException);

        $this->apiViewListener->onViolationException($exceptionEvent);

        static::assertSame('{"error":"message"}', $exceptionEvent->getResponse()->getContent());
        static::assertSame(400, $exceptionEvent->getResponse()->getStatusCode());
    }

    public function testOnViolationExceptionWithWrongException(): void
    {
        $kernel = self::bootKernel();

        $request        = Request::createFromGlobals();
        $exception      = new BadMethodCallException('message', 400);
        $exceptionEvent = new ExceptionEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $exception);

        $this->apiViewListener->onViolationException($exceptionEvent);

        static::assertSame('{"error":"message"}', $exceptionEvent->getResponse()->getContent());
        static::assertSame(500, $exceptionEvent->getResponse()->getStatusCode());
    }

    public function testGetSubscribedEvents(): void
    {
        $result = ApiViewListener::getSubscribedEvents();
        static::assertSame('Generator', \get_class($result));
        static::assertStringContainsString('ViewEvent', $result->key());
        static::assertSame('onApiResponse', $result->current());
        $result->next();
        static::assertStringContainsString('ExceptionEvent', $result->key());
        static::assertSame([['onViolationException', -8]], $result->current());
        $result->next();
        static::assertNull($result->key());
    }
}
