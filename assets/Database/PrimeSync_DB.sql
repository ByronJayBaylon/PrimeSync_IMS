-- Create accounts table if not exists
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` varchar(50) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert initial data into accounts table
INSERT INTO `accounts` (`id`, `username`, `password`, `account_type`, `date_created`, `created_by`) VALUES
(1, 'admin', '$2y$10$L6BKTF8v7Ok/meG.P6eCi.VoSR4bZsEt/M7PZqy37fsajHOP5m/0a', 'Admin', '2023-12-07 00:38:12', 'Admin'),
(2, 'clerk', '$2y$10$L6BKTF8v7Ok/meG.P6eCi.VoSR4bZsEt/M7PZqy37fsajHOP5m/0a', 'Clerk', '2023-12-06 21:45:50', 'admin'),
(3, 'cashier', '$2y$10$L6BKTF8v7Ok/meG.P6eCi.VoSR4bZsEt/M7PZqy37fsajHOP5m/0a', 'Cashier', '2023-12-06 21:46:00', 'admin'),
(4, 'owner', '$2a$12$J2BnddfQaPLWMvh6pUxwVuZ1X9tlQGdbAZl94RY5i9UAvK/G2fIya', 'Owner', '2023-12-07 01:00:00', 'admin');

-- Create category table if not exists
CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` datetime DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  INDEX `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert initial data into category table
INSERT INTO `category` (`cat_id`, `date_time`, `category`, `creator`) VALUES
(1, '2024-11-14 08:44:20', 'Sinandomeng', 'admin'),
(2, '2024-11-14 08:44:20', 'Dinorado', 'admin'),
(3, '2024-11-14 08:44:20', 'Milagrosa', 'admin'),
(4, '2024-11-14 08:44:20', 'Brown Dinorado', 'admin'),
(5, '2024-11-14 08:44:20', 'Malagkit na Puti', 'admin'),
(6, '2024-11-14 08:44:20', 'Red Rice', 'admin'),
(7, '2024-11-14 08:44:20', 'Doña Maria Jasponica', 'admin'),
(8, '2024-11-14 08:44:20', 'Malagkit na Pula', 'admin'),
(9, '2024-11-14 08:44:20', 'Japanese Rice (Sushi)', 'admin'),
(10, '2024-11-14 08:44:20', 'Basmati Rice', 'admin'),
(11, '2024-11-14 08:44:20', 'Jasmine Rice', 'admin');

-- Create suppliers table if not exists
CREATE TABLE IF NOT EXISTS `suppliers` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(100) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  PRIMARY KEY (`supplier_id`),
  INDEX `idx_supplier_name` (`supplier_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert initial data into suppliers table
INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `contact_number`, `email`, `address`) VALUES
(1, 'Sunnywood Superfoods Corporation', '0917-123-4567', 'info@sunnywood.com', 'Nueva Ecija, Philippines'),
(2, 'Golden Grains Philippines', '0918-234-5678', 'contact@goldengrains.ph', 'Isabela, Philippines'),
(3, 'R.E.J. Commercial', '0921-345-6789', 'rej@gmail.com', 'Isabela, Philippines'),
(4, 'SL Agritech Corporation', '0922-456-7890', 'sales@slagritech.com', 'Nueva Ecija, Philippines'),
(5, 'Daawat Foods Limited', '0933-567-8901', 'support@daawatfoods.com', 'India');

