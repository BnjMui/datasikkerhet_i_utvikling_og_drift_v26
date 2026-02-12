<?php
// api/register.php
// POST /api/register.php

require_once 'helpers.php';

$method = get_method();
$data   = get_request_data();

if ($method === 'POST') {
    validate_required($data, ['first_name', 'last_name', 'email', 'password', 'study_field', 'class_year']);

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        send_error('Ugyldig e-postformat', 400);
    }

    if (strlen($data['password']) < 8) {
        send_error('Passordet må være minst 8 tegn', 400);
    }

    // Sjekk om e-post finnes
    $stmt = $db->prepare("SELECT user_id FROM users WHERE mail = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->rowCount() > 0) send_error('E-posten er allerede registrert', 409);

    // Generer UUID
    $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // INSERT i users
    $stmt = $db->prepare(
        "INSERT INTO users (user_id, first_name, last_name, mail, role, password) VALUES (?, ?, ?, ?, 'student', ?)"
    );
    $stmt->execute([$uuid, trim($data['first_name']), trim($data['last_name']), trim($data['email']), $hashedPassword]);

    // INSERT i students
    $stmt = $db->prepare(
        "INSERT INTO students (student_id, study_field, class_year) VALUES (?, ?, ?)"
    );
    $stmt->execute([$uuid, trim($data['study_field']), trim($data['class_year'])]);

    $student = [
        'user_id'     => $uuid,
        'first_name'  => trim($data['first_name']),
        'last_name'   => trim($data['last_name']),
        'mail'        => trim($data['email']),
        'study_field' => trim($data['study_field']),
        'class_year'  => trim($data['class_year']),
    ];

    start_session($student);
    send_success(['student' => format_student($student), 'session_id' => session_id()], 'Registrering vellykket', 201);
}

send_response(['success' => false, 'error' => 'Metode ikke tillatt'], 405);