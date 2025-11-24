<?php
declare(strict_types=1);

namespace App\Table;

use App\Database\InMemoryDatabase;

/**
 * ProductTable - Table Layer
 * Handles direct database operations for products
 */
class ProductTable
{
    public int $id = 0;
    public string $name = '';
    public string $description = '';
    public float $price = 0.0;
    public string $category = '';
    public int $stock = 0;
    public string $created_at = '';
    
    protected InMemoryDatabase $db;
    protected ?string $error = null;
    
    public function __construct()
    {
        $this->db = InMemoryDatabase::getInstance();
    }
    
    /**
     * Get table name
     */
    public function getTable(): string
    {
        return 'products';
    }
    
    /**
     * Insert single record
     */
    public function insertSingleQuery(): ?static
    {
        try {
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'category' => $this->category,
                'stock' => $this->stock,
            ];
            
            $id = $this->db->insert($this->getTable(), $data);
            
            // Reload from database
            $result = $this->db->findById($this->getTable(), $id);
            if ($result) {
                $this->id = $result['id'];
                $this->created_at = $result['created_at'];
            }
            
            return $this;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return null;
        }
    }
    
    /**
     * Update single record
     */
    public function updateSingleQuery(): ?static
    {
        try {
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'category' => $this->category,
                'stock' => $this->stock,
            ];
            
            $success = $this->db->update($this->getTable(), $this->id, $data);
            
            if (!$success) {
                $this->error = "Product not found";
                return null;
            }
            
            return $this;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return null;
        }
    }
    
    /**
     * Delete by ID
     */
    public function deleteByIdQuery(int $id): ?int
    {
        try {
            $success = $this->db->delete($this->getTable(), $id);
            
            if (!$success) {
                $this->error = "Product not found";
                return null;
            }
            
            return $id;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return null;
        }
    }
    
    /**
     * Select by ID
     */
    public function selectById(int $id): ?static
    {
        $result = $this->db->findById($this->getTable(), $id);
        
        if ($result === null) {
            return null;
        }
        
        return $this->hydrate($result);
    }
    
    /**
     * Select all with optional filters
     */
    public function selectAll(?array $filters = null, ?string $sortBy = null): array
    {
        $results = $this->db->findAll($this->getTable(), $filters, $sortBy);
        
        $objects = [];
        foreach ($results as $result) {
            $objects[] = $this->hydrate($result);
        }
        
        return $objects;
    }
    
    /**
     * Hydrate object from array
     */
    protected function hydrate(array $data): static
    {
        $object = new static();
        $object->id = $data['id'] ?? 0;
        $object->name = $data['name'] ?? '';
        $object->description = $data['description'] ?? '';
        $object->price = $data['price'] ?? 0.0;
        $object->category = $data['category'] ?? '';
        $object->stock = $data['stock'] ?? 0;
        $object->created_at = $data['created_at'] ?? '';
        
        return $object;
    }
    
    /**
     * Get error message
     */
    public function getError(): ?string
    {
        return $this->error;
    }
    
    /**
     * Convert to array (for JSON serialization)
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category,
            'stock' => $this->stock,
            'created_at' => $this->created_at,
        ];
    }
}
