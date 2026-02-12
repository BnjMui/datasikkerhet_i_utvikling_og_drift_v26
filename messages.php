<?php
// api/messages.php
// GET  /api/messages.php  — hent meldinger
// POST /api/messages.php  — send melding

require_once 'helpers.php';

$method    = get_method();
$studentId = require_auth();

if ($method === 'GET') {
    $stmt = $db->prepare("
        SELECT
            m.message_id,
            m.text           AS message_text,
            m.created_at,
            m.course_id,
            c.course_code,
            u.first_name     AS lecturer_first,
            u.last_name      AS lecturer_last,
            r.text           AS response_text,
            r.created_at     AS response_date
        FROM messages m
        JOIN courses   c ON m.course_id   = c.course_id
        JOIN lecturers l ON c.lecturer_id = l.lecturer_id
        JOIN users     u ON l.lecturer_id = u.user_id
        LEFT JOIN replies r ON m.message_id = r.message_id
        WHERE m.student_id = ?
        ORDER BY m.created_at DESC
    ");
    $stmt->execute([$studentId]);

    $messages = array_map(fn($row) => [
        'id'         => $row['message_id'],
        'message'    => $row['message_text'],
        'created_at' => $row['created_at'],
        'course'     => ['id' => $row['course_id'], 'code' => $row['course_code']],
        'lecturer'   => ['name' => $row['lecturer_first'] . ' ' . $row['lecturer_last']],
        'response'   => $row['response_text']
            ? ['text' => $row['response_text'], 'date' => $row['response_date']]
            : null,
    ], $stmt->fetchAll(PDO::FETCH_ASSOC));

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