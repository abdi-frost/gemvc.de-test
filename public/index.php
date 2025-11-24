<?php
declare(strict_types=1);

/**
 * Bootstrap file for GEMVC Mock API
 * This file initializes the application and handles incoming requests
 */

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables (simple way)
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// Set error reporting based on environment
if (($_ENV['APP_ENV'] ?? 'development') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Simple request router
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Parse the URL
$path = parse_url($requestUri, PHP_URL_PATH);
$pathParts = array_filter(explode('/', $path));
$pathParts = array_values($pathParts);

// Handle API documentation
if ($path === '/api' || $path === '/api/') {
    header('Content-Type: application/json');
    echo json_encode([
        'message' => 'GEMVC Mock API is running',
        'version' => '1.0.0',
        'endpoints' => [
            'POST /api/Product/create' => 'Create a new product',
            'GET /api/Product/read?id={id}' => 'Read a product by ID',
            'POST /api/Product/update' => 'Update a product',
            'POST /api/Product/delete' => 'Delete a product',
            'GET /api/Product/list' => 'List all products',
        ],
        'documentation' => '/api/docs'
    ], JSON_PRETTY_PRINT);
    exit;
}

// Handle Swagger/OpenAPI documentation
if ($path === '/api/docs' || $path === '/api/docs/') {
    require_once __DIR__ . '/docs.php';
    exit;
}

// Route API requests
if (count($pathParts) >= 3 && $pathParts[0] === 'api') {
    $serviceName = $pathParts[1] ?? '';
    $methodName = $pathParts[2] ?? '';
    
    if (empty($serviceName) || empty($methodName)) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'response_code' => 404,
            'message' => 'Not Found',
            'service_message' => 'Invalid API endpoint'
        ]);
        exit;
    }
    
    // Load the API service
    $serviceClass = "App\\Api\\{$serviceName}";
    
    if (!class_exists($serviceClass)) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'response_code' => 404,
            'message' => 'Not Found',
            'service_message' => "Service '{$serviceName}' not found"
        ]);
        exit;
    }
    
    try {
        $request = new \App\Http\Request();
        $service = new $serviceClass($request);
        
        if (!method_exists($service, $methodName)) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode([
                'response_code' => 404,
                'message' => 'Not Found',
                'service_message' => "Method '{$methodName}' not found in service '{$serviceName}'"
            ]);
            exit;
        }
        
        $response = $service->$methodName();
        
        // Send response
        http_response_code($response->getStatusCode());
        header('Content-Type: application/json');
        echo json_encode($response->getData(), JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'response_code' => 500,
            'message' => 'Internal Server Error',
            'service_message' => $_ENV['APP_DEBUG'] === 'true' ? $e->getMessage() : 'An error occurred'
        ]);
    }
    exit;
}

// 404 for other requests
http_response_code(404);
header('Content-Type: application/json');
echo json_encode([
    'response_code' => 404,
    'message' => 'Not Found',
    'service_message' => 'Endpoint not found'
]);
