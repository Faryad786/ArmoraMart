# Grocery Store Website

A complete grocery store website with product management, shopping cart, and user authentication.

## Project Structure
```
grocery_store/
├── includes/
│   └── db_connect.php
├── css/
│   └── style.css
├── images/
│   └── products/
├── main.php
├── products.php
├── cart.php
├── checkout.php
├── login.php
├── register.php
├── add_product.php
└── database.sql
```

## Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- mod_rewrite enabled (for Apache)

## Installation Steps

1. **Database Setup**
   ```sql
   # Import the database structure and sample data
   mysql -u your_username -p your_database < database.sql
   ```

2. **Configuration**
   - Edit `includes/db_connect.php` with your database credentials:
     ```php
     $servername = "localhost";
     $username = "your_username";
     $password = "your_password";
     $dbname = "grocery_store";
     ```

3. **File Permissions**
   ```bash
   # Set proper permissions for uploads directory
   chmod 755 images/products
   ```

4. **Web Server Configuration**

   For Apache (.htaccess):
   ```apache
   RewriteEngine On
   RewriteBase /
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
   ```

   For Nginx:
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   ```

5. **Directory Structure**
   ```bash
   # Create required directories
   mkdir -p images/products
   mkdir -p includes
   mkdir -p css
   ```

6. **Sample Images**
   - Add product images to `images/products/`
   - Add hero background image as `images/hero-bg.jpg`

## Features
- User authentication (login/register)
- Product browsing and searching
- Shopping cart management
- Checkout process
- Admin product management
- Responsive design

## Security Considerations
1. Update database credentials
2. Set proper file permissions
3. Enable HTTPS
4. Configure proper session handling
5. Implement input validation
6. Use prepared statements for queries

## Troubleshooting

1. **Database Connection Issues**
   - Verify database credentials
   - Check if MySQL service is running
   - Ensure database exists

2. **Image Upload Issues**
   - Check directory permissions
   - Verify PHP upload settings
   - Check file size limits

3. **Session Issues**
   - Verify session directory permissions
   - Check PHP session configuration
   - Ensure cookies are enabled

## Support
For issues and feature requests, please create an issue in the repository.

## License
This project is licensed under the MIT License. #   A r m o r a M a r t  
 