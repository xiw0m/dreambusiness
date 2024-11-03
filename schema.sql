CREATE SCHEMA pet_grooming_salon;

USE SCHEMA pet_grooming_salon;

CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    pet_name VARCHAR(50) NOT NULL,
    pet_birthday DATE,
    phone_number VARCHAR(20)
);

CREATE TABLE grooming_sessions (
    session_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    date_of_grooming DATE,
    total_price DECIMAL(10,2),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);