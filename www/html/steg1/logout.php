<?php
session_start();

$_SESSION = [];

session_destroy();

header('Location: /steg1');
exit;
