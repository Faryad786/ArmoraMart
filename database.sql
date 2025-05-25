-- Create database
CREATE DATABASE IF NOT EXISTS grocery_store;
USE grocery_store;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255),
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category_id INT,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert sample categories
INSERT INTO categories (name, description, image) VALUES
('Fruits', 'Fresh seasonal fruits', 'https://5.imimg.com/data5/BB/VF/LY/SELLER-29366844/fresh-fruits.jpeg'),
('Vegetables', 'Fresh seasonal vegetables', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQWuej9PSYddWMhn45nwRoZKpBy6fFwm755bA&s'),
('Dairy', 'Fresh milk, cheese and dairy products', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcThqN5hvp2B2wk0Dt24a1preOlL6amtq9BBOw&s'),
('Bakery', 'Fresh bread, pastries and baked goods', 'https://media.istockphoto.com/id/617603536/photo/freshly-baked-bread-on-wooden-table.jp[…]12&w=0&k=20&c=erb6tj3U4wrm_nsSzFpfJWUzxSZaMvOs9i67aRqbZ0Y='),
('Meat', 'Fresh meat and poultry', 'https://t3.ftcdn.net/jpg/02/26/53/80/360_F_226538033_C42p96JDNwkSdQs86Agxd1TtaVJsyJ71.jpg');

-- Insert sample products
INSERT INTO products (category_id, name, description, price, image, stock) VALUES
(1, 'Fresh Apples', 'Sweet and crispy apples', 2.99, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQFSF5tFUet_8zyIc60fV655WwYuwwcWN_xLg&s', 100),
(1, 'Bananas', 'Yellow ripe bananas', 1.99, 'https://media.istockphoto.com/id/1312267010/photo/background-of-many-banana-pieces-overhead-view-studio-food-photography.jpg?s=612x612&w=0&k=20&c=URFsGGi8o_q6o0Ou7A_aEUez5UFSvqWpGww947goOFY=', 150),
(2, 'Carrots', 'Fresh organic carrots', 1.49, 'https://media.istockphoto.com/id/185275579/photo/bundles-of-organic-carrots-with-the-stems-still-attached.jpg?s=612x612&w=0&k=20&c=OIdIDUtDF9jxpCFnZlb7ld5tOj8pDMol1XIcfsHFlEk=', 200),
(2, 'Tomatoes', 'Ripe red tomatoes', 2.49, 'https://media.istockphoto.com/id/140453734/photo/fresh-tomatoes.jpg?s=612x612&w=0&k=20&c=b6XySPuRKF6opBf0bexh9AhkWck-c7TaoJvRdVNBgT0=', 120),
(3, 'Whole Milk', 'Fresh whole milk', 3.99, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQkoveGAv5LGqkFQi6a9D7CKerFEbW7pZ8V6w&s', 80),
(3, 'Cheddar Cheese', 'Sharp cheddar cheese', 4.99, 'https://admin.marketwagon.com/uploads/1658188747sharpcheddardreamstime-s-37654974.png.webp', 60),
(4, 'White Bread', 'Fresh white bread', 2.49, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQNm7jkDMldK-MhwwpGObnYoWqFbJKa4CKCvg&s', 90),
(4, 'Croissants', 'Buttery croissants', 3.99, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQUv95U8ixDFNL8n2zA5Jws-4N1PUrG7bzo_A&s', 70),
(5, 'Chicken Breast', 'Fresh chicken breast', 5.99, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQEZIE-Q-DzVv_whkHFCRsKFv28kTw_eVoATQ&s', 50),
(5, 'Ground Beef', 'Lean ground beef', 6.99, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTfvKdqhDU2vgaK_A_ahoKGX67XtZnMahyWxg&s', 40); 