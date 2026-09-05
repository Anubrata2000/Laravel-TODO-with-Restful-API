# 📝 Laravel Todo REST API

A production-grade, multi-tenant RESTful Todo API built with **Laravel 10**, **Laravel Sanctum API Authentication**, and **MySQL/SQLite**. Designed for backend learning and production deployment, this API features strict user isolation, search & multi-field filtering, pagination, automated timestamp tracking, comprehensive PHPUnit test coverage, seeders, factories, and a ready-to-use Postman collection.

---

## 📑 Table of Contents
- [About The Project](#-about-the-project)
- [Key Features](#-key-features)
- [Prerequisites](#-prerequisites)
- [Step-by-Step Setup Guide](#-step-by-step-setup-guide)
- [How to Use the Postman Collection](#-how-to-use-the-postman-collection)
- [Running Automated Tests](#-running-automated-tests)
- [API Endpoints Reference](#-api-endpoints-reference)
- [License](#-license)

---

## ℹ️ About The Project

This project provides a robust backend API for managing to-do items securely. Every authenticated user gets their own isolated workspace where they can create, search, filter, update, and manage tasks. It implements Laravel Sanctum Bearer tokens for authentication, ensuring it can easily connect to any frontend framework (React, Vue, Next.js, Angular, Flutter, Swift, Kotlin, etc.).

---

## 🌟 Key Features

- **🔐 Sanctum Bearer Token Auth**: Secure User Registration, Login, Profile Management, and Logout with automatic token revocation.
- **🛡️ Strict User Isolation (Multi-Tenancy)**: Users can only view, update, or delete their own todos. Unauthorized cross-user data access is blocked.
- **📋 Complete Todo Lifecycle**: Create, view, update, status toggle (`Pending`, `In Progress`, `Completed`), and soft-delete.
- **🕒 Automated Timestamp Management**: Changing status to `Completed` automatically sets `completed_at = now()`. Reverting status resets it to `null`.
- **🔍 Advanced Search & Filtering**:
  - Filter by `status` (`Pending`, `In Progress`, `Completed`)
  - Filter by `priority` (`Low`, `Medium`, `High`)
  - Keyword search across `title` & `description`
  - Filter by `due_date`
  - Flexible sorting (`created_at`, `due_date`, `title`, `priority`, `status`)
  - Custom pagination size (`rowsPerPage`)
- **🧪 Comprehensive PHPUnit Tests**: Full test suite covering Auth, Todos, User Scoping, and Administration.
- **📮 Ready-to-use Postman Collection**: Importable JSON collection included in the project root with auto-saving authentication scripts.

---

## 📋 Prerequisites

Before setting up the project, make sure you have the following installed on your machine:

- **PHP** (>= 8.1)
- **Composer** (PHP dependency manager)
- **Database**: MySQL, MariaDB, or SQLite (via Laragon, XAMPP, Docker, or native installation)
- **Postman** (for API testing)

---

## 🚀 Step-by-Step Setup Guide

Follow these steps to get the API up and running on your local machine:

### 1. Clone the Repository
```bash
git clone git@github.com:Anubrata2000/Laravel-CRUD-Project-with-API.git
cd Laravel-CRUD-Project-with-API
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Configure Environment File
Copy `.env.example` to create your `.env` file:
```bash
cp .env.example .env
```
Generate the application key:
```bash
php artisan key:generate
```

### 4. Configure Database Credentials
Open `.env` in your code editor and update the database settings for your environment:

**For MySQL (Laragon / XAMPP / Local MySQL):**
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_api
DB_USERNAME=root
DB_PASSWORD=
```
*(Make sure to create a database named `todo_api` in phpMyAdmin or MySQL CLI before migrating).*

**For SQLite (Zero-config Option):**
```ini
DB_CONNECTION=sqlite
```
*(Create an empty file at `database/database.sqlite` if using SQLite).*

### 5. Run Database Migrations & Seeders
Run the migrations and seed demo data:
```bash
php artisan migrate:fresh --seed
```

This creates the database schema and seeds default test users:
- **Demo User Email**: `demo@example.com`
- **Password**: `password`

### 6. Start the Local Server
```bash
php artisan serve
```
The server will start running at:
`http://127.0.0.1:8000` (API base URL: `http://127.0.0.1:8000/api`)

---

## 📮 How to Use the Postman Collection

A pre-configured Postman Collection is included directly in the root of this project:
`Todo_API.postman_collection.json`

### Step 1: Import Collection into Postman
1. Open **Postman**.
2. Click the **Import** button in the top-left corner.
3. Click **Choose Files** (or drag and drop) and select `Todo_API.postman_collection.json` from the project root folder.
4. Click **Import**.

### Step 2: Collection Overview & Structure
The imported collection contains three organized folders:
- **Authentication & Profile**
  - `Register User`
  - `Login User`
  - `Get Profile`
  - `Update Profile`
  - `Logout User`
- **Todos**
  - `List Todos (Filtered & Paginated)`
  - `Create Todo`
  - `Get Single Todo`
  - `Update Todo`
  - `Update Todo Status`
  - `Delete Todo`
- **User Administration**
  - `List Users`
  - `Get User By ID`
  - `Update User By ID`
  - `Delete User By ID`

### Step 3: Automatic Authentication Flow
This Postman collection features **automatic token saving**:
1. Open and send the **Login User** request (or **Register User**).
2. The built-in Postman test script automatically extracts the returned Sanctum Bearer token and saves it to the `{{token}}` collection variable!
3. All subsequent requests (e.g. `List Todos`, `Create Todo`, `Get Profile`) automatically inherit this Bearer token in their headers without requiring manual copy-pasting.
4. Sending **Logout User** automatically clears the stored token.

---

## 🧪 Running Automated Tests

Run the PHPUnit test suite to verify all endpoints and user-scoping security logic:

```bash
php artisan test
```

Tests run in-memory using SQLite and test:
- User Registration, Login, Profile, and Token Revocation
- Todo CRUD operations, ownership isolation, search, filtering, and timestamp management
- User administration routes

---

## 📑 API Endpoints Reference

All protected endpoints require the following headers:
```http
Authorization: Bearer <your_sanctum_token>
Accept: application/json
```

### 🔐 Authentication & Profile

| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/register` | Register a new user | No |
| `POST` | `/api/login` | Log in and receive Bearer token | No |
| `GET` | `/api/user/profile` | Get current authenticated user profile | Yes |
| `PUT` | `/api/user/profile` | Update current user profile | Yes |
| `POST` | `/api/logout` | Revoke current access token | Yes |

---

### 📝 Todo Management

| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/todos` | List paginated todos (supports search/filter) | Yes |
| `POST` | `/api/todos` | Create a new todo | Yes |
| `GET` | `/api/todos/{id}` | Get single todo details by UUID | Yes |
| `PUT` | `/api/todos/{id}` | Update todo details | Yes |
| `PATCH` | `/api/todos/{id}/status` | Quick update todo status | Yes |
| `DELETE` | `/api/todos/{id}` | Soft-delete a todo | Yes |

#### Query Parameters for `GET /api/todos`:
| Parameter | Type | Options / Description | Example |
| :--- | :--- | :--- | :--- |
| `status` | string | `Pending`, `In Progress`, `Completed` | `/api/todos?status=Pending` |
| `priority` | string | `Low`, `Medium`, `High` | `/api/todos?priority=High` |
| `search` | string | Search keyword in title or description | `/api/todos?search=report` |
| `due_date` | string | Date in `YYYY-MM-DD` format | `/api/todos?due_date=2026-09-15` |
| `sort_by` | string | `created_at`, `due_date`, `title`, `priority`, `status` | `/api/todos?sort_by=due_date` |
| `sort_order` | string | `asc` or `desc` (default: `desc`) | `/api/todos?sort_order=asc` |
| `rowsPerPage`| integer| Number of items per page (default: 10) | `/api/todos?rowsPerPage=15` |

---

### 👤 User Administration

| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/users` | List paginated users | Yes |
| `GET` | `/api/users/{id}` | Get specific user by ID | Yes |
| `PUT` | `/api/users/{id}` | Update specific user by ID | Yes |
| `DELETE` | `/api/users/{id}` | Delete user by ID | Yes |

---

## 📜 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
