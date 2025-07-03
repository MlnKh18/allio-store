CREATE TABLE `Roles` (
  `role_id` INT PRIMARY KEY AUTO_INCREMENT,
  `role_user` VARCHAR(200)
);

CREATE TABLE `Users` (
  `user_id` INT PRIMARY KEY AUTO_INCREMENT,
  `name_user` VARCHAR(100),
  `email` VARCHAR(100) UNIQUE,
  `password` VARCHAR(255),
  `role_id` INT,
  `created_at` DATETIME
);

CREATE TABLE `Categories` (
  `category_id` INT PRIMARY KEY AUTO_INCREMENT,
  `name_category` VARCHAR(100)
);

CREATE TABLE `Products` (
  `product_id` INT PRIMARY KEY AUTO_INCREMENT,
  `name_product` VARCHAR(150),
  `price` DECIMAL(12,2),
  `description` TEXT,
  `image_url` VARCHAR(255),
  `category_id` INT,
  `created_at` DATETIME
);

CREATE TABLE `Cart_Items` (
  `cart_item_id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `product_id` INT,
  `quantity` INT
);

CREATE TABLE `Orders` (
  `order_id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `total_amount` DECIMAL(12,2),
  `status` ENUM('pending','paid','shipped','completed','cancelled'),
  `created_at` DATETIME
);

CREATE TABLE `Order_Items` (
  `order_item_id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT,
  `product_id` INT,
  `quantity` INT,
  `price` DECIMAL(12,2)
);

CREATE TABLE `Payments` (
  `payment_id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT,
  `payment_method` VARCHAR(50),
  `payment_status` ENUM('pending','confirmed','failed'),
  `paid_at` DATETIME
);

-- Relasi antar tabel
ALTER TABLE `Users` ADD FOREIGN KEY (`role_id`) REFERENCES `Roles` (`role_id`);

ALTER TABLE `Products` ADD FOREIGN KEY (`category_id`) REFERENCES `Categories` (`category_id`);

ALTER TABLE `Cart_Items` ADD FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

ALTER TABLE `Cart_Items` ADD FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`);

ALTER TABLE `Orders` ADD FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

ALTER TABLE `Order_Items` ADD FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`);

ALTER TABLE `Order_Items` ADD FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`);

ALTER TABLE `Payments` ADD FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`);
