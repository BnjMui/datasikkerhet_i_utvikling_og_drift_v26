<?php
session_start();
session_destroy();
header('Location: guest_hjemmeside.php');
exit;
