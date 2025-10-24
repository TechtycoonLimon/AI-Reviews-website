-- --------------------------------------------------------
-- Database: ai_reviews
-- --------------------------------------------------------

DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS users;

-- --------------------------------------------------------
-- USERS TABLE
-- --------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- REVIEWS TABLE
-- --------------------------------------------------------
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tool_name VARCHAR(255) NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 10),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- SAMPLE DATA (optional)
-- --------------------------------------------------------
INSERT INTO users (name, email, password_hash)
VALUES ('Test User', 'test@example.com', '$2y$10$7nqOqzCQqY4MGeKk7U1DeeIZCHKnoy0oLbtVbKpK8Upb2bF2k2zLu'); -- password: test1234

INSERT INTO reviews (user_id, tool_name, rating, comment)
VALUES (1, 'ChatGPT', 10, 'Amazing AI tool!');
