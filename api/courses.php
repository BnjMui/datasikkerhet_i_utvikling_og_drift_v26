<?php

// api/subjects.php
// GET /api/subjects.php — hent alle emner

require_once 'helpers.php';

$method = get_method();

if ($method === 'GET') {

    $courses = repository()->getCourses();

    send_success($courses, "OK");
}

send_response(['success' => false, 'error' => 'Internal server error'], 500);
