# GEMVC Mock API Demo

Mock API endpoints with simple CRUD operations using the GEMVC framework architecture.

## Features

- ✅ **GEMVC 4-Layer Architecture**: API → Controller → Model → Table
- ✅ **RESTful CRUD Operations**: Create, Read, Update, Delete, List
- ✅ **Request Validation**: Schema-based validation for all endpoints
- ✅ **OpenAPI/Swagger Documentation**: Interactive API documentation
- ✅ **In-Memory Database**: Simple demo database with sample data
- ✅ **Filtering & Sorting**: Query parameters for list operations

## Architecture

This project follows the GEMVC framework's 4-layer architecture:

```
API Layer (app/api/)          → URL endpoints, schema validation
    ↓
Controller Layer (app/controller/) → Business logic orchestration
    ↓
Model Layer (app/model/)      → Data logic, validations
    ↓
Table Layer (app/table/)      → Database operations
```

## Project Structure

```
.
├── app/
│   ├── api/           # API Layer - Endpoint definitions
│   │   └── Product.php
│   ├── controller/    # Controller Layer - Business logic
│   │   └── ProductController.php
│   ├── model/         # Model Layer - Data validations
│   │   └── ProductModel.php
│   ├── table/         # Table Layer - Database operations
│   │   └── ProductTable.php
│   ├── database/      # In-memory database implementation
│   │   └── InMemoryDatabase.php
│   └── http/          # HTTP utilities
│       ├── Request.php
│       └── ApiResponse.php
├── public/
│   ├── index.php      # Bootstrap file
│   ├── docs.php       # Swagger UI page
│   └── openapi.json   # OpenAPI specification
├── gemvc/             # GEMVC Framework
└── composer.json
```

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd gemvc.de-test
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Start the development server**
   ```bash
   cd public
   php -S localhost:8000
   ```

4. **Visit the API**
   - API Root: http://localhost:8000/api
   - Documentation: http://localhost:8000/api/docs

## API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Endpoints

#### 1. List Products
```http
GET /api/Product/list
```

**Query Parameters:**
- `category` (optional): Filter by category
- `sort_by` (optional): Sort by field (name, price, category)
- `find_like` (optional): Search filter (e.g., name=Laptop)

**Example:**
```bash
curl http://localhost:8000/api/Product/list
curl "http://localhost:8000/api/Product/list?category=Electronics"
curl "http://localhost:8000/api/Product/list?sort_by=price"
```

**Response:**
```json
{
    "response_code": 200,
    "message": "OK",
    "count": 3,
    "service_message": "Products retrieved successfully",
    "data": [...]
}
```

#### 2. Read Product
```http
GET /api/Product/read?id={id}
```

**Example:**
```bash
curl http://localhost:8000/api/Product/read?id=1
```

**Response:**
```json
{
    "response_code": 200,
    "message": "OK",
    "count": 1,
    "service_message": "Product retrieved successfully",
    "data": {
        "id": 1,
        "name": "Laptop",
        "description": "High-performance laptop for developers",
        "price": 999.99,
        "category": "Electronics",
        "stock": 50,
        "created_at": "2024-11-24T07:00:00Z"
    }
}
```

#### 3. Create Product
```http
POST /api/Product/create
Content-Type: application/json
```

**Required Fields:**
- `name`: string
- `price`: number

**Optional Fields:**
- `description`: string
- `category`: string
- `stock`: integer

**Example:**
```bash
curl -X POST http://localhost:8000/api/Product/create \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Keyboard",
    "description": "Mechanical keyboard",
    "price": 149.99,
    "category": "Electronics",
    "stock": 30
  }'
```

**Response:**
```json
{
    "response_code": 201,
    "message": "created",
    "count": 1,
    "service_message": "Product created successfully",
    "data": {
        "id": 4,
        "name": "Keyboard",
        ...
    }
}
```

#### 4. Update Product
```http
POST /api/Product/update
Content-Type: application/json
```

**Required Fields:**
- `id`: integer

**Optional Fields (only update what you provide):**
- `name`: string
- `description`: string
- `price`: number
- `category`: string
- `stock`: integer

**Example:**
```bash
curl -X POST http://localhost:8000/api/Product/update \
  -H "Content-Type: application/json" \
  -d '{
    "id": 1,
    "price": 899.99,
    "stock": 45
  }'
```

**Response:**
```json
{
    "response_code": 209,
    "message": "updated",
    "count": 1,
    "service_message": "Product updated successfully",
    "data": {...}
}
```

#### 5. Delete Product
```http
POST /api/Product/delete
Content-Type: application/json
```

**Required Fields:**
- `id`: integer

**Example:**
```bash
curl -X POST http://localhost:8000/api/Product/delete \
  -H "Content-Type: application/json" \
  -d '{"id": 3}'
```

**Response:**
```json
{
    "response_code": 210,
    "message": "deleted",
    "count": 1,
    "service_message": "Product deleted successfully",
    "data": {
        "deleted_id": 3
    }
}
```

## Response Format

All responses follow the GEMVC standard format:

```json
{
    "response_code": 200,
    "message": "OK",
    "count": 1,
    "service_message": "Operation message",
    "data": {}
}
```

### HTTP Status Codes

- `200` - OK (Success)
- `201` - Created
- `209` - Updated (GEMVC custom)
- `210` - Deleted (GEMVC custom)
- `400` - Bad Request (Validation error)
- `404` - Not Found
- `422` - Unprocessable Entity (Business logic error)
- `500` - Internal Server Error

## Swagger/OpenAPI Documentation

Interactive API documentation is available at:
```
http://localhost:8000/api/docs
```

This provides:
- Complete API endpoint documentation
- Request/response examples
- Try-it-out functionality
- Schema definitions

## Sample Data

The in-memory database includes these sample products:

1. **Laptop** - Electronics, $999.99
2. **Wireless Mouse** - Electronics, $29.99
3. **USB-C Cable** - Accessories, $15.99

**Note:** The database is in-memory and resets on each request. This is intentional for the demo to always have consistent sample data.

## GEMVC Framework

This project demonstrates the GEMVC framework architecture:

- **API Layer**: Handles HTTP requests, validates input schemas
- **Controller Layer**: Orchestrates business logic
- **Model Layer**: Contains business validations and data transformations
- **Table Layer**: Performs database operations

Learn more about GEMVC: https://github.com/gemvc/gemvc

## Development

### Running Tests
```bash
# Start server
cd public && php -S localhost:8000

# In another terminal, test endpoints
curl http://localhost:8000/api/Product/list
```

### Extending the API

To add new endpoints, follow the 4-layer pattern:

1. Create Table class in `app/table/`
2. Create Model class extending Table in `app/model/`
3. Create Controller class in `app/controller/`
4. Create API class in `app/api/`
5. Update OpenAPI specification

## License

MIT