# Quick Start Guide

## Starting the Server

```bash
# Navigate to public directory
cd public

# Start PHP built-in server
php -S localhost:8000
```

## API Root

Visit http://localhost:8000/api to see available endpoints.

## Documentation

Visit http://localhost:8000/api/docs for full interactive documentation.

## Quick Test Commands

### List all products
```bash
curl http://localhost:8000/api/Product/list
```

### Get a specific product
```bash
curl http://localhost:8000/api/Product/read?id=1
```

### Create a new product
```bash
curl -X POST http://localhost:8000/api/Product/create \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Mechanical Keyboard",
    "description": "RGB mechanical gaming keyboard",
    "price": 149.99,
    "category": "Electronics",
    "stock": 25
  }'
```

### Update a product
```bash
curl -X POST http://localhost:8000/api/Product/update \
  -H "Content-Type: application/json" \
  -d '{
    "id": 1,
    "price": 899.99,
    "stock": 45
  }'
```

### Delete a product
```bash
curl -X POST http://localhost:8000/api/Product/delete \
  -H "Content-Type: application/json" \
  -d '{"id": 3}'
```

### Filter by category
```bash
curl "http://localhost:8000/api/Product/list?category=Electronics"
```

### Sort by price
```bash
curl "http://localhost:8000/api/Product/list?sort_by=price"
```

### Search by name
```bash
curl "http://localhost:8000/api/Product/list?find_like=name=Laptop"
```

## Response Format

All responses follow this format:
```json
{
  "response_code": 200,
  "message": "OK",
  "count": 1,
  "service_message": "Operation message",
  "data": {}
}
```

## HTTP Status Codes

- `200` - OK (Success)
- `201` - Created
- `209` - Updated (GEMVC custom)
- `210` - Deleted (GEMVC custom)
- `400` - Bad Request (Validation error)
- `404` - Not Found
- `422` - Unprocessable Entity (Business logic error)
- `500` - Internal Server Error

## Sample Products

The API comes with 3 sample products:
1. Laptop - Electronics, $999.99
2. Wireless Mouse - Electronics, $29.99
3. USB-C Cable - Accessories, $15.99

**Note:** The database is in-memory and resets with each request to provide consistent demo data.
