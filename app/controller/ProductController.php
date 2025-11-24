<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\ProductModel;
use App\Http\Request;
use App\Http\ApiResponse;

/**
 * ProductController - Controller Layer
 * Orchestrates business logic and handles request/response flow
 */
class ProductController
{
    protected Request $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Create a new product
     */
    public function create(): ApiResponse
    {
        // Map POST data to Model
        $model = $this->request->mapPostToObject(
            new ProductModel(),
            [
                'name' => 'name',
                'description' => 'description',
                'price' => 'price',
                'category' => 'category',
                'stock' => 'stock',
            ]
        );
        
        if (!$model instanceof ProductModel) {
            return $this->request->returnResponse();
        }
        
        return $model->createModel();
    }
    
    /**
     * Read a product by ID
     */
    public function read(): ApiResponse
    {
        $id = $this->request->post['id'] ?? 0;
        
        $model = new ProductModel();
        $model->id = $id;
        
        return $model->readModel();
    }
    
    /**
     * Update a product
     */
    public function update(): ApiResponse
    {
        $id = $this->request->post['id'] ?? 0;
        
        // First, fetch existing product
        $model = new ProductModel();
        $existing = $model->selectById($id);
        
        if ($existing === null) {
            return new ApiResponse(
                404,
                'Not Found',
                0,
                'Product not found',
                null
            );
        }
        
        // Update only provided fields
        if (isset($this->request->post['name'])) {
            $existing->name = $this->request->post['name'];
        }
        if (isset($this->request->post['description'])) {
            $existing->description = $this->request->post['description'];
        }
        if (isset($this->request->post['price'])) {
            $existing->price = (float)$this->request->post['price'];
        }
        if (isset($this->request->post['category'])) {
            $existing->category = $this->request->post['category'];
        }
        if (isset($this->request->post['stock'])) {
            $existing->stock = (int)$this->request->post['stock'];
        }
        
        return $existing->updateModel();
    }
    
    /**
     * Delete a product
     */
    public function delete(): ApiResponse
    {
        $id = $this->request->post['id'] ?? 0;
        
        $model = new ProductModel();
        $model->id = $id;
        
        return $model->deleteModel();
    }
    
    /**
     * List all products with optional filtering and sorting
     */
    public function list(): ApiResponse
    {
        $filters = [];
        $sortBy = null;
        
        // Check for category filter
        if (isset($this->request->get['category'])) {
            $filters['category'] = $this->request->get['category'];
        }
        
        // Check for find_like parameter (e.g., name=Laptop)
        if (isset($this->request->get['find_like'])) {
            $parts = explode('=', $this->request->get['find_like']);
            if (count($parts) === 2) {
                $filters[$parts[0]] = '%' . $parts[1] . '%';
            }
        }
        
        // Check for sort_by parameter
        if (isset($this->request->get['sort_by'])) {
            $sortBy = $this->request->get['sort_by'];
        }
        
        $model = new ProductModel();
        return $model->listModel(
            !empty($filters) ? $filters : null,
            $sortBy
        );
    }
}
