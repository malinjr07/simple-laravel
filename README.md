# 📚 Laravel 12.x Book API (SQLite)

This is a simple RESTful API built with Laravel 12.x to manage a list of books. It supports basic CRUD operations and uses SQLite as the database. All responses are in JSON format and the API is documented using Swagger.

---

## 🚀 Features

-   Create, read, update, delete books
-   User authentication with Laravel Sanctum
-   File uploads with media storage
-   Input validation using Form Requests
-   SQLite for easy local setup
-   Factory, Seeder, and Migration usage
-   Swagger (OpenAPI) API documentation

---

## 📌 API Endpoints

### Book Endpoints (Protected by Bearer Token)

| Method | Endpoint          | Description             | Auth Required |
| ------ | ----------------- | ----------------------- | ------------- |
| GET    | `/api/books`      | Get all books           | No           |
| GET    | `/api/books/{id}` | Get a book by ID        | No           |
| POST   | `/api/books`      | Create a new book       | No           |
| PUT    | `/api/books/{id}` | Update an existing book | No           |
| DELETE | `/api/books/{id}` | Delete a book           | No           |

### Authentication Endpoints

| Method | Endpoint       | Description           | Auth Required |
| ------ | -------------- | --------------------- | ------------- |
| POST   | `/api/sign-in` | Sign in and get token | No            |
| POST   | `/api/sign-up` | Register a new user   | No            |
| POST   | `/api/logout`  | Logout (revoke token) | Yes           |

### User Management (Protected)

| Method | Endpoint          | Description      | Auth Required |
| ------ | ----------------- | ---------------- | ------------- |
| GET    | `/api/users`      | Get all users    | Yes           |
| GET    | `/api/users/{id}` | Get a user by ID | Yes           |
| DELETE | `/api/users/{id}` | Delete a user    | Yes           |

### Media Upload

| Method | Endpoint      | Description          | Auth Required |
| ------ | ------------- | -------------------- | ------------- |
| POST   | `/api/upload` | Upload an image file | No            |

---

## 🛠️ Local Setup

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/laravel-book-api.git
cd laravel-book-api
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Set Up Environment

```bash
cp .env.example .env
php artisan key:generate

```

Update the .env file to use SQLite:

```env
DB_CONNECTION=sqlite
```

### 4. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

This will create the necessary tables and populate them with sample data.

### 5. Create Storage Link for Media Uploads

```bash
php artisan storage:link
```

This command creates a symbolic link from `public/storage` to `storage/app/public`, allowing you to access uploaded files via the web.

### 6. Start the Development Server

```bash
php artisan serve
```

This will start the server at `http://localhost:8000`.

## 📒 API Documentation (Swagger)

After setting up the project:

1. Generate the Swagger docs:

    ```bash
    php artisan l5-swagger:generate
    ```

    This command scans your annotated controllers and generates OpenAPI specifications.

2. Access the Swagger UI:

    [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

3. Updating Swagger Documentation:

    Whenever you make changes to your API endpoints or annotations, regenerate the docs with:

    ```bash
    php artisan l5-swagger:generate
    ```

The Swagger UI provides interactive documentation where you can:

-   Browse all available endpoints
-   See required parameters and response structures
-   Test endpoints directly from the browser
-   Authorize with bearer tokens for testing protected endpoints

---

## 📂 Technologies Used

-   Laravel 12.x
-   PHP 8.x+
-   SQLite
-   L5 Swagger (OpenAPI)
-   PHPUnit

---

## ✅ Requirements

-   PHP >= 8.1
-   Composer
-   SQLite3

---
