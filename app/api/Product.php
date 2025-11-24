<?php
declare(strict_types=1);

namespace App\Api;

use App\Controller\ProductController;
use App\Http\Request;
use App\Http\ApiResponse;

/**
 * Product - API Layer
 * Handles URL endpoints, request validation, and delegates to Controller layer
 * 
 * Endpoints:
 * - POST /api/Product/create
 * - GET /api/Product/read?id={id}
 * - POST /api/Product/update
 * - POST /api/Product/delete
 * - GET /api/Product/list
 */
class Product
{
    protected Request $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Create a new product
     * POST /api/Product/create
     * 
     * Required fields: name, price
     * Optional fields: description, category, stock
     */
    public function create(): ApiResponse
    {
        // Validate request schema
        if (!$this->request->definePostSchema([
            'name' => 'string',
            'price' => 'float',
            '?description' => 'string',
            '?category' => 'string',
            '?stock' => 'int',
        ])) {
            return $this->request->returnResponse();
        }
        
        // Delegate to controller
        return (new ProductController($this->request))->create();
    }
    
    /**
     * Read a product by ID
     * GET /api/Product/read?id={id}
     * 
     * Required parameters: id
     */
    public function read(): ApiResponse
    {
        // Validate GET parameters
        if (!$this->request->defineGetSchema([
            'id' => 'int',
        ])) {
            return $this->request->returnResponse();
        }
        
        $id = $this->request->intValueGet('id');
        if ($id === false) {
            return $this->request->returnResponse();
        }
        
        // Store in post for controller
        $this->request->post['id'] = $id;
        
        // Delegate to controller
        return (new ProductController($this->request))->read();
    }
    
    /**
     * Update a product
     * POST /api/Product/update
     * 
     * Required fields: id
     * Optional fields: name, description, price, category, stock
     */
    public function update(): ApiResponse
    {
        // Validate request schema
        if (!$this->request->definePostSchema([
            'id' => 'int',
            '?name' => 'string',
            '?description' => 'string',
            '?price' => 'float',
            '?category' => 'string',
            '?stock' => 'int',
        ])) {
            return $this->request->returnResponse();
        }
        
        // Delegate to controller
        return (new ProductController($this->request))->update();
    }
    
    /**
     * Delete a product
     * POST /api/Product/delete
     * 
     * Required fields: id
     */
    public function delete(): ApiResponse
    {
        // Validate request schema
        if (!$this->request->definePostSchema([
            'id' => 'int',
        ])) {
            return $this->request->returnResponse();
        }
        
        // Delegate to controller
        return (new ProductController($this->request))->delete();
    }
    
    /**
     * List all products
     * GET /api/Product/list
     * 
     * Optional parameters:
     * - sort_by: Field to sort by (name, price, category)
     * - find_like: Search filter (e.g., name=Laptop)
     * - category: Filter by category
     */
    public function list(): ApiResponse
    {
        // No validation needed - all parameters are optional
        
        // Delegate to controller
        return (new ProductController($this->request))->list();
    }
}
