<?php
declare(strict_types=1);

namespace App\Http;

use Gemvc\Http\Request as BaseRequest;

/**
 * Request wrapper for simpler response handling
 */
class RequestWrapper extends BaseRequest
{
    private array $errors = [];
    
    public function returnResponse(): ApiResponse
    {
        return new ApiResponse(
            400,
            'Bad Request',
            0,
            implode(', ', $this->errors),
            null
        );
    }
    
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }
    
    public function getErrors(): array
    {
        return $this->errors;
    }
}
