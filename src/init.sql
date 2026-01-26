CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    mail VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    study_field VARCHAR(255),
    class_year INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS lecturers (
    lecturer_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    lecturer_id INT NOT NULL,  -- FK HERE,
    pin_code CHAR(4),
    FOREIGN KEY (lecturer_id) REFERENCES lecturers(lecturer_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS students_have_courses (
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    PRIMARY KEY (student_id, course_id),
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,  -- fk
    course_id INT NOT NULL,  --fk
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    text VARCHAR(255) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
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
