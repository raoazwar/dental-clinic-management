# Dental Clinic Dashboard

A modern, responsive web application for dental clinic management built with Laravel 12, Jetstream, Livewire, and Tailwind CSS.

## Features

### 🏥 **Dashboard**
- Real-time statistics and metrics
- Monthly sales charts
- Recent sales overview
- Low stock product alerts
- Revenue and profit tracking

### 📦 **Inventory Management**
- Complete CRUD operations for products
- Product categories (Dental Supplies, Medications, Equipment, Consumables)
- Stock level monitoring with alerts
- Expiry date tracking
- SKU management
- Cost and price tracking

### 💰 **Sales Management**
- Create and manage sales transactions
- Multiple payment methods (Cash, Card, Transfer)
- Invoice generation with unique numbers
- Sales history and reporting
- Profit calculation

### 👥 **User Management**
- Role-based access control
- Administrator role (full access)
- Staff role (inventory management only)
- Secure authentication with Jetstream

### 🎨 **Modern UI/UX**
- Responsive design with Tailwind CSS
- Clean and professional interface
- Mobile-friendly navigation
- Real-time data updates with Livewire

## Requirements

- PHP 8.2 or higher
- MySQL 5.7 or higher
- Composer
- Node.js and NPM

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd dental-clinic
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file and set your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=dental_clinic
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database with sample data**
   ```bash
   php artisan db:seed --class=DentalClinicSeeder
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

## Default Users

After running the seeder, you can log in with these credentials:

### Administrator
- **Email:** admin@dentalclinic.com
- **Password:** password
- **Permissions:** Full access to all features

### Staff Member
- **Email:** staff@dentalclinic.com
- **Password:** password
- **Permissions:** Inventory management only

## Usage

### Dashboard
- View real-time statistics
- Monitor sales performance
- Check low stock alerts
- Review recent transactions

### Inventory Management
1. Navigate to "Inventory" in the main menu
2. Click "Add Product" to create new items
3. Use the table to view, edit, or delete products
4. Monitor stock levels and expiry dates

### Sales Management
1. Navigate to "Sales" in the main menu
2. Click "New Sale" to create a transaction
3. Select products and quantities
4. Choose payment method
5. Complete the sale

## Database Structure

### Tables
- `users` - User accounts with roles
- `products` - Inventory items
- `sales` - Sales transactions
- `sale_items` - Individual items in sales

### Key Features
- Automatic invoice number generation
- Stock level tracking
- Profit margin calculations
- Expiry date monitoring

## Customization

### Adding New Product Categories
Edit the `create.blade.php` view to add new categories to the dropdown.

### Modifying User Roles
Update the `User` model and migration to add new roles.

### Styling
The application uses Tailwind CSS. Modify the classes in the Blade templates to customize the appearance.

## Security Features

- CSRF protection
- SQL injection prevention
- XSS protection
- Role-based access control
- Secure password hashing
- Session management

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions, please contact the development team or create an issue in the repository.
