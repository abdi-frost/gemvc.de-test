<?php
declare(strict_types=1);

namespace App\Http;

/**
 * Simple Request handler for demo purposes
 */
class Request
{
    public array $post = [];
    public array $get = [];
    private array $errors = [];
    
    public function __construct()
    {
        // Parse JSON body for POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            $this->post = is_array($data) ? $data : [];
        }
        
        $this->get = $_GET;
    }
    
    /**
     * Define POST schema for validation
     */
    public function definePostSchema(array $schema): bool
    {
        foreach ($schema as $field => $type) {
            $isOptional = str_starts_with($field, '?');
            $fieldName = $isOptional ? substr($field, 1) : $field;
            
            if (!$isOptional && !isset($this->post[$fieldName])) {
                $this->errors[] = "Field '{$fieldName}' is required";
                return false;
            }
            
            if (isset($this->post[$fieldName])) {
                if (!$this->validateType($this->post[$fieldName], $type)) {
                    $this->errors[] = "Field '{$fieldName}' must be of type '{$type}'";
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Define GET schema for validation
     */
    public function defineGetSchema(array $schema): bool
    {
        foreach ($schema as $field => $type) {
            $isOptional = str_starts_with($field, '?');
            $fieldName = $isOptional ? substr($field, 1) : $field;
            
            if (!$isOptional && !isset($this->get[$fieldName])) {
                $this->errors[] = "Parameter '{$fieldName}' is required";
                return false;
            }
            
            if (isset($this->get[$fieldName])) {
                if (!$this->validateType($this->get[$fieldName], $type)) {
                    $this->errors[] = "Parameter '{$fieldName}' must be of type '{$type}'";
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Validate field type
     */
    private function validateType(mixed $value, string $type): bool
    {
        return match($type) {
            'string' => is_string($value),
            'int' => is_numeric($value),
            'float' => is_numeric($value),
            'bool' => is_bool($value),
            'email' => is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false,
            default => true
        };
    }
    
    /**
     * Get integer value from GET parameters
     */
    public function intValueGet(string $key): int|false
    {
        if (!isset($this->get[$key])) {
            return false;
        }
        
        $value = $this->get[$key];
        if (!is_numeric($value)) {
            $this->errors[] = "Parameter '{$key}' must be an integer";
            return false;
        }
        
        return (int)$value;
    }
    
    /**
     * Get integer value from POST data
     */
    public function intValuePost(string $key): int|false
    {
        if (!isset($this->post[$key])) {
            return false;
        }
        
        $value = $this->post[$key];
        if (!is_numeric($value)) {
            $this->errors[] = "Field '{$key}' must be an integer";
            return false;
        }
        
        return (int)$value;
    }
    
    /**
     * Get string value from GET parameters
     */
    public function stringValueGet(string $key): ?string
    {
        return isset($this->get[$key]) ? (string)$this->get[$key] : null;
    }
    
    /**
     * Map POST data to object
     */
    public function mapPostToObject(object $object, ?array $mapping = null): object|false
    {
        if ($mapping === null) {
            // Auto-map all POST fields
            foreach ($this->post as $key => $value) {
                if (property_exists($object, $key)) {
                    $object->$key = $value;
                }
            }
        } else {
            // Map according to provided mapping
            foreach ($mapping as $postKey => $property) {
                if (!isset($this->post[$postKey])) {
                    continue;
                }
                
                // Check if it's a method call
                if (str_ends_with($property, '()')) {
                    $method = substr($property, 0, -2);
                    if (method_exists($object, $method)) {
                        $object->$method($this->post[$postKey]);
                    }
                } else {
                    if (property_exists($object, $property)) {
                        $object->$property = $this->post[$postKey];
                    }
                }
            }
        }
        
        return $object;
    }
    
    /**
     * Return JSON response with errors
     */
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
    
    /**
     * Get errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
