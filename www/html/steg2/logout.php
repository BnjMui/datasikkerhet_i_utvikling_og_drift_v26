<?php
session_start();

$_SESSION = [];

session_destroy();

header('Location: /steg2');
exit;
