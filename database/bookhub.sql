-- UIU BookHUB fresh database setup.
-- Importing this file drops and recreates the bookhub database.
DROP DATABASE IF EXISTS bookhub;
CREATE DATABASE bookhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bookhub;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    student_id VARCHAR(30) NULL UNIQUE,
    email VARCHAR(160) NULL UNIQUE,
    department VARCHAR(80) NULL,
    phone VARCHAR(30) NULL,
    password_hash VARCHAR(255) NULL,
    role ENUM('student', 'admin') NOT NULL DEFAULT 'student',
    status ENUM('active', 'blocked') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE listings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seller_id INT UNSIGNED NOT NULL,
    title VARCHAR(180) NOT NULL,
    course_code VARCHAR(30) NOT NULL,
    department VARCHAR(80) NOT NULL,
    subject VARCHAR(120) NULL,
    item_type ENUM('Textbook', 'Lecture Notes', 'Lab Manual', 'Other Notes') NOT NULL DEFAULT 'Textbook',
    condition_status ENUM('Like New', 'Good', 'Used') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    edition_author VARCHAR(180) NULL,
    description TEXT NULL,
    image_url VARCHAR(500) NULL,
    status ENUM('pending', 'approved', 'rejected', 'sold') NOT NULL DEFAULT 'pending',
    reject_reason VARCHAR(255) NULL,
    reviewed_by INT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_listings_seller FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_listings_reviewer FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_listings_status_created (status, created_at),
    INDEX idx_listings_course (course_code),
    INDEX idx_listings_department (department)
) ENGINE=InnoDB;

CREATE TABLE donations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    donor_identity VARCHAR(120) NULL,
    title VARCHAR(180) NOT NULL,
    course_code VARCHAR(30) NOT NULL,
    department VARCHAR(80) NOT NULL,
    subject VARCHAR(120) NULL,
    item_type ENUM('Textbook', 'Lecture Notes', 'Lab Manual', 'Other Notes') NOT NULL DEFAULT 'Textbook',
    condition_status ENUM('Like New', 'Good', 'Used') NOT NULL,
    edition_author VARCHAR(180) NULL,
    description TEXT NULL,
    image_url VARCHAR(500) NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    reject_reason VARCHAR(255) NULL,
    reviewed_by INT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_donations_reviewer FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_donations_status_created (status, created_at),
    INDEX idx_donations_course (course_code),
    INDEX idx_donations_department (department)
) ENGINE=InnoDB;

CREATE TABLE wishlists (
    user_id INT UNSIGNED NOT NULL,
    listing_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, listing_id),
    CONSTRAINT fk_wishlists_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_wishlists_listing FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    INDEX idx_wishlists_listing (listing_id)
) ENGINE=InnoDB;

CREATE TABLE purchase_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    listing_id INT UNSIGNED NOT NULL,
    buyer_id INT UNSIGNED NOT NULL,
    status ENUM('pending', 'accepted', 'rejected', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_purchase_requests_listing FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    CONSTRAINT fk_purchase_requests_buyer FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_purchase_requests_buyer (buyer_id, created_at),
    INDEX idx_purchase_requests_listing_status (listing_id, status)
) ENGINE=InnoDB;

