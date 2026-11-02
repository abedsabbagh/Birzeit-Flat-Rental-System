# Birzeit Flat Rental System

An academic PHP/MySQL rental web application prototype featuring owner and customer registration, apartment listings, search filters, login sessions, and rental requests.

## Technologies

- PHP, MySQL, PDO prepared statements
- HTML, CSS, session-based application workflows

## Source structure

```text
includes/   Database connection, authentication, shared layout
public/     Listing pages, search, rental requests, account views
register/   Customer and property-owner registration flows
```

## Features visible in the source

- Multi-step registration for customers and property owners
- Authentication and distinct user-role workflows
- Apartment search using multiple filters and validated sort columns
- Property details, owner listings, and rental records
- **Demo-only rental confirmation:** no real payments and no card details collected in this public copy

## Running locally — limitations

This repository preserves an academic project code snapshot; it is **not a deployable complete product**.

- PHP 8+ and a MySQL server are required.
- The original database schema / SQL dump was **not included** in the source archive. Reconstruct or supply the correct schema before running the application.
- Create your own local test database. Set the server's environment variables `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`. The `.env.example` file documents the names but is not loaded automatically.
- The original application used course-specific web-root URLs; the published copy expects to be served under `/Birzeit-Flat-Rental-System/` or to have those paths adjusted.
- Some referenced images/pages are absent from the provided source snapshot.
- The project has not been end-to-end tested in a browser with a database in this packaging step.

**Never use production credentials, real identity details, live bank-account numbers, or real payment-card information in this prototype.** There is no payment gateway. All rental confirmation is a demonstration only.

## What changed for safe public sharing

- Removed hard-coded database username, database name, and password. Environment-variable configuration is used instead.
- Removed contact phone number from the original example footer and course-specific numeric identifier from root URLs.
- Removed database diagnostic page.
- Removed the form collecting card numbers; the rental step now records a pending demonstration request with no actual payment.
- Aligned a handful of session variables and dead navigation links with the existing authentication flow.
- Added `.gitignore`, `.env.example`, and `SECURITY.md`.

## Status

**Academic prototype / source-code portfolio sample. Not production ready.** It requires a supplied database schema and further functionality/security review before deployment. No claims are made about production deployment or a live payment integration.

## Security notes

See [SECURITY.md](SECURITY.md) for known areas that would require work before using this application beyond a local demonstration.
