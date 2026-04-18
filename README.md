# Events Management API with Laravel Sanctum

A complete RESTful API built with Laravel and authenticated via Laravel Sanctum.

## Features

- **Token-based Authentication** with Laravel Sanctum
- **Full CRUD Operations** for Events
- **Full CRUD Operations** for Participants
- **PostgreSQL Database** support
- **RESTful API Design**

## Requirements

- PHP 8.1+
- PostgreSQL
- Composer

## Installation

### 1. Clone and Install Dependencies

```bash
cd events-api-laravel
composer install
```

### 2. Environment Configuration

```bash
cp .env.example .env
# Edit .env with your database credentials
php artisan key:generate
```

### 3. Database Setup

```bash
php artisan migrate
php artisan db:seed
```

### 4. Start the Server

```bash
php artisan serve
```

## API Endpoints

### Authentication (Public)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/login` | Login & get token |
| POST | `/api/register` | Register new user |

### Protected Routes (Require Bearer Token)

#### Events

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/events` | List all events |
| GET | `/api/events/{id}` | Get single event |
| POST | `/api/events` | Create event |
| PUT | `/api/events/{id}` | Update event |
| DELETE | `/api/events/{id}` | Delete event |

#### Participants

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/participants` | List all participants |
| GET | `/api/participants/{id}` | Get single participant |
| GET | `/api/participants/event/{event_id}` | List by event |
| POST | `/api/participants` | Register participant |
| PUT | `/api/participants/{id}` | Update participant |
| DELETE | `/api/participants/{id}` | Delete participant |

#### Auth

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/logout` | Revoke token |
| GET | `/api/user` | Get current user |

## Usage Example

### 1. Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "password123"}'
```

Response:
```json
{
  "user": {"id": 1, "name": "Admin User", "email": "admin@example.com"},
  "token": "1|laravel_sanctum_token_here"
}
```

### 2. Access Protected Route

```bash
curl -X GET http://localhost:8000/api/events \
  -H "Authorization: Bearer 1|laravel_sanctum_token_here"
```

### 3. Create Event

```bash
curl -X POST http://localhost:8000/api/events \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"title": "New Event", "description": "Details", "event_date": "2024-12-25"}'
```

### 4. Logout

```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Default Credentials

- **Email:** admin@example.com
- **Password:** password123

## Authentication Method: Laravel Sanctum

### What is Laravel Sanctum?

Laravel Sanctum provides a featherweight authentication system for SPAs (Single Page Applications), mobile applications, and simple token-based APIs. It allows each user of your application to generate multiple API tokens for their account.

### Authentication Flow (Step-by-Step)

```
┌─────────┐     Login Request      ┌─────────┐
│  Client │ ─────────────────────> │   API   │
│         │  (email + password)     │         │
│         │                        │         │
│         │ <───────────────────── │         │
│         │   Bearer Token         │         │
└─────────┘                        └─────────┘
       │                                  │
       │   Request Protected Resource   │
       │ ─────────────────────────────> │
       │   (Authorization: Bearer TOKEN)│
       │                                  │
       │ <─────────────────────────────   │
       │        Protected Data            │
       │                                  │
       │   Logout Request               │
       │ ─────────────────────────────> │
       │   (Token Revoked)              │
```

**Step 1: Login**
- Client sends POST request to `/api/login` with credentials
- Server validates credentials using Laravel's Auth system
- Server generates a unique personal access token via Sanctum
- Server returns token to client

**Step 2: Token Handling**
- Client stores token (localStorage, session, or memory)
- Client includes token in `Authorization: Bearer {token}` header for subsequent requests
- Sanctum middleware validates token on protected routes

**Step 3: Protected Routes**
- Routes use `auth:sanctum` middleware
- Sanctum decodes token and authenticates the user
- Request proceeds with authenticated user context
- User can access their data via `$request->user()`

**Step 4: Logout**
- Client sends POST to `/api/logout` with token
- Server deletes the token from `personal_access_tokens` table
- Client removes token from storage
- User is fully logged out

### Advantages of Sanctum

**Strengths:**
1. **Simple & Lightweight** - No complex OAuth2 setup required
2. **Multiple Tokens per User** - Users can have tokens for different devices/apps
3. **Token Expiration** - Tokens can have expiration dates for security
4. **SPA & Mobile Ready** - Works seamlessly with JavaScript frontends and mobile apps
5. **Laravel Native** - Built by Laravel team, perfect integration with Eloquent and Auth
6. **No External Dependencies** - Unlike Passport, no OAuth server needed
7. **Stateless** - Server doesn't need to maintain session state (scalable)

### Disadvantages / Limitations

**Limitations:**
1. **No Built-in OAuth2** - Unlike Passport, doesn't support third-party OAuth providers out of box
2. **Token Storage** - Tokens are stored in database (personal_access_tokens table) - can grow large with many users
3. **No Refresh Tokens** - Simple tokens don't have automatic refresh mechanism (must re-login when expired)
4. **Database Dependency** - Each API request queries the database to validate token (slight performance overhead)
5. **Manual Token Management** - Users must manually revoke tokens; no automatic cleanup of old tokens
6. **Less Secure than OAuth2** - For high-security applications, OAuth2 with Passport is recommended

## Database Schema

- **users** - Authentication (11 users seeded)
- **personal_access_tokens** - Sanctum tokens storage
- **events** - Event records (100 events seeded)
- **participants** - Event participants (1,000 participants seeded)

**Total Records: 1,111** (exceeds 1,000 requirement)
