-- ======================================
-- PC STATUS SYSTEM DATABASE (CLEAN VERSION)
-- ======================================

CREATE DATABASE IF NOT EXISTS pc_status;
USE pc_status;

-- =====================
-- USERS TABLE
-- =====================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin','admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================
-- TICKETS TABLE
-- =====================
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    serialNumber VARCHAR(100),
    tagNumber VARCHAR(100),
    pcModel VARCHAR(100),
    hardwareType VARCHAR(100),
    branch VARCHAR(100),
    status ENUM('Open','In Progress','Resolved','Closed','Overdue') DEFAULT 'Open',
    priority ENUM('Low','Medium','High','Critical') DEFAULT 'Medium',
    issue TEXT,
    resolution TEXT,
    assigned_to INT NULL,
    created_by INT NOT NULL,
    due_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- =====================
-- ATTACHMENTS TABLE
-- =====================
CREATE TABLE attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    file_name VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE
);

-- =====================
-- AUDIT LOGS TABLE
-- =====================
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255),
    ticket_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =====================
-- SUPER ADMIN USER
-- =====================
INSERT INTO users(full_name,email,password,role)
VALUES (
'Super Admin',
'admin@pcstatus.com',
'$2y$10$u0v6kqz1Vbq5bZ7xw3pQ6eQm8kJ0g8vH8c5cZQq6mQ8d8sG2xA9xC',
'super_admin'
);