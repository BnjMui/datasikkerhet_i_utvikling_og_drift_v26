<?php
// api/login.php
// POST /api/login.php

require_once 'helpers.php';

$method = get_method();
$data   = get_request_data();

if ($method === 'POST') {
    validate_required($data, ['email', 'password']);

    $stmt = $db->prepare("
        SELECT u.user_id, u.first_name, u.last_name, u.mail, u.password,
               s.study_field, s.class_year
        FROM users u
        JOIN students s ON u.user_id = s.student_id
        WHERE u.mail = ? AND u.role = 'student'
    ");
    $stmt->execute([$data['email']]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vag feilmelding — avslør ikke om e-post finnes
    if (!$student || !password_verify($data['password'], $student['password'])) {
        send_error('Ugyldig e-post eller passord', 401);
    }

    start_session($student);

    unset($student['password']); // Send aldri passord tilbake
    send_success(['student' => format_student($student), 'session_id' => session_id()], 'Innlogging vellykket');
}

send_response(['success' => false, 'error' => 'Metode ikke tillatt'], 405);