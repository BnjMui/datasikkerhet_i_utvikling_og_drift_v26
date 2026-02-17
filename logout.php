<?php
session_start();
session_destroy();
header('Location: ../html/guest_hjemmeside.php');
exit;