-- Create items table if not exists
CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `ProcessingMethod` varchar(100) DEFAULT NULL,
  `Region` varchar(100) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `StockQuantity` int(11) NOT NULL,
  `supplier_name` varchar(100) DEFAULT NULL,
  `QualityGrade` varchar(100) DEFAULT NULL,
  `MinimumStockLevel` int(11) NOT NULL,
  `item_price` decimal(10,2) DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  FOREIGN KEY (`category`) REFERENCES `category` (`category`) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (`supplier_name`) REFERENCES `suppliers` (`supplier_name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert initial data into items table
INSERT INTO `items` (`item_id`, `item_name`, `ProcessingMethod`, `Region`, `date_time`, `StockQuantity`, `supplier_name`, `QualityGrade`, `MinimumStockLevel`, `item_price`, `creator`, `category`) VALUES
(1, "Harvester's Sinandomeng", 'Milled', 'Nueva Ecija', '2024-11-07 08:44:45', 500, 'Sunnywood Superfoods Corporation', 'Premium', 50, 50.00, 'admin', 'Sinandomeng'),
(2, "Golden Grains Sinandomeng Special", 'Milled', 'Isabela', '2024-11-07 08:44:45', 300, 'Golden Grains Philippines', 'Specialty', 30, 55.00, 'admin', 'Sinandomeng'),
(3, "Harvester's Dinorado", 'Semi-Milled', 'Nueva Ecija', '2024-11-07 08:44:45', 400, 'Sunnywood Superfoods Corporation', 'Premium', 50, 60.00, 'admin', 'Dinorado'),
(4, "Golden Grains Dinurado", 'Semi-Milled', 'Mindoro', '2024-11-07 08:44:45', 350, 'Golden Grains Philippines', 'Premium', 30, 65.00, 'admin', 'Dinorado'),
(5, "Jordan Farms Milagrosa", 'Milled', 'Iloilo', '2024-11-07 08:44:45', 250, 'Sunnywood Superfoods Corporation', 'Specialty', 25, 70.00, 'admin', 'Milagrosa'),
(6, "Angelica Milagrosa", 'Milled', 'Isabela', '2024-11-07 08:44:45', 200, 'R.E.J. Commercial', 'Premium', 20, 65.00, 'admin', 'Milagrosa'),
(7, "Jordan Farms Organic Brown Dinorado", 'Unpolished', 'Nueva Ecija', '2024-11-07 08:44:45', 150, 'Sunnywood Superfoods Corporation', 'Organic', 20, 75.00, 'admin', 'Brown Dinorado'),
(8, "Golden Grains Organic Brown", 'Unpolished', 'Mindoro', '2024-11-07 08:44:45', 200, 'Golden Grains Philippines', 'Organic', 25, 80.00, 'admin', 'Brown Dinorado'),
(9, "Farm Boy White Glutinous", 'Milled', 'Nueva Ecija', '2024-11-07 08:44:45', 300, 'Sunnywood Superfoods Corporation', 'Specialty', 30, 60.00, 'admin', 'Malagkit na Puti'),
(10, "Golden Grains Malagkit", 'Milled', 'Isabela', '2024-11-07 08:44:45', 250, 'Golden Grains Philippines', 'Specialty', 25, 65.00, 'admin', 'Malagkit na Puti'),
(11, "Jordan Farms Organic Red", 'Unpolished', 'Nueva Ecija', '2024-11-07 08:44:45', 150, 'Sunnywood Superfoods Corporation', 'Organic', 20, 80.00, 'admin', 'Red Rice'),
(12, "Golden Grains Organic Red", 'Unpolished', 'Mindoro', '2024-11-07 08:44:45', 200, 'Golden Grains Philippines', 'Organic', 25, 85.00, 'admin', 'Red Rice'),
(13, "Doña Maria Jasponica", 'Milled', 'Nueva Ecija', '2024-11-07 08:44:45', 500, 'SL Agritech Corporation', 'Premium', 50, 70.00, 'admin', 'Doña Maria Jasponica'),
(14, "Doña Maria Jasponica Plus", 'Milled', 'Nueva Ecija', '2024-11-07 08:44:45', 400, 'SL Agritech Corporation', 'Specialty', 50, 75.00, 'admin', 'Doña Maria Jasponica'),
(15, "Harvester's Red Glutinous", 'Milled', 'Nueva Ecija', '2024-11-07 08:44:45', 200, 'Sunnywood Superfoods Corporation', 'Premium', 20, 75.00, 'admin', 'Malagkit na Pula'),
(16, "Golden Grains Red Malagkit", 'Milled', 'Mindoro', '2024-11-07 08:44:45', 180, 'Golden Grains Philippines', 'Specialty', 20, 80.00, 'admin', 'Malagkit na Pula'),
(17, "Harvester's Kanto Japanese", 'Milled', 'Nueva Ecija', '2024-11-07 08:44:45', 300, 'Sunnywood Superfoods Corporation', 'Specialty', 30, 90.00, 'admin', 'Japanese Rice (Sushi)'),
(18, "Golden Grains Kanto Sushi", 'Milled', 'Isabela', '2024-11-07 08:44:45', 250, 'Golden Grains Philippines', 'Specialty', 30, 95.00, 'admin', 'Japanese Rice (Sushi)'),
(19, "Jordan Farms Basmati", 'Semi-Milled', 'Imported (India)', '2024-11-07 08:44:45', 200, 'Sunnywood Superfoods Corporation', 'Specialty', 20, 120.00, 'admin', 'Basmati Rice'),
(20, "Daawat Basmati", 'Semi-Milled', 'Imported (India)', '2024-11-07 08:44:45', 150, 'Daawat Foods Limited', 'Specialty', 15, 125.00, 'admin', 'Basmati Rice'),
(21, "Harvester's Thai Jasmine", 'Milled', 'Imported (Thailand)', '2024-11-07 08:44:45', 300, 'Sunnywood Superfoods Corporation', 'Premium', 30, 110.00, 'admin', 'Jasmine Rice'),
(22, "Golden Grains Thai Jasmine", 'Milled', 'Imported (Thailand)', '2024-11-07 08:44:45', 250, 'Golden Grains Philippines', 'Premium', 30, 115.00, 'admin', 'Jasmine Rice');

-- Create sales table if not exists
CREATE TABLE IF NOT EXISTS `sales` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `item_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `sub_total` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `week` int(11) DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert initial data into sales table
INSERT INTO `sales` (`sale_id`, `item_id`, `item_name`, `item_price`, `date`, `quantity`, `sub_total`, `month`, `year`, `week`) VALUES
(1, 1, "Harvester's Sinandomeng", 50.00, '2024-01-05', 50, 2500.00, 1, 2024, 1),
(2, 1, "Harvester's Sinandomeng", 50.00, '2024-02-10', 100, 5000.00, 2, 2024, 6),
(3, 1, "Golden Grains Sinandomeng Special", 55.00, '2024-03-15', 200, 11000.00, 3, 2024, 11),
(4, 2, "Golden Grains Dinurado", 65.00, '2024-01-12', 30, 1950.00, 1, 2024, 2),
(5, 2, "Golden Grains Dinurado", 65.00, '2024-02-18', 60, 3900.00, 2, 2024, 7),
(6, 3, "Jordan Farms Milagrosa", 70.00, '2024-03-25', 90, 6300.00, 3, 2024, 13),
(7, 5, "Golden Grains Malagkit", 65.00, '2024-06-20', 70, 4550.00, 6, 2024, 25);


-- Set indexes and auto increment values for tables
ALTER TABLE `sales`
ADD KEY `item_id` (`item_id`);

ALTER TABLE `accounts`
 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `category`
 MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `items`
 MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

ALTER TABLE `sales`
 MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `suppliers`
 MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

-- -- Modify the 'items' table; change 'RiceName' column to 'item_name'
-- ALTER TABLE `items`
-- CHANGE `RiceName` `item_name` VARCHAR(100) NOT NULL;

-- Modify the items table
ALTER TABLE `items`
ADD INDEX `idx_item_name` (`item_name`),
ADD INDEX `idx_item_price` (`item_price`);

-- Adding foreign key constraints
ALTER TABLE `sales`
ADD CONSTRAINT `sales_ibfk_item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Adding foreign key constraint for item_name
ALTER TABLE `sales`
ADD CONSTRAINT `sales_fk_item_name` FOREIGN KEY (`item_name`) REFERENCES `items` (`item_name`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Adding foreign key constraint for item_price
ALTER TABLE `sales`
ADD CONSTRAINT `sales_fk_item_price` FOREIGN KEY (`item_price`) REFERENCES `items` (`item_price`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Update supplier_name column in items table
UPDATE `items` AS i
JOIN `suppliers` AS s ON i.supplier_name = s.supplier_name
SET i.supplier_name = s.supplier_name;

-- Populate sales table with updated month, year, and week values
UPDATE `sales` SET 
  `month` = MONTH(`date`),
  `year` = YEAR(`date`),
  `week` = WEEK(`date`);
