<?php

declare(strict_types=1);

namespace App\Listener;

class ApiResponse
{
    /**
     * @var array<mixed>
     */
    private array $content;
    private int $statusCode;

    /**
     * ApiResponse constructor.
     *
     * @param array<mixed> $content
     */
    public function __construct(array $content, int $statusCode)
    {
        $this->content    = $content;
        $this->statusCode = $statusCode;
    }

    /**
     * @return array<mixed>
     */
    public function getContent(): array
    {
        return $this->content;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
