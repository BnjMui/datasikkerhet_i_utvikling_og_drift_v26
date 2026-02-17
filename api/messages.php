<?php
// api/messages.php
// GET  /api/messages.php  — hent meldinger
// POST /api/messages.php  — send melding

require_once 'helpers.php';

$method    = get_method();
$studentId = require_auth();

if ($method === 'GET') {


    send_success(['messages' => $messages]);
}

if ($method === 'POST') {
    $data        = get_request_data();
    validate_required($data, ['course_id', 'message']);

    $courseId    = intval($data['course_id']);
    $messageText = trim($data['message']);

    if (!$messageText) send_error('Melding kan ikke være tom', 400);

    // Sjekk at kurset finnes
    $stmt = $db->prepare("SELECT course_id FROM courses WHERE course_id = ?");
    $stmt->execute([$courseId]);
    if ($stmt->rowCount() === 0) send_error('Emnet ble ikke funnet', 404);

    $stmt = $db->prepare("INSERT INTO messages (student_id, course_id, text) VALUES (?, ?, ?)");
    $stmt->execute([$studentId, $courseId, $messageText]);

    send_success([
        'message_id' => $db->lastInsertId(),
        'created_at' => date('Y-m-d H:i:s')
    ], 'Melding sendt', 201);
}

send_response(['success' => false, 'error' => 'Metode ikke tillatt'], 405);
