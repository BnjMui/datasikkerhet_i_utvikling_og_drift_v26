<?php
// api/subjects.php
// GET /api/subjects.php — hent alle emner
// PIN sendes aldri ut

require_once 'helpers.php';

$method = get_method();

if ($method === 'GET') {
    require_auth();

    $stmt = $db->query("
        SELECT
            c.course_id,
            c.course_code,
            u.user_id     AS lecturer_id,
            u.first_name  AS lecturer_first,
            u.last_name   AS lecturer_last,
            u.mail        AS lecturer_email,
            l.avatar
        FROM courses   c
        JOIN lecturers l ON c.lecturer_id = l.lecturer_id
        JOIN users     u ON l.lecturer_id = u.user_id
        ORDER BY c.course_code
    ");

    $subjects = array_map(fn($row) => [
        'id'       => $row['course_id'],
        'code'     => $row['course_code'],
        'lecturer' => [
            'id'        => $row['lecturer_id'],
            'name'      => $row['lecturer_first'] . ' ' . $row['lecturer_last'],
            'email'     => $row['lecturer_email'],
            'image_url' => $row['avatar'] ? '/images/' . $row['avatar'] : null,
        ],
    ], $stmt->fetchAll(PDO::FETCH_ASSOC));

    send_success(['subjects' => $subjects]);
}

send_response(['success' => false, 'error' => 'Metode ikke tillatt'], 405);