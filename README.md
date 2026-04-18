# Events Management API with HTTP Basic Authentication

A complete RESTful API built with Laravel and authenticated via HTTP Basic Authentication.

## Features

- **HTTP Basic Authentication** (username:password in every request)
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

### Authentication Test

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/auth-test` | Test Basic Auth credentials |

### All Routes Require Basic Auth

**Header format:** `Authorization: Basic base64(email:password)`

**Example:**
- Email: `admin@example.com`
- Password: `password123`
- Base64: `YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM=`
- Header: `Authorization: Basic YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM=`

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


## Usage Example - HTTP Basic Authentication

### 1. Test Authentication

```bash
curl -X GET http://localhost:8000/api/auth-test \
  -H "Authorization: Basic YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM="
```

Response:
```json
{
  "message": "Basic Authentication successful",
  "user": {"id": 1, "name": "Admin User", "email": "admin@example.com"},
  "auth_method": "HTTP Basic Authentication"
}
```

### 2. Access Events (with Basic Auth)

```bash
curl -X GET http://localhost:8000/api/events \
  -H "Authorization: Basic YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM="
```

### 3. Create Event (with Basic Auth)

```bash
curl -X POST http://localhost:8000/api/events \
  -H "Content-Type: application/json" \
  -H "Authorization: Basic YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM=" \
  -d '{"title": "New Event", "description": "Details", "event_date": "2024-12-25"}'
```

### Using curl --user (easier method)

```bash
# Test auth
curl --user admin@example.com:password123 http://localhost:8000/api/auth-test

# Get events
curl --user admin@example.com:password123 http://localhost:8000/api/events

# Get participants
curl --user admin@example.com:password123 http://localhost:8000/api/participants
```

## Default Credentials

- **Email:** admin@example.com
- **Password:** password123

## Authentication Method: HTTP Basic Authentication

### What is HTTP Basic Authentication?

HTTP Basic Authentication is a simple authentication scheme built into the HTTP protocol. The client sends HTTP requests with the `Authorization` header that contains the word `Basic` followed by a space and a base64-encoded string `username:password`.

### Authentication Flow (Step-by-Step)

```
┌─────────┐                          ┌─────────┐
│  Client │ ───────────────────────> │   API   │
│         │  Authorization Header    │         │
│         │  Basic base64(user:pass) │         │
│         │                          │         │
│         │ <──────────────────────  │         │
│         │   Requested Data OR      │         │
│         │   401 Unauthorized       │         │
└─────────┘                          └─────────┘
```

**Step 1: Encode Credentials**
- Client combines email and password: `admin@example.com:password123`
- Client encodes to base64: `YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM=`

**Step 2: Send Request with Header**
- Client includes header: `Authorization: Basic YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM=`
- Server receives the request and extracts the header

**Step 3: Server Validation**
- Server decodes base64 to get `email:password`
- Server validates credentials against database
- If valid: Returns requested data (events, participants)
- If invalid: Returns 401 Unauthorized with `WWW-Authenticate` header

**Step 4: Every Request**
- Basic Auth requires credentials in **every single request**
- No session or token storage on server
- Stateless authentication

### Advantages of HTTP Basic Auth

**Strengths:**
1. **Extremely Simple** - No complex token generation or session management
2. **HTTP Standard** - Built into HTTP protocol, widely supported
3. **Stateless** - Server doesn't need to maintain sessions or token storage
4. **Easy to Test** - Simple to test with curl or Postman
5. **Works Everywhere** - Supported by all HTTP clients and browsers
6. **No Database Overhead** - No tokens table needed (only users table)
7. **No Expiration Logic** - Credentials valid as long as user exists

### Disadvantages / Limitations

**Limitations:**
1. **Credentials in Every Request** - Password sent repeatedly (security risk without HTTPS)
2. **No Logout Mechanism** - Can't revoke access until password is changed
3. **No Token Expiration** - Credentials work indefinitely until password change
4. **Base64 is Not Encryption** - Credentials are only encoded, not encrypted
5. **Vulnerable to Replay Attacks** - Same credentials work from any device
6. **Must Use HTTPS** - Without SSL/TLS, credentials visible in plain text
7. **Less Secure than OAuth2** - Not suitable for high-security applications

## Database Schema

- **users** - Authentication (11 users seeded)
- **events** - Event records (100 events seeded)
- **participants** - Event participants (1,000 participants seeded)

**Total Records: 1,111** (exceeds 1,000 requirement)

## Testing with Postman

### 1. Basic Auth Test
- **URL:** `http://localhost:8000/api/auth-test`
- **Method:** GET
- **Authorization:** Basic Auth
  - Username: `admin@example.com`
  - Password: `password123`

### 2. Get All Events
- **URL:** `http://localhost:8000/api/events`
- **Method:** GET
- **Authorization:** Basic Auth (same credentials)

### 3. Get All Participants
- **URL:** `http://localhost:8000/api/participants`
- **Method:** GET
- **Authorization:** Basic Auth (same credentials)
