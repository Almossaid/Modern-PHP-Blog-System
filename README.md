Modern PHP Blog
=============

A modern, feature-rich blogging platform built with PHP, featuring an admin dashboard, user authentication, and responsive design.

Preview
-------
<img width="3198" height="1836" alt="Image" src="https://github.com/user-attachments/assets/e2aab2a6-7d32-4428-ad41-f37f777a74af" />

Features
--------
- User-friendly admin dashboard
- Secure authentication system
- Post management with categories
- Responsive design using Bootstrap 5
- Search functionality
- Archive and category views
- Contact form

Prerequisites
-------------
- PHP 7.4 or higher
- MySQL/MariaDB
- Apache web server
- mod_rewrite enabled
- XAMPP (recommended for local development)

Installation
------------
1. Clone or download the repository to your web server directory
2. Create a new MySQL database for the blog
3. Configure database settings in includes/config.php
4. Import the SQL schema (provided in SQL.md)
5. Ensure proper permissions are set for upload directories
6. Access the admin dashboard at /admin/login.php

Project Structure
----------------
- admin/     - Admin dashboard and management (single user who is admin) 
- assets/    - Static resources (CSS, JS, images)
- includes/  - Core PHP components
- templates/ - Page templates
- public/    - Public-facing pages

Configuration
-------------
1. Database settings: includes/config.php
2. Authentication: includes/auth.php
3. Site settings: includes/functions.php

Development Guidelines
---------------------
- Follow PSR-4 autoloading standards
- Use prepared statements for database queries
- Maintain separation of concerns
- Document all functions and classes
- Follow consistent coding style

Security
--------
- All user inputs are sanitized
- Passwords are securely hashed
- SQL injection prevention
- XSS protection implemented
- CSRF protection for forms

Support
-------
For issues and feature requests, please create an issue in the project repository.

License
-------
This project is licensed under the MIT License.

.................
## ❤️ Support This Project

If you find this project useful, consider making a donation to support its development.
....................
[![Donate with PayPal](https://img.shields.io/badge/Donate-PayPal-00457C?style=for-the-badge&logo=paypal)]([YOUR_PAYPAL_DONATION_URL](https://www.paypal.com/donate?token=Q-MQo5TmGn-aCg9edDDsoDNXvJTAZn_tfPyi3TRvVimLmHsrmR9ckQflWwP1fMB5lXAVef3qvw-8psqC))

<img width="412" height="837" alt="Image" src="https://github.com/user-attachments/assets/0b5c5b2e-4507-40b7-9351-70600b6b6c6b" />
<img width="741" height="1027" alt="Image" src="https://github.com/user-attachments/assets/b5df69c5-a30c-4a57-9f90-92a5ce85e3c2" />
