# Group 6 - Team Members & Roles

## Project: Events Management API with Laravel Sanctum

### Team Members

| Username | Email | Role | Responsibilities |
|----------|-------|------|------------------|
| **mp-catedrilla** | mpcatedrilla@ccc.edu.ph | Project Lead / DevOps | Repository setup, project initialization, deployment configuration |
| **Jwelynie123** | dgperez@ccc.edu.ph | Database Architect | Database migrations, PostgreSQL configuration, schema design |
| **tzahhhahaha** | jaamado@ccc.edu.ph | Backend Developer - Auth | Authentication system, Sanctum integration, User model |
| **yumaki00** | abbayanban@ccc.edu.ph | Backend Developer - Events | Event API endpoints, Event model, CRUD operations |
| **markmasongsong** | mcmasongsong@ccc.edu.ph | Backend Developer - Participants | Participant API endpoints, registration logic, email validation |
| **rayvenedburato** | reburato@ccc.edu.ph | API Engineer / Documentation | API routing, middleware setup, seeders, README documentation |

## Contribution Summary

### mp-catedrilla
- Initial Laravel project structure
- Composer dependencies configuration
- Git repository setup
- Project coordination

### Jwelynie123
- Created database migrations for Users, Events, Participants tables
- Implemented Sanctum personal_access_tokens migration
- Configured PostgreSQL database connection
- Database schema design and relationships

### tzahhhahaha
- Implemented User model with HasApiTokens trait
- Created AuthController with login/logout/register methods
- Configured Sanctum authentication guard
- Token generation and validation logic

### yumaki00
- Created Event Eloquent model
- Implemented EventController with full CRUD operations
- Event validation rules and error handling
- Event-Participant relationship setup

### markmasongsong
- Created Participant Eloquent model
- Implemented ParticipantController with CRUD
- Email uniqueness validation
- Event-specific participant queries

### rayvenedburato
- Defined API routes with Sanctum middleware protection
- Created database seeders with sample data
- Setup Laravel bootstrap and middleware
- Comprehensive README documentation

## API Endpoints Overview

### Authentication (Public)
- `POST /api/login` - User login
- `POST /api/register` - User registration

### Protected Routes (Bearer Token Required)
- `POST /api/logout` - User logout
- `GET /api/user` - Get current user

#### Events
- `GET /api/events` - List all events
- `GET /api/events/{id}` - Get single event
- `POST /api/events` - Create event
- `PUT /api/events/{id}` - Update event
- `DELETE /api/events/{id}` - Delete event

#### Participants
- `GET /api/participants` - List all participants
- `GET /api/participants/{id}` - Get single participant
- `GET /api/participants/event/{event_id}` - List by event
- `POST /api/participants` - Register participant
- `PUT /api/participants/{id}` - Update participant
- `DELETE /api/participants/{id}` - Delete participant

## Technologies Used

- **Framework:** Laravel 10.x
- **Authentication:** Laravel Sanctum
- **Database:** PostgreSQL
- **Language:** PHP 8.1+
- **API Style:** RESTful JSON API

---
*Midterm Project - Group 6*
