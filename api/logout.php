<?php
// api/logout.php
// POST /api/logout.php

require_once 'helpers.php';

$method = get_method();

if ($method === 'POST') {
    require_auth();
    session_destroy();
    send_success(null, 'Logget ut');
}

send_response(['success' => false, 'error' => 'Metode ikke tillatt'], 405);