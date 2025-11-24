<?php
declare(strict_types=1);

namespace App\Database;

/**
 * Simple in-memory database for mock API demo
 * This is a mock implementation - in production, use GEMVC's Table class with PDO
 */
class InMemoryDatabase
{
    private static ?self $instance = null;
    private array $data = [];
    private array $autoIncrements = [];
    
    private function __construct()
    {
        // Initialize with some sample data
        $this->data['products'] = [
            1 => [
                'id' => 1,
                'name' => 'Laptop',
                'description' => 'High-performance laptop for developers',
                'price' => 999.99,
                'category' => 'Electronics',
                'stock' => 50,
                'created_at' => '2024-11-24T07:00:00Z'
            ],
            2 => [
                'id' => 2,
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse',
                'price' => 29.99,
                'category' => 'Electronics',
                'stock' => 100,
                'created_at' => '2024-11-24T07:00:00Z'
            ],
            3 => [
                'id' => 3,
                'name' => 'USB-C Cable',
                'description' => 'High-speed USB-C cable',
                'price' => 15.99,
                'category' => 'Accessories',
                'stock' => 200,
                'created_at' => '2024-11-24T07:00:00Z'
            ]
        ];
        
        $this->autoIncrements['products'] = 4;
    }
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function insert(string $table, array $data): int
    {
        if (!isset($this->data[$table])) {
            $this->data[$table] = [];
            $this->autoIncrements[$table] = 1;
        }
        
        $id = $this->autoIncrements[$table]++;
        $data['id'] = $id;
        $data['created_at'] = date('c');
        
        $this->data[$table][$id] = $data;
        
        return $id;
    }
    
    public function update(string $table, int $id, array $data): bool
    {
        if (!isset($this->data[$table][$id])) {
            return false;
        }
        
        // Preserve id and created_at
        $data['id'] = $id;
        $data['created_at'] = $this->data[$table][$id]['created_at'] ?? date('c');
        
        $this->data[$table][$id] = array_merge($this->data[$table][$id], $data);
        
        return true;
    }
    
    public function delete(string $table, int $id): bool
    {
        if (!isset($this->data[$table][$id])) {
            return false;
        }
        
        unset($this->data[$table][$id]);
        
        return true;
    }
    
    public function findById(string $table, int $id): ?array
    {
        return $this->data[$table][$id] ?? null;
    }
    
    public function findAll(string $table, ?array $filters = null, ?string $sortBy = null): array
    {
        if (!isset($this->data[$table])) {
            return [];
        }
        
        $results = array_values($this->data[$table]);
        
        // Apply filters
        if ($filters !== null) {
            $results = array_filter($results, function($item) use ($filters) {
                foreach ($filters as $key => $value) {
                    if (isset($item[$key])) {
                        // Check for LIKE search
                        if (is_string($value) && str_contains($value, '%')) {
                            $pattern = '/' . str_replace('%', '.*', preg_quote($value, '/')) . '/i';
                            if (!preg_match($pattern, (string)$item[$key])) {
                                return false;
                            }
                        } else {
                            // Exact match
                            if ($item[$key] != $value) {
                                return false;
                            }
                        }
                    } else {
                        return false;
                    }
                }
                return true;
            });
        }
        
        // Apply sorting
        if ($sortBy !== null && count($results) > 0) {
            usort($results, function($a, $b) use ($sortBy) {
                if (!isset($a[$sortBy]) || !isset($b[$sortBy])) {
                    return 0;
                }
                return $a[$sortBy] <=> $b[$sortBy];
            });
        }
        
        return array_values($results);
    }
}
