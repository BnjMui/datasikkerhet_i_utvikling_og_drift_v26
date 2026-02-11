<?php

use Entities\User;

include "./entities/user.php";
include "db.php";
class Repository
{
    private Database $db;
    private Pdo\Mysql $dbh;

    public function __construct()
    {
        $this->db = new Database("localhost", "datasikkerhet", "root", "dev");
        $this->dbh = $this->db->getDb();
    }

    # Users
    public function getUserByMail(string $mail)
    {
        $dbh = $this->dbh;

        $statement = $dbh->prepare("SELECT user_id, name, mail, role FROM users WHERE mail = ?");
        $statement->execute([$mail]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $user = new User($result["user_id"], $result["name"], $result["mail"], $result["role"], "pass");
        print_r($user);

    }

    public function createUser($uid, User $userData)
    {
        $dbh = $this->dbh;

        $statement = $dbh->prepare("INSERT INTO users (user_id, name, mail, role, password) VALUES (:id :name,:mail,:role,:password)");

        $statement -> bindParam(":id", $id);
        $statement -> bindParam(":name", $name);
        $statement -> bindParam(":mail", $mail);
        $statement -> bindParam(":role", $role);
        $statement -> bindParam(":password", $password);

        $id = $uid;
        $name = $userData->name;
        $mail = $userData->mail;
        $role = $userData->role;
        $password = $userData->password;
    }

    public function createStudent($userData)
    {
        $dbh = $this->dbh;
        $statement = $dbh->prepare("INSERT INTO students (student_id, 
        try {
            $uid = uuid();
            $dbh->beginTransaction();

            create_user($uid, $userData);



            $dbh->commit;
        } catch (Exception $e) {
            $dbh->rollBack();
        }
    }

    public function createLecturer($userData)
    {
        $dbh = $this->dbh;
        try {

            $dbh->beginTransaction();


            $dbh->commit;
        } catch (Exception $e) {
            $dbh->rollBack();
        }
    }

    public function updatePassword()
    {
    }

    public function getStudentById()
    {
    }

    public function getLecturerById()
    {
    }

    public function checkUserLoginById()
    {
    }

    # Courses
    public function getCourses()
    {
    }

    public function getCourse()
    {
    }

    public function createCourse()
    {
    }

    public function checkCoursePin()
    {
    }

    # Messages
    public function getMessages()
    {
    }

    public function createMessage()
    {
    }

    # Responses
    public function createResponse()
    {
    }

    # Comments

    # Reports

    # guid4v funksjon hentet fra:
    # https://www.uuidgenerator.net/dev-corner/php
    private function uuid()
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


    # TESTING
    # $result = $db->query("SHOW COLUMNS FROM users;")->fetchAll();

    # echo json_encode($result);

    #  foreach ($result as $key => $column) {
    #      echo "Column $column[Field]\n";
    #  }
    #
    # $query = null;
    # $db = null;

    // Close connection to db, do for all variables referencing $dbh. ??
    // $db = null;
}
