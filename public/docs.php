<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEMVC Mock API Documentation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        .section {
            background: white;
            margin: 20px 0;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        h2 {
            color: #667eea;
            margin-bottom: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .endpoint {
            margin: 20px 0;
            border-left: 4px solid #667eea;
            padding-left: 20px;
        }
        .method {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 4px;
            font-weight: bold;
            margin-right: 10px;
            font-size: 0.9rem;
        }
        .method.get {
            background: #61affe;
            color: white;
        }
        .method.post {
            background: #49cc90;
            color: white;
        }
        .url {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
        }
        .code-block {
            background: #282c34;
            color: #abb2bf;
            padding: 20px;
            border-radius: 6px;
            overflow-x: auto;
            margin: 10px 0;
        }
        .code-block pre {
            margin: 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }
        .param {
            background: #f8f9fa;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border-left: 3px solid #667eea;
        }
        .param strong {
            color: #667eea;
        }
        .note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        footer {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            background: #667eea;
            color: white;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <header>
        <h1>🚀 GEMVC Mock API</h1>
        <p>RESTful API with CRUD Operations</p>
        <p style="font-size: 1rem; margin-top: 10px;">Base URL: <code style="background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 4px;">http://localhost:8000/api</code></p>
    </header>

    <div class="container">
        <div class="section">
            <h2>📋 Overview</h2>
            <p>This API provides CRUD operations for managing products. It follows the GEMVC framework's 4-layer architecture pattern.</p>
            <p style="margin-top: 10px;"><strong>Features:</strong></p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li>✅ Schema-based request validation</li>
                <li>✅ Filtering and sorting support</li>
                <li>✅ Consistent JSON response format</li>
                <li>✅ In-memory demo database with sample data</li>
            </ul>
        </div>

        <div class="section">
            <h2>🔗 Endpoints</h2>

            <div class="endpoint">
                <h3><span class="method get">GET</span> <span class="url">/Product/list</span></h3>
                <p><strong>Description:</strong> Retrieve a list of all products</p>
                
                <p style="margin-top: 15px;"><strong>Query Parameters:</strong></p>
                <div class="param">
                    <strong>category</strong> <span class="badge">optional</span><br>
                    Filter products by category (e.g., Electronics, Accessories)
                </div>
                <div class="param">
                    <strong>sort_by</strong> <span class="badge">optional</span><br>
                    Sort by field: name, price, category
                </div>
                <div class="param">
                    <strong>find_like</strong> <span class="badge">optional</span><br>
                    Search filter (e.g., name=Laptop)
                </div>

                <p style="margin-top: 15px;"><strong>Example Request:</strong></p>
                <div class="code-block">
                    <pre>curl "http://localhost:8000/api/Product/list"
curl "http://localhost:8000/api/Product/list?category=Electronics"
curl "http://localhost:8000/api/Product/list?sort_by=price"</pre>
                </div>

                <p style="margin-top: 15px;"><strong>Example Response:</strong></p>
                <div class="code-block">
                    <pre>{
  "response_code": 200,
  "message": "OK",
  "count": 3,
  "service_message": "Products retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Laptop",
      "description": "High-performance laptop for developers",
      "price": 999.99,
      "category": "Electronics",
      "stock": 50,
      "created_at": "2024-11-24T07:00:00Z"
    }
  ]
}</pre>
                </div>
            </div>

            <div class="endpoint">
                <h3><span class="method get">GET</span> <span class="url">/Product/read?id={id}</span></h3>
                <p><strong>Description:</strong> Retrieve a single product by ID</p>
                
                <p style="margin-top: 15px;"><strong>Query Parameters:</strong></p>
                <div class="param">
                    <strong>id</strong> <span class="badge">required</span><br>
                    Product ID (integer)
                </div>

                <p style="margin-top: 15px;"><strong>Example Request:</strong></p>
                <div class="code-block">
                    <pre>curl "http://localhost:8000/api/Product/read?id=1"</pre>
                </div>
            </div>

            <div class="endpoint">
                <h3><span class="method post">POST</span> <span class="url">/Product/create</span></h3>
                <p><strong>Description:</strong> Create a new product</p>
                
                <p style="margin-top: 15px;"><strong>Request Body:</strong></p>
                <div class="param">
                    <strong>name</strong> <span class="badge">required</span><br>
                    Product name (string)
                </div>
                <div class="param">
                    <strong>price</strong> <span class="badge">required</span><br>
                    Product price (number)
                </div>
                <div class="param">
                    <strong>description</strong> <span class="badge">optional</span><br>
                    Product description (string)
                </div>
                <div class="param">
                    <strong>category</strong> <span class="badge">optional</span><br>
                    Product category (string)
                </div>
                <div class="param">
                    <strong>stock</strong> <span class="badge">optional</span><br>
                    Stock quantity (integer)
                </div>

                <p style="margin-top: 15px;"><strong>Example Request:</strong></p>
                <div class="code-block">
                    <pre>curl -X POST http://localhost:8000/api/Product/create \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Keyboard",
    "description": "Mechanical keyboard",
    "price": 149.99,
    "category": "Electronics",
    "stock": 30
  }'</pre>
                </div>
            </div>

            <div class="endpoint">
                <h3><span class="method post">POST</span> <span class="url">/Product/update</span></h3>
                <p><strong>Description:</strong> Update an existing product</p>
                
                <p style="margin-top: 15px;"><strong>Request Body:</strong></p>
                <div class="param">
                    <strong>id</strong> <span class="badge">required</span><br>
                    Product ID (integer)
                </div>
                <div class="param">
                    <strong>Other fields</strong> <span class="badge">optional</span><br>
                    Only provided fields will be updated: name, description, price, category, stock
                </div>

                <p style="margin-top: 15px;"><strong>Example Request:</strong></p>
                <div class="code-block">
                    <pre>curl -X POST http://localhost:8000/api/Product/update \
  -H "Content-Type: application/json" \
  -d '{
    "id": 1,
    "price": 899.99,
    "stock": 45
  }'</pre>
                </div>
            </div>

            <div class="endpoint">
                <h3><span class="method post">POST</span> <span class="url">/Product/delete</span></h3>
                <p><strong>Description:</strong> Delete a product by ID</p>
                
                <p style="margin-top: 15px;"><strong>Request Body:</strong></p>
                <div class="param">
                    <strong>id</strong> <span class="badge">required</span><br>
                    Product ID (integer)
                </div>

                <p style="margin-top: 15px;"><strong>Example Request:</strong></p>
                <div class="code-block">
                    <pre>curl -X POST http://localhost:8000/api/Product/delete \
  -H "Content-Type: application/json" \
  -d '{"id": 3}'</pre>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>📊 Response Format</h2>
            <p>All endpoints return responses in this standard format:</p>
            <div class="code-block">
                <pre>{
  "response_code": 200,     // HTTP status code
  "message": "OK",          // HTTP status message
  "count": 1,               // Number of items returned
  "service_message": "...", // Descriptive message
  "data": {}                // Response data
}</pre>
            </div>

            <p style="margin-top: 20px;"><strong>HTTP Status Codes:</strong></p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li><strong>200</strong> - OK (Success)</li>
                <li><strong>201</strong> - Created</li>
                <li><strong>209</strong> - Updated (GEMVC custom)</li>
                <li><strong>210</strong> - Deleted (GEMVC custom)</li>
                <li><strong>400</strong> - Bad Request (Validation error)</li>
                <li><strong>404</strong> - Not Found</li>
                <li><strong>422</strong> - Unprocessable Entity (Business logic error)</li>
                <li><strong>500</strong> - Internal Server Error</li>
            </ul>
        </div>

        <div class="section">
            <h2>💾 Sample Data</h2>
            <p>The API includes these sample products:</p>
            <ol style="margin-left: 20px; margin-top: 10px;">
                <li><strong>Laptop</strong> - Electronics, $999.99</li>
                <li><strong>Wireless Mouse</strong> - Electronics, $29.99</li>
                <li><strong>USB-C Cable</strong> - Accessories, $15.99</li>
            </ol>
            <div class="note">
                <strong>Note:</strong> The database is in-memory and resets on each request. This ensures consistent demo data.
            </div>
        </div>

        <div class="section">
            <h2>🏗️ Architecture</h2>
            <p>This API follows the GEMVC framework's 4-layer architecture:</p>
            <div style="margin: 20px 0;">
                <div style="padding: 15px; background: #f8f9fa; border-radius: 4px; margin: 10px 0;">
                    <strong>1. API Layer</strong> (app/api/Product.php)<br>
                    <span style="color: #666;">Handles HTTP requests and schema validation</span>
                </div>
                <div style="text-align: center; padding: 5px; color: #667eea; font-size: 1.5rem;">↓</div>
                <div style="padding: 15px; background: #f8f9fa; border-radius: 4px; margin: 10px 0;">
                    <strong>2. Controller Layer</strong> (app/controller/ProductController.php)<br>
                    <span style="color: #666;">Orchestrates business logic</span>
                </div>
                <div style="text-align: center; padding: 5px; color: #667eea; font-size: 1.5rem;">↓</div>
                <div style="padding: 15px; background: #f8f9fa; border-radius: 4px; margin: 10px 0;">
                    <strong>3. Model Layer</strong> (app/model/ProductModel.php)<br>
                    <span style="color: #666;">Contains business validations and data transformations</span>
                </div>
                <div style="text-align: center; padding: 5px; color: #667eea; font-size: 1.5rem;">↓</div>
                <div style="padding: 15px; background: #f8f9fa; border-radius: 4px; margin: 10px 0;">
                    <strong>4. Table Layer</strong> (app/table/ProductTable.php)<br>
                    <span style="color: #666;">Performs database operations</span>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>Built with ❤️ using <a href="https://github.com/gemvc/gemvc" style="color: #667eea; text-decoration: none;">GEMVC Framework</a></p>
        <p style="margin-top: 10px; font-size: 0.9rem;">Mock API Demo - © 2024</p>
    </footer>
</body>
</html>
