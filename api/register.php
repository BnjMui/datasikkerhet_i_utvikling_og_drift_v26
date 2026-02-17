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

    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // INSERT i users

    // INSERT i students

    start_session($student);
    send_success(['student' => format_student($student), 'session_id' => session_id()], 'Registrering vellykket', 201);
}

send_response(['success' => false, 'error' => 'Metode ikke tillatt'], 405);
