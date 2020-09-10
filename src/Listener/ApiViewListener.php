<?php

declare(strict_types=1);

namespace App\Listener;

use App\Exception\ViolationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiViewListener.
 */
class ApiViewListener implements EventSubscriberInterface
{
    protected bool              $debug;
    private SerializerInterface $serializer;

    /**
     * ApiViewListener constructor.
     */
    public function __construct(bool $debug, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->debug      = $debug;
    }

    /**
     * serializes every api response to json.
     */
    public function onApiResponse(ViewEvent $e): void
    {
        $result     = $e->getControllerResult();

        $statusCode = 200;

        if ($result instanceof ApiResponse) {
            $statusCode = $result->getStatusCode();
            $result     = $result->getContent();
        }
        $json_data = $this->serializer->serialize($result, 'json', \array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ]));

        $response = new JsonResponse($json_data, $statusCode, ['content-type' => 'application/json', 'Access-Control-Allow-Credentials' => 'true'], true);

        $e->setResponse($response);
    }

    public function onViolationException(ExceptionEvent $e): void
    {
        $exception = $e->getThrowable();

        $content = $this->debug ? ['error' => $exception->getMessage()] : [];

        if ($exception instanceof ViolationException) {
            $content = $this->serializer->serialize($exception->getViolationList(), 'json');
            $e->setResponse(JsonResponse::fromJsonString($content, $exception->getStatusCode()));

            return;
        }

        if ($exception instanceof HttpException) {
            $e->setResponse(new JsonResponse($content, $exception->getStatusCode()));

            return;
        }

        $e->setResponse(new JsonResponse($content, 500));
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public static function getSubscribedEvents()
    {
        yield ViewEvent::class => 'onApiResponse';
        yield ExceptionEvent::class => [
            ['onViolationException', -8],
        ];
    }
}
