<?php
// Example SQL query: Modify this query for the specific migration.
return "
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    title VARCHAR(255),
    parent_id INT DEFAULT NULL,
    photo_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES employees(id)
) ENGINE=INNODB;
";