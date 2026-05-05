-- ============================================================
--  Quiz Pro — Full Database Schema
--  Database: quiz_app
--  Compatible with: MySQL 5.7+ / MariaDB 10.3+
-- ============================================================

CREATE DATABASE IF NOT EXISTS quiz_app
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE quiz_app;

-- ------------------------------------------------------------
-- 1. USERS
--    Stores both regular users and admins (role column).
--    Referenced by: results (user_id)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  username   VARCHAR(80)      NOT NULL,
  email      VARCHAR(191)     NOT NULL,
  password   VARCHAR(255)     NOT NULL,           -- bcrypt hash
  role       ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ------------------------------------------------------------
-- 2. QUESTIONS
--    Each row is one multiple-choice question with 4 options.
--    correct_option stores the winning option number (1–4).
--    Referenced by: results → score is computed from answers
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS questions (
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  question       TEXT         NOT NULL,
  option1        VARCHAR(500) NOT NULL,
  option2        VARCHAR(500) NOT NULL,
  option3        VARCHAR(500) NOT NULL,
  option4        VARCHAR(500) NOT NULL,
  correct_option TINYINT      NOT NULL CHECK (correct_option BETWEEN 1 AND 4),
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ------------------------------------------------------------
-- 3. RESULTS
--    One row per quiz attempt.
--    score = number of correct answers out of total questions.
--    Leaderboard query: MAX(score) per user, ordered DESC.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS results (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id    INT UNSIGNED NOT NULL,
  score      SMALLINT     NOT NULL DEFAULT 0,
  total      SMALLINT     NOT NULL DEFAULT 0,      -- questions answered
  taken_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  KEY idx_results_user   (user_id),
  KEY idx_results_score  (score DESC),

  CONSTRAINT fk_results_user
    FOREIGN KEY (user_id) REFERENCES users (id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  SEED DATA
-- ============================================================

-- ------------------------------------------------------------
-- Default admin account
--   email   : admin@example.com
--   password: Admin@1234   (bcrypt — change after first login)
-- ------------------------------------------------------------
INSERT INTO users (username, email, password, role) VALUES
('Admin', 'admin@example.com',
 '$2y$10$FrStkEjfGuWf2NpWtfcod.qD/uPqkHdQWDlt5B.RVY8wv/p.aJiQi',
 'admin');

-- Demo regular user
--   email   : demo@example.com
--   password: demo123
INSERT INTO users (username, email, password, role) VALUES
('Demo User', 'demo@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'user');

-- ------------------------------------------------------------
-- 20 Sample quiz questions (General Knowledge)
-- ------------------------------------------------------------
INSERT INTO questions (question, option1, option2, option3, option4, correct_option) VALUES

('What is the capital of France?',
 'Berlin', 'Madrid', 'Paris', 'Rome', 3),

('Which planet is known as the Red Planet?',
 'Venus', 'Mars', 'Jupiter', 'Saturn', 2),

('Who wrote the play "Romeo and Juliet"?',
 'Charles Dickens', 'William Shakespeare', 'Mark Twain', 'Jane Austen', 2),

('What is the chemical symbol for water?',
 'O2', 'H2O', 'CO2', 'NaCl', 2),

('Which country hosted the 2016 Summer Olympics?',
 'China', 'United Kingdom', 'Brazil', 'Japan', 3),

('How many continents are on Earth?',
 '5', '6', '7', '8', 3),

('What is the largest ocean on Earth?',
 'Atlantic Ocean', 'Indian Ocean', 'Arctic Ocean', 'Pacific Ocean', 4),

('Which element has the atomic number 1?',
 'Helium', 'Oxygen', 'Hydrogen', 'Carbon', 3),

('Who painted the Mona Lisa?',
 'Vincent van Gogh', 'Pablo Picasso', 'Leonardo da Vinci', 'Michelangelo', 3),

('What is the smallest prime number?',
 '0', '1', '2', '3', 3),

('Which programming language is known as the "language of the web"?',
 'Python', 'Java', 'C++', 'JavaScript', 4),

('What is the speed of light (approx.) in km/s?',
 '150,000', '299,792', '500,000', '1,000,000', 2),

('Which organ pumps blood through the human body?',
 'Liver', 'Kidney', 'Lungs', 'Heart', 4),

('What does "HTML" stand for?',
 'Hyper Text Markup Language', 'High Tech Modern Language',
 'Hyper Transfer Markup Logic', 'Home Tool Markup Language', 1),

('Which country is the largest by land area?',
 'China', 'Canada', 'United States', 'Russia', 4),

('In what year did World War II end?',
 '1943', '1944', '1945', '1946', 3),

('What is the powerhouse of the cell?',
 'Nucleus', 'Ribosome', 'Mitochondria', 'Golgi Apparatus', 3),

('Which planet is closest to the Sun?',
 'Venus', 'Earth', 'Mercury', 'Mars', 3),

('What is 12 × 12?',
 '124', '144', '132', '148', 2),

('Which gas do plants primarily absorb during photosynthesis?',
 'Oxygen', 'Nitrogen', 'Carbon Dioxide', 'Hydrogen', 3);


-- ============================================================
--  QUICK-REFERENCE
--
--  Tables   : users · questions · results
--
--  Key queries used by the app
--  ─────────────────────────────────────────────────────────
--  Login check:
--    SELECT * FROM users WHERE email = ?
--
--  Role check (admin guard):
--    SELECT role FROM users WHERE id = ?
--
--  Fetch quiz questions:
--    SELECT * FROM questions
--
--  Submit score (api/submit.php calls this per question):
--    SELECT correct_option FROM questions WHERE id = ?
--
--  Save result after quiz:
--    INSERT INTO results (user_id, score, total) VALUES (?, ?, ?)
--
--  Leaderboard (top 10):
--    SELECT username, MAX(score) AS s
--    FROM users JOIN results ON users.id = results.user_id
--    GROUP BY user_id
--    ORDER BY s DESC
--    LIMIT 10
--
--  To promote a user to admin:
--    UPDATE users SET role = 'admin' WHERE email = 'someone@example.com';
-- ============================================================
