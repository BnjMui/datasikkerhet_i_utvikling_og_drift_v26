<?php

$_db_host = "localhost:3306";
$_db_name = "datasikkerhet";
$_db_user = "root";
$_db_passwd = "dev";

try {
    $db = new Pdo\Mysql("mysql:host=$_db_host;dbname=$_db_name", $_db_user, $_db_passwd);
#    echo "connection success\n";
} catch (PDOException $e) {
    echo "connection to db failed";
    echo $e;
    echo $e->getMessage();
}

$result = $db->query("SHOW COLUMNS FROM users;")->fetchAll();

# echo json_encode($result);

 foreach ($result as $key => $column) {
     echo "Column $column[Field]\n";
 }

$query = null;
$db = null;

// Close connection to db, do for all variables referencing $dbh. ??
// $db = null;
