
# Birzeit Flat Rental System

An academic PHP/MySQL web application prototype designed to support apartment listings, property searches, and rental requests.

## Technologies

- PHP 8+
- MySQL
- PDO Prepared Statements
- HTML and CSS
- PHP Sessions

## Features

- Customer and property-owner registration
- Login and session-based workflows
- Apartment listings and property details
- Property search with multiple filters
- Rental request workflows
- Demonstration-only rental confirmation

## Project Structure

- `includes/` — Database connection, authentication, and shared components
- `public/` — Property listings, search, rental requests, and account pages
- `register/` — Customer and property-owner registration

## Local Setup

1. Install PHP 8+ and MySQL.
2. Create a local MySQL database.
3. Configure these environment variables:
   - `DB_HOST`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASSWORD`
4. Place the application in your local PHP web-server directory.
5. Adjust any project-specific paths if necessary.

**Note:** The original database schema was not included in the archived project. A compatible schema is required before the application can run.

## Security and Limitations

This repository contains an academic project prototype, not a production-ready application.

- Database credentials are configured through environment variables.
- Rental confirmations are demonstrations only.
- No payment gateway is implemented.
- The public version does not collect payment-card details.
- Some original assets and pages may be missing.
- The project has not been fully tested end-to-end with a reconstructed database.

See `SECURITY.md` for additional security information.

## Project Purpose

This project demonstrates practical experience with PHP, relational databases, PDO prepared statements, user registration, session handling, and database-backed web workflows.
