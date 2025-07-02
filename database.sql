-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services Table
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    duration INT NOT NULL -- Duration in minutes
);

-- Vehicles Table
CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    make VARCHAR(100),
    model VARCHAR(100),
    year INT,
    license_plate VARCHAR(50) UNIQUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL -- Keep vehicle even if user is deleted, or make it CASCADE
);

-- Bookings Table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL, -- Nullable for guest bookings
    guest_name VARCHAR(255) NULL,
    guest_email VARCHAR(255) NULL,
    guest_phone VARCHAR(50) NULL,
    service_id INT NOT NULL,
    vehicle_id INT NULL, -- Nullable if booking without a pre-registered vehicle or for guests
    vehicle_make VARCHAR(100) NULL,
    vehicle_model VARCHAR(100) NULL,
    vehicle_year INT NULL,
    vehicle_license_plate VARCHAR(50) NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled', 'no-show') DEFAULT 'pending',
    notes TEXT NULL, -- Any additional notes from the user or admin
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL, -- Or CASCADE if bookings should be deleted with user
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT, -- Prevent deleting service if bookings exist
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL -- Or CASCADE
);

-- Pre-populate some services for testing
INSERT INTO services (name, description, price, duration) VALUES
('Comprehensive Vehicle Inspection', 'A thorough inspection covering all major components, including engine, brakes, suspension, and electrical systems. Receive a detailed report with recommendations.', 199.00, 120),
('Pre-Purchase Inspection', 'Ensure the vehicle you''re buying is in top shape. We''ll assess its condition and provide a detailed report to help you make an informed decision.', 249.00, 150),
('Oil Change and Filter Replacement', 'Keep your engine running smoothly with our professional oil change service. Includes high-quality oil and filter replacement.', 99.00, 60),
('Brake Service', 'Ensure your safety with our comprehensive brake service. Includes inspection, pad replacement, and rotor resurfacing.', 299.00, 180),
('Tire Rotation and Balancing', 'Extend the life of your tires and improve handling with our tire rotation and balancing service.', 79.00, 90),
('Wheel Alignment', 'Improve your vehicle''s handling and fuel efficiency with our precise wheel alignment service.', 129.00, 90),
('Battery Replacement', 'Ensure reliable starting power with our battery replacement service. Includes installation and testing.', 149.00, 60),
('Air Conditioning Service', 'Stay cool and comfortable with our air conditioning service. Includes inspection, refrigerant recharge, and leak detection.', 179.00, 120);

-- Create a default admin user (password: admin123)
-- In a real application, ensure this password is changed or created through a secure setup process.
INSERT INTO users (name, email, password, role) VALUES
('Admin User', 'admin@example.com', '$2y$10$I0S.3V3G.E5Y1g2X0Z8S0uW0i6J.X5R.N8Y.U9B.P7b.K2r.L4v.S', 'admin');
-- The password 'admin123' hashed with password_hash('admin123', PASSWORD_DEFAULT)
-- Note: The hash will be different each time password_hash is called if you regenerate it.
-- This one is just an example. You'll generate this hash in your PHP registration script.

-- Example user (password: user123)
INSERT INTO users (name, email, password, role) VALUES
('Test User', 'user@example.com', '$2y$10$gL2N01nE.o./Jc.jL8XzUe.Y0f9W.Z8m.T7u.V5P.R2q.E1D.A9gG', 'user');
-- The password 'user123' hashed with password_hash('user123', PASSWORD_DEFAULT)
