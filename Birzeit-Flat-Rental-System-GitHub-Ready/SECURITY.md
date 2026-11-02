# Security and scope

This project is a classroom prototype, not production-ready software. Do not deploy it publicly or enter actual credentials, national identification numbers, bank-account information, or card details.

The original coursework archive contained hard-coded database login details. Those values have **not** been published in this archive. If they were connected to an active server, the database password should be rotated independently.

The source still needs a thorough security review and hardening (including CSRF protection, access-control audits, field validation, authorization of user data, privacy-by-design, safe error handling, and server-side recalculation of financial amounts). A working database schema was not supplied with the original archive.

The former direct card-number collection was replaced with a simulated confirmation step; the code stores a non-sensitive `DEMO_ONLY` marker in the original schema's `credit_card` field solely for compatibility with the academic database design. **No live payment occurs.**
