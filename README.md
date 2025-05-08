# 📚 Laravel 12.x Book API (SQLite)

This is a simple RESTful API built with Laravel 12.x to manage a list of books. It supports basic CRUD operations and uses SQLite as the database. All responses are in JSON format and the API is documented using Swagger.

---

## 🚀 Features

-   Create, read, update, delete books
-   Input validation using Form Requests
-   SQLite for easy local setup
-   Factory, Seeder, and Migration usage
-   Swagger (OpenAPI) API documentation

---

## 📌 API Endpoints

| Method | Endpoint          | Description             |
| ------ | ----------------- | ----------------------- |
| GET    | `/api/books`      | Get all books           |
| GET    | `/api/books/{id}` | Get a book by ID        |
| POST   | `/api/books`      | Create a new book       |
| PUT    | `/api/books/{id}` | Update an existing book |
| DELETE | `/api/books/{id}` | Delete a book           |

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

Update the `.env` file to use SQLite:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Then create the SQLite file:

```bash
touch database/database.sqlite
```

### 4. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

This will create the `books` table and populate it with sample data (if seeders are set up).

### 5. Serve the Application

```bash
php artisan serve
```

Visit: [http://localhost:8000](http://localhost:8000)

---

## 📒 API Documentation (Swagger)

After setting up the project:

1. Generate the Swagger docs:

    ```bash
    php artisan l5-swagger:generate
    ```

2. Access the UI:

    [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

This interactive documentation shows all available endpoints, parameters, request/response structures, and validation rules.

---

## 🧪 Running Tests

```bash
php artisan test
```

This runs all Feature and Unit tests including CRUD test coverage for the `BookController`.

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
