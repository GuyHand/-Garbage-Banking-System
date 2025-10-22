-- schema.sql สำหรับโปรเจค 'ธนาคารขยะ'
CREATE DATABASE IF NOT EXISTS garbage_bank CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE garbage_bank;

CREATE TABLE members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  email VARCHAR(200),
  phone VARCHAR(50),
  points DECIMAL(10,2) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type_name VARCHAR(200) NOT NULL,
  point_per_kg DECIMAL(10,4) NOT NULL DEFAULT 1.0,
  image VARCHAR(300),
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE rewards (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  points_required DECIMAL(10,2) NOT NULL,
  image VARCHAR(300),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT NOT NULL,
  rate_id INT NOT NULL,
  weight DECIMAL(10,3) NOT NULL,
  points_earned DECIMAL(10,3) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
  FOREIGN KEY (rate_id) REFERENCES rates(id) ON DELETE CASCADE
);

-- Seed some sample rates
INSERT INTO rates (type_name, point_per_kg, image) VALUES
('พลาสติก (Plastic)', 1.5, 'assets/uploaded_images/plastic.png'),
('กระดาษ (Paper)', 1.0, 'assets/uploaded_images/paper.png'),
('โลหะ (Metal)', 2.0, 'assets/uploaded_images/metal.png');
