# Laravel Todo REST API

A production-grade RESTful Todo API built with **Laravel 10**, **Laravel Sanctum API Authentication**, and **MySQL**. Features complete user isolation, search & multi-field filtering, pagination, automated timestamp tracking, comprehensive PHPUnit test coverage, seeders, and factories.

---

## 🌟 Key Features

- **Authentication & Authorization**: Secure registration, login, logout, and token revocation powered by Laravel Sanctum Bearer tokens.
- **User Scoping & Isolation**: Multi-tenant data architecture ensuring every user can only view, edit, and manage their own todos.
- **Todo CRUD & Quick Status Toggle**: Complete task lifecycle management with `Pending`, `In Progress`, and `Completed` status workflow. Automatic management of `completed_at` timestamps.
- **Filtering, Search & Sorting**:
  - Filter by `status` (`Pending`, `In Progress`, `Completed`)
  - Filter by `priority` (`Low`, `Medium`, `High`)
  - Search by keyword across title & description
  - Filter by `due_date`
  - Flexible sorting (`created_at`, `due_date`, `title`, `priority`, `status`) and custom page sizing (`rowsPerPage`)
- **User Profile & Admin Management**: Profile endpoint for authenticated users and user management endpoints.
- **Automated PHPUnit Test Suite**: End-to-end feature test coverage for Auth, Todos, and Users.

---

## 🚀 Quick Setup Guide

### 1. Clone & Install Dependencies
```bash
git clone git@github.com:Anubrata2000/Laravel-CRUD-Project-with-API.git
cd Laravel-CRUD-Project-with-API
composer install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Configure your `.env` database settings:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_api
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Run Migrations & Database Seeders
```bash
php artisan migrate:fresh --seed
```

This populates a demo user:
- **Email**: `demo@example.com`
- **Password**: `password`

### 4. Start Local Development Server
```bash
php artisan serve
```
The API will be accessible at `http://127.0.0.1:8000/api`.

---

## 🧪 Running Automated Tests

Run the full PHPUnit feature test suite:
```bash
php artisan test
```

---

## 📑 API Reference

All protected endpoints require the HTTP Authorization header:
```http
Authorization: Bearer <your_sanctum_token>
Accept: application/json
```

### 🔐 Authentication & Profile Endpoints

| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/register` | Register a new user | No |
| `POST` | `/api/login` | Log in and get Bearer token | No |
| `GET` | `/api/user/profile` | Get current user profile | Yes |
| `PUT` | `/api/user/profile` | Update current user profile | Yes |
| `POST` | `/api/logout` | Revoke current access token | Yes |

---

### 📝 Todo Endpoints

| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/todos` | List paginated todos (supports filters & search) | Yes |
| `POST` | `/api/todos` | Create a new todo | Yes |
| `GET` | `/api/todos/{id}` | Get single todo details | Yes |
| `PUT` | `/api/todos/{id}` | Update todo details | Yes |
| `PATCH` | `/api/todos/{id}/status` | Quick update todo status | Yes |
| `DELETE` | `/api/todos/{id}` | Soft-delete a todo | Yes |

#### Query Parameters for `GET /api/todos`:
- `status`: `Pending` \| `In Progress` \| `Completed`
- `priority`: `Low` \| `Medium` \| `High`
- `search`: Keyword search in title/description
- `due_date`: `YYYY-MM-DD`
- `sort_by`: `created_at` \| `due_date` \| `title` \| `priority` \| `status`
- `sort_order`: `asc` \| `desc`
- `rowsPerPage`: Number of items per page (default: 10)

---

### 👤 User Administration Endpoints

| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/users` | List paginated users | Yes |
| `GET` | `/api/users/{id}` | Get specific user by ID | Yes |
| `PUT` | `/api/users/{id}` | Update specific user by ID | Yes |
| `DELETE` | `/api/users/{id}` | Delete user by ID | Yes |

---

## 📜 License
Open-sourced software licensed under the [MIT license](LICENSE).
