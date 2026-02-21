CREATE DATABASE IF NOT EXISTS steg2_datasikkerhet;

use steg2_datasikkerhet;

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

INSERT IGNORE INTO users (user_id, first_name, last_name, mail, role, password) VALUES
('11111111-1111-1111-1111-111111111111', 'Ola', 'Nordmann', 'ola@student.no', 'student', '$2y$10$examplehash1'),
('22222222-2222-2222-2222-222222222222', 'Kari', 'Hansen', 'kari@student.no', 'student', '$2y$10$examplehash2'),
('33333333-3333-3333-3333-333333333333', 'Per', 'Larsen', 'per@foreleser.no', 'lecturer', '$2y$10$examplehash3'),
('44444444-4444-4444-4444-444444444444', 'Anne', 'Berg', 'anne@foreleser.no', 'lecturer', '$2y$10$examplehash4');

INSERT IGNORE INTO students (student_id, study_field, class_year) VALUES
('11111111-1111-1111-1111-111111111111', 'Informatikk', 2023),
('22222222-2222-2222-2222-222222222222', 'Datasikkerhet', 2022);

INSERT IGNORE INTO lecturers (lecturer_id, avatar, security_question, security_answer) VALUES
('33333333-3333-3333-3333-333333333333', 'profile_avatars/per_avatar.png', 'Hva er din favorittfarge?', 'Blå'),
('44444444-4444-4444-4444-444444444444', 'profile_avatars/anne_avatar.png', 'Hva heter ditt første kjæledyr?', 'Milo');

INSERT IGNORE INTO courses (lecturer_id, course_code, course_name, pin_code) VALUES
('33333333-3333-3333-3333-333333333333', 'DAT100', 'Introduksjon til programmering', '1234'),
('33333333-3333-3333-3333-333333333333', 'DAT200', 'Datastrukturer', '5678'),
('44444444-4444-4444-4444-444444444444', 'SEC300', 'Etisk hacking', '4321');

INSERT IGNORE INTO students_courses (student_id, course_id) VALUES
('11111111-1111-1111-1111-111111111111', 1),
('11111111-1111-1111-1111-111111111111', 2),
('22222222-2222-2222-2222-222222222222', 3);

INSERT IGNORE INTO messages (student_id, course_id, text) VALUES
('11111111-1111-1111-1111-111111111111', 1, 'Når er eksamen?'),
('22222222-2222-2222-2222-222222222222', 3, 'Blir det obligatoriske innleveringer?'),
('11111111-1111-1111-1111-111111111111', 2, 'Kan vi få flere oppgaver til øving?');

INSERT IGNORE INTO replies (message_id, text) VALUES
(1, 'Eksamen er 12. desember.'),
(2, 'Ja, det blir to obligatoriske innleveringer.');

INSERT IGNORE INTO comments (message_id, text) VALUES
(1, 'Takk for raskt svar!'),
(1, 'Dette lurte jeg også på.'),
(3, 'Ja takk!');

INSERT IGNORE INTO reports (message_id, text) VALUES
(3, 'Denne meldingen inneholder sensitiv info.');
