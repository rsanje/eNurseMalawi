-- Create the database
CREATE DATABASE IF NOT EXISTS nmcm;

-- Switch to the newly created database
USE nmcm;

-- Create the users table
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) UNIQUE,
    password VARCHAR(64) NOT NULL,
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    other_names VARCHAR(30),
    national_id VARCHAR(15) UNIQUE,
    birth_date DATE NOT NULL,
    place_of_birth VARCHAR(30) NOT NULL,
    nationality VARCHAR(50) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    email VARCHAR(40),
    phone VARCHAR(15) NOT NULL,
    address VARCHAR(130),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Nurse Table
CREATE TABLE IF NOT EXISTS nurse (
    nurse_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    reg_no VARCHAR(30) UNIQUE,
    speciality VARCHAR(50) NOT NULL,
    reg_status VARCHAR(20) NOT NULL
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Employment Table
CREATE TABLE IF NOT EXISTS employment (
    emp_no INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    emp_name VARCHAR(255) NOT NULL;
    start_date DATE NOT NULL,
    end_date DATE,
    position VARCHAR(30) NOT NULL,
    emp_address VARCHAR(60) NOT NULL,
    location VARCHAR(60) NOT NULL,
    emp_phone VARCHAR(15) NOT NULL,
    emp_email VARCHAR(50),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE employement
ADD COLUMN emp_name VARCHAR(255) NOT NULL;

-- License Table
CREATE TABLE IF NOT EXISTS license (
    license_no INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    staff_id INT,
    FOREIGN KEY (staff_id) REFERENCES users(user_id),
    authorised_by INT,
    FOREIGN KEY (authorised_by) REFERENCES users(user_id),
    application_date DATE NOT NULL,
    issue_date DATE,
    status VARCHAR(30) NOT NULL,
    expire_date DATE,
    amount VARCHAR(20) NOT NULL,
    receipt_no VARCHAR(30),
    mode_of_payment VARCHAR(30)
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Staff Table
CREATE TABLE IF NOT EXISTS staff (
    emp_code VARCHAR(20) PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    department VARCHAR(50) NOT NULL,
    position VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    email VARCHAR(50),
    phone VARCHAR(15),
    role VARCHAR(20) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Qualification Table
CREATE TABLE IF NOT EXISTS qualification (
    qualification_no INT AUTO_INCREMENT PRIMARY KEY,,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    institution VARCHAR(50) NOT NULL,
    certificate VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    credits INT,
    program VARCHAR(200) NOT NULL,
    description VARCHAR(500) NOT NULL,
    modules VARCHAR(500) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Document Table
CREATE TABLE IF NOT EXISTS document (
    doc_no INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    belongs_to INT,
    description VARCHAR(50) NOT NULL,
    document BLOB NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
