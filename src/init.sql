CREATE TABLE IF NOT EXISTS users (
    user_id VARCHAR(36)  PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    mail VARCHAR(100) NOT NULL UNIQUE,
    role ENUM("student", "lecturer") NOT NULL,
    PASSWORD VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS students (
    student_id VARCHAR(36)  PRIMARY KEY,
    study_field VARCHAR(255) NOT NULL,
    class_year YEAR NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS lecturers (
    lecturer_id VARCHAR(36)  PRIMARY KEY,
    avatar VARCHAR(255),
    security_question VARCHAR(255) NOT NULL,
    security_answer VARCHAR(255) NOT NULL,
    FOREIGN KEY (lecturer_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    lecturer_id VARCHAR(36) NOT NULL,
    course_code VARCHAR(14) NOT NULL UNIQUE,
    course_name VARCHAR(50) NOT NULL,
    pin_code CHAR(4),
    FOREIGN KEY (lecturer_id) REFERENCES lecturers(lecturer_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS students_courses (
    student_id VARCHAR(36) NOT NULL,
    course_id INT NOT NULL,
    PRIMARY KEY (student_id, course_id),
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(36) NOT NULL,
    course_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    text VARCHAR(255) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS replies (
    reply_id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    text VARCHAR(255) NOT NULL,
    FOREIGN KEY (message_id) REFERENCES messages(message_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    text VARCHAR(255) NOT NULL,
    FOREIGN KEY (message_id) REFERENCES messages(message_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    text VARCHAR (255) NOT NULL,
    FOREIGN KEY (message_id) REFERENCES messages(message_id) ON DELETE CASCADE
);
