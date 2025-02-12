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

What You Can Do with FandomWearShop & User Benefits
FandomWearShop is more than just an online T-shirt design store—it has the potential to become a thriving digital marketplace for fandom-inspired artwork. Here’s what you can do with it and how users can benefit:

🚀 Potential Features & Expansions
1️⃣ Sell & Showcase Digital T-Shirt Designs
Offer exclusive, high-quality digital T-shirt designs inspired by movies, comics, anime, gaming, and pop culture.
Users can purchase and download designs or order printed T-shirts with your designs on them.
Option to let customers customize designs (add names, colors, or effects).
2️⃣ Build a Fandom-Driven Community
Fandom-Specific Collections: Organize designs into Marvel, DC, Anime, Sci-Fi, Fantasy, etc.
User Profiles & Wishlists: Let customers create accounts, save favorite designs, and get notified about new collections.
Loyalty Rewards & Discounts: Implement a rewards system where users earn points for purchases, reviews, or referrals.
3️⃣ Open an Artist Marketplace
Allow independent artists & designers to sell their own T-shirt designs on your platform.
Offer royalty-based earnings for each sale, like Redbubble or TeeSpring.
Provide design challenges & competitions where the winning design gets featured.
4️⃣ Expand to Print-on-Demand (POD)
Instead of selling only digital files, integrate with a POD service (e.g., Printful, Teespring) to print and ship T-shirts globally.
Give customers the option to order physical T-shirts with their favorite designs.
5️⃣ Introduce AI-Powered Customization
Use AI tools (like DeepSeek AI) to let users generate personalized T-shirt designs based on prompts.
Offer auto-color adjustment, background removal, and smart recommendations for trending designs.
6️⃣ Exclusive Limited-Edition Drops & Collaborations
Release limited-time exclusive designs inspired by new movie releases, game launches, or fan events.
Collaborate with influencers, fan artists, or brands to create special edition merchandise.
💡 Benefits for Users
🛒 1. A Unique Shopping Experience
✅ Access to exclusive fan-based T-shirt designs they won’t find anywhere else.
✅ High-quality digital downloads or printed shirts shipped to their doorstep.
✅ Easy navigation & quick checkout for a seamless experience.

🎨 2. Support for Fan Artists
✅ Users can buy directly from their favorite designers, supporting independent artists.
✅ Artists get recognition and earn commissions for their work.
✅ Users can submit design ideas or vote on upcoming collections.

🌟 3. Personalization & Customization
✅ Option to personalize T-shirt designs with names, colors, or effects.
✅ Custom AI-generated designs based on user preferences.
✅ Ability to request specific fandom-based designs from artists.

🛍️ 4. Exclusive Deals & Limited Releases
✅ Early access to limited-edition designs before they sell out.
✅ Special discounts for members & frequent buyers.
✅ Seasonal drops & event-based collections (Comic-Con exclusives, Halloween, etc.).

🔥 5. A Fandom Community Hub
✅ Engage in design contests & giveaways.
✅ Access to a blog or forum where fans discuss trends & upcoming releases.
✅ Social media integration for sharing favorite designs and getting featured.

🔮 Future Potential – Scaling the Business
As FandomWearShop grows, you can:

Expand to Hoodies, Posters, Stickers, and More
Launch a Subscription Service (Exclusive monthly T-shirt designs for members)
Develop a Mobile App for easy shopping & notifications
Integrate NFTs or Digital Collectibles (Exclusive blockchain-based designs)