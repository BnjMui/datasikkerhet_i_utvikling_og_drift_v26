USE steg2_datasikkerhet;

-- =====================
-- USERS
-- =====================
INSERT INTO
    users (
        user_id,
        first_name,
        last_name,
        mail,
        role,
        PASSWORD
    )
VALUES
    (
        'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
        'Erik',
        'Hansen',
        'erik.hansen@uni.no',
        'lecturer',
        '$2b$12$examplehashedpassword001'
    ),
    (
        'b2c3d4e5-f6a7-8901-bcde-f12345678901',
        'Ingrid',
        'Larsen',
        'ingrid.larsen@uni.no',
        'lecturer',
        '$2b$12$examplehashedpassword002'
    ),
    (
        'c3d4e5f6-a7b8-9012-cdef-123456789012',
        'Magnus',
        'Berg',
        'magnus.berg@student.no',
        'student',
        '$2b$12$examplehashedpassword003'
    ),
    (
        'd4e5f6a7-b8c9-0123-defa-234567890123',
        'Sofie',
        'Dahl',
        'sofie.dahl@student.no',
        'student',
        '$2b$12$examplehashedpassword004'
    ),
    (
        'e5f6a7b8-c9d0-1234-efab-345678901234',
        'Jonas',
        'Moen',
        'jonas.moen@student.no',
        'student',
        '$2b$12$examplehashedpassword005'
    ),
    (
        'f6a7b8c9-d0e1-2345-fabc-456789012345',
        'Hanna',
        'Vik',
        'hanna.vik@student.no',
        'student',
        '$2b$12$examplehashedpassword006'
    ),
    (
        'a7b8c9d0-e1f2-3456-abcd-567890123456',
        'Lars',
        'Nygaard',
        'lars.nygaard@student.no',
        'student',
        '$2b$12$examplehashedpassword007'
    ),
    (
        'b8c9d0e1-f2a3-4567-bcde-678901234567',
        'Mia',
        'Solberg',
        'mia.solberg@student.no',
        'student',
        '$2b$12$examplehashedpassword008'
    );

-- =====================
-- LECTURERS
-- =====================
INSERT INTO
    lecturers (lecturer_id, avatar)
VALUES
    (
        'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
        'avatars/erik_hansen.png'
    ),
    ('b2c3d4e5-f6a7-8901-bcde-f12345678901', NULL);

-- =====================
-- STUDENTS
-- =====================
INSERT INTO
    students (student_id, study_field, class_year)
VALUES
    (
        'c3d4e5f6-a7b8-9012-cdef-123456789012',
        'Computer Science',
        2023
    ),
    (
        'd4e5f6a7-b8c9-0123-defa-234567890123',
        'Information Security',
        2022
    ),
    (
        'e5f6a7b8-c9d0-1234-efab-345678901234',
        'Computer Science',
        2024
    ),
    (
        'f6a7b8c9-d0e1-2345-fabc-456789012345',
        'Software Engineering',
        2023
    ),
    (
        'a7b8c9d0-e1f2-3456-abcd-567890123456',
        'Information Security',
        2024
    ),
    (
        'b8c9d0e1-f2a3-4567-bcde-678901234567',
        'Computer Science',
        2022
    );

-- =====================
-- SECURITY QUESTIONS
-- =====================
INSERT INTO
    security_questions (
        question_id,
        user_id,
        security_question,
        security_answer
    )
VALUES
    (
        '11111111-1111-1111-1111-111111111111',
        'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
        'What was the name of your first pet?',
        'Mittens'
    ),
    (
        '22222222-2222-2222-2222-222222222222',
        'b2c3d4e5-f6a7-8901-bcde-f12345678901',
        'What city were you born in?',
        'Bergen'
    ),
    (
        '33333333-3333-3333-3333-333333333333',
        'c3d4e5f6-a7b8-9012-cdef-123456789012',
        'What is your mothers maiden name?',
        'Johansen'
    ),
    (
        '44444444-4444-4444-4444-444444444444',
        'd4e5f6a7-b8c9-0123-defa-234567890123',
        'What was the name of your first school?',
        'Ås skole'
    ),
    (
        '55555555-5555-5555-5555-555555555555',
        'e5f6a7b8-c9d0-1234-efab-345678901234',
        'What is your oldest siblings middle name?',
        'Kristian'
    ),
    (
        '66666666-6666-6666-6666-666666666666',
        'f6a7b8c9-d0e1-2345-fabc-456789012345',
        'What was the make of your first car?',
        'Volkswagen'
    ),
    (
        '77777777-7777-7777-7777-777777777777',
        'a7b8c9d0-e1f2-3456-abcd-567890123456',
        'What was the name of your childhood best friend?',
        'Ole'
    ),
    (
        '88888888-8888-8888-8888-888888888888',
        'b8c9d0e1-f2a3-4567-bcde-678901234567',
        'What street did you grow up on?',
        'Storgata'
    );

