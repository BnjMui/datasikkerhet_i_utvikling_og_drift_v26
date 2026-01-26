<?php

$_db_host = "localhost:0000";
$_db_name = "test";
$_db_user = "username";
$_db_passwd = "password";

try {
    $db = new Pdo\Mysql("mysql:host=$_db_host;dbname=$_db_name", $_db_user, $_db_passwd);
} catch (PDOException $e) {
    echo $e;
}


// Close connection to db, do for all variables referencing $dbh. ??
// $db = null;