CREATE TABLE conversations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    listing_id INT UNSIGNED NOT NULL,
    buyer_id INT UNSIGNED NOT NULL,
    seller_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_conversation_listing_buyer (listing_id, buyer_id),
    CONSTRAINT fk_conversations_listing FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    CONSTRAINT fk_conversations_buyer FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_conversations_seller FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_conversations_seller (seller_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT UNSIGNED NOT NULL,
    sender_id INT UNSIGNED NOT NULL,
    body VARCHAR(2000) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_messages_conversation FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
    CONSTRAINT fk_messages_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_messages_conversation_created (conversation_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_request_id INT UNSIGNED NOT NULL UNIQUE,
    listing_id INT UNSIGNED NOT NULL,
    reviewer_id INT UNSIGNED NOT NULL,
    seller_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    comment VARCHAR(1000) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_request FOREIGN KEY (purchase_request_id) REFERENCES purchase_requests(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_listing FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_reviewer FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_seller FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5),
    INDEX idx_reviews_seller_created (seller_id, created_at)
) ENGINE=InnoDB;

-- Demo admin login: Student ID = ADMIN, password = admin123. Change before deployment.
INSERT INTO users (full_name, student_id, role, password_hash)
VALUES ('Admin', 'ADMIN', 'admin', '$2y$10$ybcDr3hwTswiRaWbB5bwH.xSnk6GSwq3PsMwidMp/Tu1K/RuPuklO');

INSERT INTO users (full_name, student_id, email, department)
VALUES ('Demo Student', 'DEMO-001', 'demo@bookhub.local', 'CSE');

INSERT INTO listings
    (seller_id, title, course_code, department, subject, item_type, condition_status, price, edition_author, description, image_url, status)
VALUES
    (2, 'Database System Concepts', 'CSE 311', 'CSE', 'Database Management', 'Textbook', 'Good', 650, 'Abraham Silberschatz', 'Used textbook in good condition.', 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=700', 'approved'),
    (2, 'Computer Networks', 'CSE 421', 'CSE', 'Computer Networks', 'Textbook', 'Like New', 900, NULL, 'Almost unused copy.', 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=700', 'approved'),
    (2, 'Engineering Mathematics', 'MAT 101', 'GED', 'Mathematics', 'Other Notes', 'Good', 400, NULL, 'Helpful class notes.', 'https://images.unsplash.com/photo-1593340010859-83edd3d6d13f?auto=format&fit=crop&q=80&w=700', 'approved'),
    (2, 'Data Structures & Algorithms', 'CSE 221', 'CSE', 'Data Structures', 'Textbook', 'Good', 700, NULL, 'Clean pages with minor markings.', 'https://images.unsplash.com/photo-1531058020387-3be344556be6?auto=format&fit=crop&q=80&w=700', 'approved');












    -- Run once on an existing BookHUB database to enable wishlist, purchase requests, chat, and reviews.
CREATE TABLE IF NOT EXISTS wishlists (
    user_id INT UNSIGNED NOT NULL,
    listing_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, listing_id),
    CONSTRAINT fk_wishlists_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_wishlists_listing FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    INDEX idx_wishlists_listing (listing_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS purchase_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    listing_id INT UNSIGNED NOT NULL,
    buyer_id INT UNSIGNED NOT NULL,
    status ENUM('pending', 'accepted', 'rejected', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_purchase_requests_listing FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    CONSTRAINT fk_purchase_requests_buyer FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_purchase_requests_buyer (buyer_id, created_at),
    INDEX idx_purchase_requests_listing_status (listing_id, status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS conversations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    listing_id INT UNSIGNED NOT NULL,
    buyer_id INT UNSIGNED NOT NULL,
    seller_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_conversation_listing_buyer (listing_id, buyer_id),
    CONSTRAINT fk_conversations_listing FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    CONSTRAINT fk_conversations_buyer FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_conversations_seller FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_conversations_seller (seller_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT UNSIGNED NOT NULL,
    sender_id INT UNSIGNED NOT NULL,
    body VARCHAR(2000) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_messages_conversation FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
    CONSTRAINT fk_messages_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_messages_conversation_created (conversation_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_request_id INT UNSIGNED NOT NULL UNIQUE,
    listing_id INT UNSIGNED NOT NULL,
    reviewer_id INT UNSIGNED NOT NULL,
    seller_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    comment VARCHAR(1000) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_request FOREIGN KEY (purchase_request_id) REFERENCES purchase_requests(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_listing FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_reviewer FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_seller FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5),
    INDEX idx_reviews_seller_created (seller_id, created_at)
) ENGINE=InnoDB;








-- Add free book donations submitted without signing in.
CREATE TABLE IF NOT EXISTS donations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    donor_identity VARCHAR(120) NULL,
    title VARCHAR(180) NOT NULL,
    course_code VARCHAR(30) NOT NULL,
    department VARCHAR(80) NOT NULL,
    subject VARCHAR(120) NULL,
    item_type ENUM('Textbook', 'Lecture Notes', 'Lab Manual', 'Other Notes') NOT NULL DEFAULT 'Textbook',
    condition_status ENUM('Like New', 'Good', 'Used') NOT NULL,
    edition_author VARCHAR(180) NULL,
    description TEXT NULL,
    image_url VARCHAR(500) NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    reject_reason VARCHAR(255) NULL,
    reviewed_by INT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_donations_reviewer FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_donations_status_created (status, created_at),
    INDEX idx_donations_course (course_code),
    INDEX idx_donations_department (department)
) ENGINE=InnoDB;
