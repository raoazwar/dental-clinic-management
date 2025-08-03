# Dental Clinic Management System

A comprehensive web-based management system for dental clinics built with Laravel, featuring role-based access control, inventory management, sales tracking, and user management.

## 🚀 Features

### 🔐 Authentication & Authorization
- **Role-based Access Control**: Administrator and Staff roles
- **Email Verification**: Secure user registration with email verification
- **Two-Factor Authentication**: Enhanced security with 2FA support
- **Password Reset**: Secure password recovery system

### 📊 Dashboard
- **Role-specific Dashboards**: Different views for administrators and staff
- **Real-time Statistics**: Sales, inventory, and user analytics
- **Interactive Charts**: Visual representation of data trends
- **Filter Controls**: Date range filtering for analytics (Admin only)

### 🏥 Core Management Features

#### Inventory Management
- **Product Management**: Add, edit, delete products with categories
- **Stock Tracking**: Real-time inventory levels and low stock alerts
- **Category Management**: Organize products by categories
- **SKU Management**: Unique product identification

#### Sales Management
- **Sales Processing**: Create and manage sales transactions
- **Invoice Generation**: Automatic invoice numbering
- **Payment Methods**: Support for cash, card, and transfer payments
- **Sales History**: Complete transaction history and reporting

#### User Management (Admin Only)
- **User CRUD**: Create, read, update, delete users
- **Role Assignment**: Assign administrator or staff roles
- **Email Verification**: Manage user verification status
- **User Analytics**: User activity and performance metrics

### 🔍 Advanced Features
- **Global Search**: AJAX-powered search across all entities
- **Modern UI**: Beautiful, responsive interface with Tailwind CSS
- **Real-time Updates**: Live data updates without page refresh
- **Export Capabilities**: Data export functionality
- **Mobile Responsive**: Works seamlessly on all devices

## 🛠️ Technology Stack

- **Backend**: Laravel 11 (PHP 8.3+)
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Fortify & Jetstream
- **Email**: Laravel Mail with customizable notifications
- **Build Tool**: Vite

## 📋 Requirements

- PHP 8.3 or higher
- Composer
- Node.js & NPM
- MySQL 8.0+ or PostgreSQL 13+
- Web server (Apache/Nginx)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/dental-clinic-management.git
cd dental-clinic-management
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dental_clinic
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations and Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 6. Build Assets
```bash
npm run build
```

### 7. Set Up Storage
```bash
php artisan storage:link
```

### 8. Configure Email (Optional)
For email verification, configure your email settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email
MAIL_FROM_NAME="${APP_NAME}"
```

### 9. Start the Application
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 👥 Default Users

After running the seeders, you'll have these default users:

### Administrator
- **Email**: admin@dentalclinic.com
- **Password**: password
- **Role**: Administrator

### Staff Member
- **Email**: staff@dentalclinic.com
- **Password**: password
- **Role**: Staff

## 🔧 Usage

### Administrator Access
Administrators have full access to:
- User management
- Sales analytics and reporting
- Revenue tracking
- System configuration
- All staff features

### Staff Access
Staff members can:
- Manage inventory (products and categories)
- Process sales transactions
- View low stock alerts
- Search across products and categories
- Access basic dashboard statistics

### Key Features Usage

#### Adding Products
1. Navigate to **Inventory** → **Add Product**
2. Fill in product details (name, category, quantity, price, etc.)
3. Save the product

#### Processing Sales
1. Go to **Sales** → **New Sale**
2. Select products and quantities
3. Choose payment method
4. Complete the transaction

#### Managing Categories
1. Navigate to **Categories**
2. Add, edit, or delete categories
3. Categories with products cannot be deleted

#### User Management (Admin Only)
1. Go to **Users** (visible only to administrators)
2. Create new users with appropriate roles
3. Manage user verification status

## 🔒 Security Features

- **CSRF Protection**: All forms are protected against CSRF attacks
- **SQL Injection Prevention**: Laravel's Eloquent ORM prevents SQL injection
- **XSS Protection**: Blade templating engine provides XSS protection
- **Role-based Access**: Strict role-based access control
- **Email Verification**: Required for all new user accounts
- **Password Hashing**: Secure password storage using bcrypt

## 📁 Project Structure

```
dental-clinic/
├── app/
│   ├── Console/Commands/     # Custom Artisan commands
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Notifications/       # Email notifications
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/              # Blade templates
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
├── routes/                 # Application routes
└── storage/               # File storage
```

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📞 Support

For support and questions:
- Create an issue on GitHub
- Email: support@dentalclinic.com

## 🔄 Updates

To update the application:
```bash
git pull origin main
composer install
php artisan migrate
npm run build
```

## 📊 System Requirements

- **PHP**: 8.3+
- **Memory**: 512MB RAM minimum
- **Storage**: 1GB free space
- **Database**: MySQL 8.0+ or PostgreSQL 13+

---

**Built with ❤️ using Laravel**
