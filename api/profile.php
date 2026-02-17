<?php
// api/profile.php
// GET  /api/profile.php  — hent profil
// PUT  /api/profile.php  — oppdater profil

require_once 'helpers.php';

$method    = get_method();
$studentId = require_auth();

if ($method === 'GET') {

    if (!$student) send_error('Student ikke funnet', 404);

    send_success(['student' => format_student($student)]);
}

if ($method === 'PUT') {
    $data = get_request_data();

    // Oppdater users-tabellen
    $userFelter = ['first_name', 'last_name'];
    $sets = $params = [];
    foreach ($userFelter as $felt) {
        if (!empty($data[$felt])) { $sets[] = "$felt = ?"; $params[] = trim($data[$felt]); }
    }
    if ($sets) {
        $params[] = $studentId;
        $db->prepare("UPDATE users SET " . implode(', ', $sets) . " WHERE user_id = ?")->execute($params);
    }

    // Oppdater students-tabellen
    $studentFelter = ['study_field', 'class_year'];
    $sets = $params = [];
    foreach ($studentFelter as $felt) {
        if (!empty($data[$felt])) { $sets[] = "$felt = ?"; $params[] = trim($data[$felt]); }
    }
    if ($sets) {
        $params[] = $studentId;
        $db->prepare("UPDATE students SET " . implode(', ', $sets) . " WHERE student_id = ?")->execute($params);
    }

    send_success(null, 'Profil oppdatert');
}

send_response(['success' => false, 'error' => 'Metode ikke tillatt'], 405);