-- =====================
-- COURSES
-- =====================
INSERT INTO
    courses (lecturer_id, course_code, course_name, pin_code)
VALUES
    (
        'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
        'DAT101',
        'Introduction to Programming',
        '1234'
    ),
    (
        'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
        'DAT310',
        'Database Systems',
        '5678'
    ),
    (
        'b2c3d4e5-f6a7-8901-bcde-f12345678901',
        'SEC201',
        'Network Security',
        '9012'
    ),
    (
        'b2c3d4e5-f6a7-8901-bcde-f12345678901',
        'SEC301',
        'Ethical Hacking',
        '3456'
    );

-- =====================
-- STUDENTS_COURSES
-- =====================
INSERT INTO
    students_courses (student_id, course_id)
VALUES
    ('c3d4e5f6-a7b8-9012-cdef-123456789012', 1),
    ('c3d4e5f6-a7b8-9012-cdef-123456789012', 2),
    ('d4e5f6a7-b8c9-0123-defa-234567890123', 3),
    ('d4e5f6a7-b8c9-0123-defa-234567890123', 4),
    ('e5f6a7b8-c9d0-1234-efab-345678901234', 1),
    ('e5f6a7b8-c9d0-1234-efab-345678901234', 3),
    ('f6a7b8c9-d0e1-2345-fabc-456789012345', 2),
    ('f6a7b8c9-d0e1-2345-fabc-456789012345', 4),
    ('a7b8c9d0-e1f2-3456-abcd-567890123456', 1),
    ('a7b8c9d0-e1f2-3456-abcd-567890123456', 3),
    ('b8c9d0e1-f2a3-4567-bcde-678901234567', 2),
    ('b8c9d0e1-f2a3-4567-bcde-678901234567', 4);

-- =====================
-- MESSAGES
-- =====================
INSERT INTO
    messages (student_id, course_id, created_at, text)
VALUES
    (
        'c3d4e5f6-a7b8-9012-cdef-123456789012',
        1,
        '2025-01-15 09:10:00',
        'When is the first assignment due?'
    ),
    (
        'c3d4e5f6-a7b8-9012-cdef-123456789012',
        2,
        '2025-01-16 10:20:00',
        'Can you clarify the difference between inner and outer joins?'
    ),
    (
        'd4e5f6a7-b8c9-0123-defa-234567890123',
        3,
        '2025-01-17 11:30:00',
        'Is there a recommended book for the network security course?'
    ),
    (
        'e5f6a7b8-c9d0-1234-efab-345678901234',
        1,
        '2025-01-18 13:00:00',
        'Will there be a lecture recording available?'
    ),
    (
        'f6a7b8c9-d0e1-2345-fabc-456789012345',
        4,
        '2025-01-19 14:15:00',
        'What tools do we need installed for the ethical hacking labs?'
    ),
    (
        'a7b8c9d0-e1f2-3456-abcd-567890123456',
        3,
        '2025-01-20 15:00:00',
        'Are the slides from last week available?'
    ),
    (
        'b8c9d0e1-f2a3-4567-bcde-678901234567',
        2,
        '2025-01-21 09:45:00',
        'I am having trouble connecting to the database server.'
    );

-- =====================
-- REPLIES
-- =====================
INSERT INTO
    replies (message_id, created_at, text)
VALUES
    (
        1,
        '2025-01-15 10:00:00',
        'The first assignment is due on January 30th. Check the course page for details.'
    ),
    (
        2,
        '2025-01-16 11:00:00',
        'An inner join returns only matching rows, while an outer join includes unmatched rows from one or both tables.'
    ),
    (
        3,
        '2025-01-17 12:30:00',
        'I recommend "Computer Networking" by Tanenbaum. It is available in the library.'
    ),
    (
        4,
        '2025-01-18 14:00:00',
        'Yes, recordings will be uploaded to the course portal within 24 hours.'
    );

-- =====================
-- COMMENTS
-- =====================
INSERT INTO
    comments (message_id, created_at, text)
VALUES
    (
        1,
        '2025-01-15 10:30:00',
        'Same question here, thanks for asking!'
    ),
    (
        2,
        '2025-01-16 11:45:00',
        'Great explanation, I was confused about this too.'
    ),
    (
        3,
        '2025-01-17 13:00:00',
        'I found a free PDF version online as well.'
    ),
    (
        5,
        '2025-01-19 15:00:00',
        'We need Kali Linux and Wireshark based on last years syllabus.'
    ),
    (
        6,
        '2025-01-20 15:30:00',
        'I found them in the Files section under Week 2.'
    ),
    (
        7,
        '2025-01-21 10:00:00',
        'Make sure you are on the university VPN first!'
    );

-- =====================
-- REPORTS
-- =====================
INSERT INTO
    reports (message_id, created_at, text)
VALUES
    (
        5,
        '2025-01-20 08:00:00',
        'This message may contain a link to a potentially unsafe resource.'
    ),
    (
        7,
        '2025-01-22 09:00:00',
        'Reported for sharing external credentials in the message thread.'
    );
