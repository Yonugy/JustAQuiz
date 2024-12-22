CREATE DATABASE justaquiz;

USE justaquiz;

CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT, -- 1 admin 2 instructor 3 student
    name VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    date_joined DATETIME DEFAULT CURRENT_TIMESTAMP,
    profile_image LONGBLOB,
    image_type varchar(100)
);

CREATE TABLE Badges (
    badge_id INT AUTO_INCREMENT PRIMARY KEY,
    creator_id INT,
    badge_image LONGBLOB,
    image_type  VARCHAR(50),
    achievement_name VARCHAR(100) NOT NULL,
    category VARCHAR(50), -- HTML or CSS
    criteria VARCHAR(100),
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creator_id) REFERENCES Users(user_id)
);

CREATE TABLE Collected_Badges (
    collected_badge_id INT AUTO_INCREMENT PRIMARY KEY,
    badge_id INT,
    student_id INT,
    date_collected DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (badge_id, student_id), -- 1 student only can earn same badge once
    FOREIGN KEY (badge_id) REFERENCES Badges(badge_id),
    FOREIGN KEY (student_id) REFERENCES Users(user_id)
);

CREATE TABLE Quiz (
    quiz_id INT AUTO_INCREMENT PRIMARY KEY,
    creator_id INT,
    title VARCHAR(100),
    description VARCHAR(100),
    subject VARCHAR(100),
    time_limit INT CHECK (time_limit > 0),
    date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creator_id) REFERENCES Users(user_id)
);

CREATE TABLE Question (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    question_text VARCHAR(255) NOT NULL,
    score INT DEFAULT 1,
    FOREIGN KEY (quiz_id) REFERENCES Quiz(quiz_id) ON DELETE CASCADE
);

CREATE TABLE Choices (
    choice_id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT,
    text VARCHAR(255),
    is_correct tinyint(1),
    FOREIGN KEY (question_id) REFERENCES Question(question_id) ON DELETE CASCADE
);

CREATE TABLE Attempt (
    attempt_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    quiz_id INT,
    stat VARCHAR(50),
    FOREIGN KEY (student_id) REFERENCES Users(user_id),
    FOREIGN KEY (quiz_id) REFERENCES Quiz(quiz_id)
);

CREATE TABLE Result (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT,
    datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
    time_remaining INT,
    feedback TEXT,
    FOREIGN KEY (attempt_id) REFERENCES Attempt(attempt_id)
);

CREATE TABLE Student_Answer (
    student_answer_id INT AUTO_INCREMENT PRIMARY KEY,
    choice_id INT,
    attempt_id INT,
    FOREIGN KEY (choice_id) REFERENCES Choices(choice_id),
    FOREIGN KEY (attempt_id) REFERENCES Attempt(attempt_id)
);


