# Project Structure

This document outlines the organization and architecture of the Modern PHP Blog project.

## Directory Structure

```
blog/
├── admin/                 # Admin dashboard and management
│   ├── dashboard.php      # Admin main dashboard
│   ├── login.php          # Admin authentication
│   ├── logout.php         # Logout functionality
│   └── posts.php          # Post management
├── assets/                # Static resources
│   ├── css/              # Stylesheets
│   └── js/               # JavaScript files
├── includes/             # Core PHP components
│   ├── auth.php          # Authentication functions
│   ├── config.php        # Configuration settings
│   ├── database.php      # Database connection
│   ├── footer.php        # Footer template
│   ├── functions.php     # Helper functions
│   ├── header.php        # Header template
│   ├── pdo_database.php  # PDO database handler
│   └── sidebar.php       # Sidebar template
├── templates/            # Page templates
│   ├── home.php          # Homepage template
│   └── post.php          # Single post template
└── public/               # Public-facing pages
    ├── about.php         # About page
    ├── archive.php       # Post archives
    ├── category.php      # Category views
    ├── contact.php       # Contact form
    ├── index.php         # Main entry point
    ├── post.php          # Single post view
    └── search.php        # Search functionality

```
