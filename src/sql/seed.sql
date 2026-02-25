-- SET FOREIGN_KEY_CHECKS = 0;
-- 
-- TRUNCATE reports;
-- TRUNCATE comments;
-- TRUNCATE replies;
-- TRUNCATE messages;
-- TRUNCATE students_courses;
-- TRUNCATE courses;
-- TRUNCATE lecturers;
-- TRUNCATE students;
-- TRUNCATE users;
-- 
-- SET FOREIGN_KEY_CHECKS = 1;


INSERT INTO users (user_id, first_name, last_name, mail, role, password) VALUES
('11111111-1111-1111-1111-111111111111', 'Ola', 'Nordmann', 'ola@student.no', 'student', '$2y$10$examplehash1'),
('22222222-2222-2222-2222-222222222222', 'Kari', 'Hansen', 'kari@student.no', 'student', '$2y$10$examplehash2'),
('33333333-3333-3333-3333-333333333333', 'Per', 'Larsen', 'per@foreleser.no', 'lecturer', '$2y$10$examplehash3'),
('44444444-4444-4444-4444-444444444444', 'Anne', 'Berg', 'anne@foreleser.no', 'lecturer', '$2y$10$examplehash4');

INSERT INTO students (student_id, study_field, class_year) VALUES
('11111111-1111-1111-1111-111111111111', 'Informatikk', 2023),
('22222222-2222-2222-2222-222222222222', 'Datasikkerhet', 2022);

INSERT INTO lecturers (lecturer_id, avatar, security_question, security_answer) VALUES
('33333333-3333-3333-3333-333333333333', 'profile_avatars/per_avatar.png', 'Hva er din favorittfarge?', 'Blå'),
('44444444-4444-4444-4444-444444444444', 'profile_avatars/anne_avatar.png', 'Hva heter ditt første kjæledyr?', 'Milo');

INSERT INTO courses (lecturer_id, course_code, course_name, pin_code) VALUES
('33333333-3333-3333-3333-333333333333', 'DAT100', 'Introduksjon til programmering', '1234'),
('33333333-3333-3333-3333-333333333333', 'DAT200', 'Datastrukturer', '5678'),
('44444444-4444-4444-4444-444444444444', 'SEC300', 'Etisk hacking', '4321');

INSERT INTO students_courses (student_id, course_id) VALUES
('11111111-1111-1111-1111-111111111111', 1),
('11111111-1111-1111-1111-111111111111', 2),
('22222222-2222-2222-2222-222222222222', 3);

INSERT INTO messages (student_id, course_id, text) VALUES
('11111111-1111-1111-1111-111111111111', 1, 'Når er eksamen?'),
('22222222-2222-2222-2222-222222222222', 3, 'Blir det obligatoriske innleveringer?'),
('11111111-1111-1111-1111-111111111111', 2, 'Kan vi få flere oppgaver til øving?');

INSERT INTO replies (message_id, text) VALUES
(1, 'Eksamen er 12. desember.'),
(2, 'Ja, det blir to obligatoriske innleveringer.');

INSERT INTO comments (message_id, text) VALUES
(1, 'Takk for raskt svar!'),
(1, 'Dette lurte jeg også på.'),
(3, 'Ja takk!');

INSERT INTO reports (message_id, text) VALUES
(3, 'Denne meldingen inneholder sensitiv info.');
