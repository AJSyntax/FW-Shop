# Fandom Wear Shop

A Laravel-based e-commerce platform for custom t-shirt designs and merchandise.

## System Requirements

Before installation, ensure your system meets these requirements:
- PHP >= 8.1
- MySQL >= 5.7
- Node.js >= 14.x
- npm >= 6.x

## Required Software

Install these applications first:
1. XAMPP (with PHP 8.1 or higher)
2. Composer (Package Manager for PHP)
3. Git (Version Control)
4. Node.js and npm (JavaScript Package Manager)

## Installation Guide

### 1. Clone Project
```bash
# Navigate to xampp/htdocs directory
cd C:/xampp/htdocs

# Clone the repository
git clone https://github.com/AJSyntax/FW-Shop.git

# Enter project directory
cd FW-Shop
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install NPM packages
npm install
```

### 3. Configure Environment
```bash
# Create .env file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Set Up Database
1. Start XAMPP Control Panel
2. Start Apache and MySQL services
3. Open phpMyAdmin (http://localhost/phpmyadmin)
4. Create new database named 'fw_shop'
5. Configure .env file with database details:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fandomwearshop
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations and Seeders
```bash
# Run database migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed
```

### 6. Set Up Storage
```bash
# Create storage link
php artisan storage:link
```

### 7. Build Frontend Assets
```bash
# For development
npm run dev

# OR for production
npm run build
```

### 8. Start the Application
Choose one of these methods:

**Method 1: Using Artisan Serve**
```bash
php artisan serve
# Access at: http://localhost:8000
```

**Method 2: Using XAMPP**
- Access at: http://localhost/FW-Shop/public

## Troubleshooting Guide

### Fix Permission Issues
```bash
# For storage directory
chmod -R 777 storage

# For cache directory
chmod -R 777 bootstrap/cache
```

### Fix Composer Memory Issues
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

### Fix Configuration Issues
```bash
# Clear configuration cache
php artisan config:clear

# Clear application cache
php artisan cache:clear
```

## Important Notes

### Security
- Keep your .env file secure and never commit it
- Set proper file permissions
- Regularly update dependencies

### Configuration
- Enable required PHP extensions in XAMPP
- Configure virtual hosts if needed
- Check error logs in case of issues

### Development
- Use `npm run dev` during development
- Use `npm run build` for production
- Keep your dependencies updated

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
