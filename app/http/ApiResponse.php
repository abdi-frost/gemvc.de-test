<?php
declare(strict_types=1);

namespace App\Http;

/**
 * Simple response wrapper for the demo
 */
class ApiResponse
{
    private int $statusCode;
    private array $data;
    
    public function __construct(
        int $responseCode = 200,
        string $message = 'OK',
        int $count = 0,
        string $serviceMessage = '',
        mixed $data = null
    ) {
        $this->statusCode = $responseCode;
        $this->data = [
            'response_code' => $responseCode,
            'message' => $message,
            'count' => $count,
            'service_message' => $serviceMessage,
            'data' => $data,
        ];
    }
    
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    public function getData(): array
    {
        return $this->data;
    }
}
