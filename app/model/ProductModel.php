<?php
declare(strict_types=1);

namespace App\Model;

use App\Table\ProductTable;
use App\Http\ApiResponse;

/**
 * ProductModel - Model Layer
 * Contains business logic, validations, and data transformations
 */
class ProductModel extends ProductTable
{
    /**
     * Create a new product
     */
    public function createModel(): ApiResponse
    {
        // Business validation: Check if name is empty
        if (empty($this->name)) {
            return new ApiResponse(
                400,
                'Bad Request',
                0,
                'Product name is required',
                null
            );
        }
        
        // Business validation: Check price
        if ($this->price <= 0) {
            return new ApiResponse(
                422,
                'Unprocessable Entity',
                0,
                'Product price must be greater than 0',
                null
            );
        }
        
        // Business validation: Check stock
        if ($this->stock < 0) {
            return new ApiResponse(
                422,
                'Unprocessable Entity',
                0,
                'Product stock cannot be negative',
                null
            );
        }
        
        // Perform database operation
        $result = $this->insertSingleQuery();
        if ($this->getError()) {
            return new ApiResponse(
                500,
                'Internal Server Error',
                0,
                $this->getError(),
                null
            );
        }
        
        return new ApiResponse(
            201,
            'created',
            1,
            'Product created successfully',
            $result ? $result->toArray() : null
        );
    }
    
    /**
     * Read a product
     */
    public function readModel(): ApiResponse
    {
        if ($this->id <= 0) {
            return new ApiResponse(
                400,
                'Bad Request',
                0,
                'Invalid product ID',
                null
            );
        }
        
        $product = $this->selectById($this->id);
        
        if ($product === null) {
            return new ApiResponse(
                404,
                'Not Found',
                0,
                'Product not found',
                null
            );
        }
        
        return new ApiResponse(
            200,
            'OK',
            1,
            'Product retrieved successfully',
            $product->toArray()
        );
    }
    
    /**
     * Update a product
     */
    public function updateModel(): ApiResponse
    {
        // Validate ID
        if ($this->id <= 0) {
            return new ApiResponse(
                400,
                'Bad Request',
                0,
                'Invalid product ID',
                null
            );
        }
        
        // Check if product exists
        $existing = $this->selectById($this->id);
        if ($existing === null) {
            return new ApiResponse(
                404,
                'Not Found',
                0,
                'Product not found',
                null
            );
        }
        
        // Business validation: Check price if provided
        if ($this->price <= 0) {
            return new ApiResponse(
                422,
                'Unprocessable Entity',
                0,
                'Product price must be greater than 0',
                null
            );
        }
        
        // Business validation: Check stock if provided
        if ($this->stock < 0) {
            return new ApiResponse(
                422,
                'Unprocessable Entity',
                0,
                'Product stock cannot be negative',
                null
            );
        }
        
        // Perform database operation
        $result = $this->updateSingleQuery();
        if ($this->getError()) {
            return new ApiResponse(
                500,
                'Internal Server Error',
                0,
                $this->getError(),
                null
            );
        }
        
        return new ApiResponse(
            209,
            'updated',
            1,
            'Product updated successfully',
            $result ? $result->toArray() : null
        );
    }
    
    /**
     * Delete a product
     */
    public function deleteModel(): ApiResponse
    {
        if ($this->id <= 0) {
            return new ApiResponse(
                400,
                'Bad Request',
                0,
                'Invalid product ID',
                null
            );
        }
        
        // Check if product exists
        $existing = $this->selectById($this->id);
        if ($existing === null) {
            return new ApiResponse(
                404,
                'Not Found',
                0,
                'Product not found',
                null
            );
        }
        
        // Perform database operation
        $deletedId = $this->deleteByIdQuery($this->id);
        
        if ($this->getError()) {
            return new ApiResponse(
                500,
                'Internal Server Error',
                0,
                $this->getError(),
                null
            );
        }
        
        return new ApiResponse(
            210,
            'deleted',
            1,
            'Product deleted successfully',
            ['deleted_id' => $deletedId]
        );
    }
    
    /**
     * List all products
     */
    public function listModel(?array $filters = null, ?string $sortBy = null): ApiResponse
    {
        $products = $this->selectAll($filters, $sortBy);
        
        $data = array_map(fn($p) => $p->toArray(), $products);
        
        return new ApiResponse(
            200,
            'OK',
            count($data),
            'Products retrieved successfully',
            $data
        );
    }
}
