#  URL Shortener Application

A Laravel-based URL Shortener application with role-based access control (SuperAdmin, Admin, Member).  
This project allows users to create, manage, and track short URLs securely.

---

##  Requirements

Make sure your system meets the following requirements:

- PHP >= 8.2
- Composer
- MySQL


---

###  Project Setup (Local Environment) Clone the Repository

```bash
git clone https://github.com/Pratik120709/url_shortener.git
cd url-shortener

Step 2: Install PHP Dependencies

composer install

php artisan key:generate

Step 3: setup env

Step 4: Database Setup

# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed

Step 5: Run Development Server

# Start the Laravel server
php artisan serve
