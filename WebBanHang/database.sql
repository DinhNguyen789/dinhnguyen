CREATE DATABASE IF NOT EXISTS my_store;
USE my_store;


CREATE TABLE IF NOT EXISTS category (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 description TEXT
);



CREATE TABLE IF NOT EXISTS product (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 description TEXT,
 price DECIMAL(10,2) NOT NULL,
 image VARCHAR(255) DEFAULT NULL,
 category_id INT,
 FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS orders (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(255) NOT NULL,
 phone VARCHAR(20) NOT NULL,
 address TEXT NOT NULL,
 total_price DECIMAL(10,2) NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS order_details (
 id INT AUTO_INCREMENT PRIMARY KEY,
 order_id INT NOT NULL,
 product_id INT NOT NULL,
 quantity INT NOT NULL,
 price DECIMAL(10,2) NOT NULL,
 FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
 FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(255) NOT NULL UNIQUE,
 fullname VARCHAR(255) NOT NULL,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin','user') DEFAULT 'user'
);

SELECT p.*, c.name AS category_name 
FROM product p 
LEFT JOIN category c ON p.category_id = c.id;

-- Thêm dữ liệu mẫu vào bảng category


-- Thêm dữ liệu mẫu vào bảng product


-- Kiểm tra danh sách sản phẩm	



-- Tài khoản user
INSERT INTO users (username, fullname, password, role) 
VALUES ('user1', 'Nguyen Van A', '$2y$10$WAKe0Xz5z5z5z5z5z5z5z5uWAKe0Xz5z5z5z5z5z5z5z5z5z5z5z5z5', 'user');

-- Tài khoản admin
INSERT INTO users (username, fullname, password, role) 
VALUES ('admin1', 'Admin User', '$2y$10$WAKe0Xz5z5z5z5z5z5z5z5uWAKe0Xz5z5z5z5z5z5z5z5z5z5z5z5z5', 'admin');