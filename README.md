# Stonify

## Features

- **Product Management**
  - Product catalog with images
  - Product categories and search
  - Stock management
  
- **Shopping Experience**
  - User-friendly shopping cart
  - Secure checkout process
  - Order history and tracking
  
- **Payment Integration**
  - Integrated with Midtrans payment gateway
  - Multiple payment methods support
  - Cash on Delivery (COD) option
  
- **Customer Service**
  - Real-time chat with admin support
  - Chat room management
  - Message history
  
- **Content Management**
  - Article/blog system
  - Content publishing workflow
  
- **User Management**
  - Role-based access control
  - User authentication and authorization
  - Profile management

## Tech Stack

- PHP 8.x
- Laravel 10.x
- MySQL 5.7+
- Node.js & NPM
- Pusher (for real-time features)
- Midtrans Payment Gateway


## Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/stonify.git
cd stonify
```

2. Install PHP dependencies
```bash
composer install
```

3. Install NPM dependencies
```bash
npm install
```

4. Create environment file
```bash
cp .env.example .env
```

5. Generate application key
```bash
php artisan key:generate
```

6. Configure your database in .env file
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stonify_backend
DB_USERNAME=root
DB_PASSWORD=
```

7. Run database migrations and seeders
```bash
php artisan migrate --seed
```

8. Build assets
```bash
npm run build
```

9. Start the development server
```bash
php artisan serve
```

## Environment Variables


### Database
- `DB_CONNECTION`: Database connection (mysql)
- `DB_HOST`: Database host
- `DB_PORT`: Database port
- `DB_DATABASE`: Database name
- `DB_USERNAME`: Database username
- `DB_PASSWORD`: Database password

### Payment Gateway (Midtrans)
- `MIDTRANS_SERVER_KEY`: Midtrans server key
- `MIDTRANS_CLIENT_KEY`: Midtrans client key

### Real-time Features (Pusher)
- `BROADCAST_DRIVER`: Broadcasting driver (pusher)
- `PUSHER_APP_ID`: Pusher application ID
- `PUSHER_APP_KEY`: Pusher application key
- `PUSHER_APP_SECRET`: Pusher application secret
- `PUSHER_APP_CLUSTER`: Pusher application cluster

## Usage

1. Access the application at `http://localhost:8000`
2. Login with admin credentials:
   - Email: admin@admin.com
   - Password: 

3. Start managing:
   - Add/edit products
   - Process orders
   - Manage users
   - Handle customer chat
   - Publish articles

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
