<?php

include_once "entities/user.php";
include_once "models/create_user_dto.php";
include_once "models/create_student_dto.php";
include_once "models/create_lecturer_dto.php";
include_once "models/create_course_dto.php";

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
    public function getUserByMail(string $mail): User
    {
        # TODO: Fjerne passord og lage nytt objekt for bruker uten passord
        $statement = $this->dbh->prepare("SELECT user_id, first_name, last_name, mail, role, password FROM users WHERE mail = ?");
        $statement->execute([$mail]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $user = new User($result["user_id"], $result["first_name"], $result["last_name"], $result["mail"], $result["role"], $result["password"]);
        return $user;
    }

    public function createUser(string $uid, CreateUserDto $userData): void
    {
        $statement = $this->dbh->prepare(
            "INSERT INTO users
            (user_id, first_name, last_name, mail, role, password)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $statement->execute([$uid, $userData->first_name, $userData->last_name, $userData->mail, $userData->role, $userData->password]);
    }

    public function createStudent(CreateStudentDto $userData): bool
    {
        $statement = $this->dbh->prepare(
            "INSERT INTO students
                (student_id, study_field, class_year)
            VALUES (?, ?, ?)"
        );

        try {
            $uid = $this->uuid();
            $this->dbh->beginTransaction();

            $this->createUser($uid, $userData);
            $statement->execute([$uid, $userData->study_field, $userData->class_year]);

            return $this->dbh->commit();
        } catch (Exception $e) {
            $this->dbh->rollBack();
            throw $e;
        }
    }

    public function createLecturer(CreateLecturerDto $userData): bool
    {
        $statement = $this->dbh->prepare(
            "INSERT INTO lecturers
                (lecturer_id, avatar, security_question, security_answer)
            VALUES (?, ?, ?, ?)"
        );
        try {
            $uid = $this->uuid();
            $this->dbh->beginTransaction();

            $this->createUser($uid, $userData);
            $statement->execute([$uid, $userData->avatar, $userData->security_question, $userData->security_answer]);
            $this->createCourse($uid, $userData->course);

            return $this->dbh->commit();
        } catch (Exception $e) {
            $this->dbh->rollBack();
            throw $e;
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
        $statement = $this->dbh->prepare(
            "SELECT course_id, lecturer_id, course_code, pin_code FROM courses"
        );

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        print_r($result);
        
    }

    public function getCourse()
    {

    }

    public function createCourse(string $lecturer_id, CreateCourseDto $courseData): void
    {
        // TODO legg til createCourse objekt som property i CreateLecturerDto objektet
        $statement = $this->dbh->prepare(
            "INSERT INTO courses
                (lecturer_id, course_code, pin_code)
            VALUES(?, ?, ?)"
        );

        $statement->execute([$lecturer_id, $courseData->course_code, $courseData->pin_code]);
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
    private function uuid(): string
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
