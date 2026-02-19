<?php

require_once "db.php";

require_once "models/create_user_dto.php";
require_once "models/create_student_dto.php";
require_once "models/create_lecturer_dto.php";
require_once "models/create_course_dto.php";
require_once "models/user_login_dto.php";
require_once "models/create_message_dto.php";
require_once "models/message_dto.php";
require_once "models/course_dto.php";
require_once "models/create_comment_dto.php";
require_once "models/base_message_reply.php";
require_once "models/user_dto.php";

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
    public function getUserByMail(string $mail): UserDto
    {
        # TODO: Fjerne passord og lage nytt objekt for bruker uten passord
        $statement = $this->dbh->prepare("SELECT user_id, first_name, last_name, mail, role, password FROM users WHERE mail = ?");
        $statement->execute([$mail]);
        $result = $statement->fetchObject("UserDto");

        return $result;
    }

    public function getUserById(string $id): UserDto
    {
        # TODO: Fjerne passord og lage nytt objekt for bruker uten passord
        $statement = $this->dbh->prepare("SELECT user_id, first_name, last_name, mail, role FROM users WHERE user_id = ?");
        $statement->execute([$id]);
        $result = $statement->fetchObject("UserDto");

        return $result;
    }

    public function getUserLoginInfo(string $mail): UserLoginDto
    {
        $statement = $this->dbh->prepare("SELECT user_id, mail, password FROM users WHERE mail = ?");

        $statement->execute([$mail]);
        $result = $statement->fetchObject("UserLoginDto");

        return $result;
    }

    public function getSecurityQuestionByMail(string $mail): string
    {
        $statement = $this->dbh->prepare("
            SELECT security_question FROM lecturers l, users u
            WHERE l.lecturer_id = u.user_id AND u.mail = ?
            ");

        $statement->execute([$mail]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result["security_question"];

    }

    public function getSecurityAnswerByMail(string $mail): mixed
    {
        $statement = $this->dbh->prepare("
            SELECT user_id, mail, security_question, security_answer FROM lecturers l, users u
            WHERE l.lecturer_id = u.user_id AND u.mail = ?
            ");

        $statement->execute([$mail]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
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
            return false;
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
            return false;
        }
    }

    public function updatePasswordByUserId(string $user_id, string $new_password): bool
    {
        $statement = $this->dbh->prepare("
            UPDATE users
            SET password = ?
            WHERE user_id = ?
            ");
        try {
            $this->dbh->beginTransaction();

            $statement->execute([$new_password, $user_id]);

            return $this->dbh->commit();
        } catch (Exception $e) {
            $this->dbh->rollBack();
            throw $e;
            return false;
        }
    }

    public function getStudentDataById(string $user_id): StudentDataDto
    {
        $statement = $this->dbh->prepare("
            SELECT study_field, class_year FROM students
            WHERE student_id = ?
            ");

        $statement->execute([$user_id]);

        $result = $statement->fetchObject("StudentDataDto");

        return $result;
    }

    public function getLecturerDataById(string $user_id): LecturerDto
    {
        $statement = $this->dbh->prepare("
            SELECT lecturer_id, first_name, last_name, mail, avatar FROM lecturers l, users u
            WHERE lecturer_id = user_id AND lecturer_id = ?
            ");

        $statement->execute([$user_id]);

        $result = $statement->fetchObject("LecturerDto");

        return $result;
    }


    # Courses
    /*
    * @return list<CourseDto>
    */
    public function getCourses(): array
    {
        $statement = $this->dbh->prepare(
            "SELECT course_id, lecturer_id, course_code, course_name FROM courses"
        );

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_CLASS, "CourseDto");

        return $result;
    }

    /*
    * @return list<CourseDto>
    */
    public function getStudentCourses(string $user_id): array
    {
        $statement = $this->dbh->prepare("SELECT c.course_id, lecturer_id, course_code, course_name, pin_code FROM courses c, students_courses s WHERE c.course_id = s.course_id AND s.student_id = ?");

        $statement->execute([$user_id]);
        $result = $statement->fetchAll(PDO::FETCH_CLASS, "CourseDto");

        return $result;
    }

    public function getCourseById(int $course_id): CourseDto
    {
        $statement = $this->dbh->prepare("SELECT course_id, lecturer_id, course_code, course_name, pin_code FROM courses WHERE course_id = ?");

        $statement->execute([$course_id]);

        $result = $statement->fetchObject("CourseDto");

        return $result;
    }

    public function getCoursePin(int $course_id): mixed
    {
        $statement = $this->dbh->prepare("SELECT pin_code FROM courses WHERE course_id = ?");

        $statement->execute([$course_id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result["pin_code"];
    }

    public function createCourse(string $lecturer_id, CreateCourseDto $courseData): bool
    {
        // TODO legg til createCourse objekt som property i CreateLecturerDto objektet
        $statement = $this->dbh->prepare(
            "INSERT INTO courses
                (lecturer_id, course_code, course_name, pin_code)
            VALUES(?, ?, ?, ?)"
        );

        try {
            $statement->execute([$lecturer_id, $courseData->course_code, $courseData->course_name, $courseData->pin_code]);
            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }

    public function addCourseToStudent(string $student_id, int $course_id): bool
    {
        $statement = $this->dbh->prepare(
            "INSERT INTO students_courses 
                (student_id, course_id)
            VALUES (?, ?)"
        );

        try {
            $this->dbh->beginTransaction();
            $statement->execute([$student_id, $course_id]);

            return $this->dbh->commit();
        } catch (Exception $e) {
            $this->dbh->rollBack();
            throw $e;
            return false;
        }
    }

    # Messages
    /*
    * @return list<MessageDto>
    */
    public function getMessages(int $course_id): array
    {
        $statement = $this->dbh->prepare("SELECT message_id, course_id, created_at, text FROM messages WHERE course_id = ?");

        $statement->execute([$course_id]);
        $result = $statement->fetchAll(PDO::FETCH_CLASS, "MessageDto");

        return $result;
    }

    public function createMessage(CreateMessageDto $message): bool
    {
        $statement = $this->dbh->prepare(
            "INSERT INTO messages
                (student_id, course_id, text)
            VALUES (?, ?, ?)"
        );

        try {
            $this->dbh->beginTransaction();
            $statement->execute([
                $message->student_id,
                $message->course_id,
                $message->text
            ]);

            return $this->dbh->commit();
        } catch (Exception $e) {
            $this->dbh->rollBack();
            throw $e;
            return false;
        }
    }

    # Responses
    /*
    * @return list<ReplyDto>
    */
    public function getReplies(int $message_id): array
    {
        $statement = $this->dbh->prepare("SELECT reply_id, message_id, created_at, text FROM replies WHERE message_id = ?");

        $statement->execute([$message_id]);
        $result = $statement->fetchAll(PDO::FETCH_CLASS, "ReplyDto");

        return $result;
    }

    public function createReply(BaseMessageReplyType $reply): bool
    {
        $statement = $this->dbh->prepare(
            "INSERT INTO replies
                (message_id, text)
            VALUES (?, ?)"
        );

        try {
            $this->dbh->beginTransaction();
            $statement->execute([$reply->message_id, $reply->text]);

            return $this->dbh->commit();
        } catch (Exception $e) {
            $this->dbh->rollBack();
            throw $e;
            return false;
        }
    }

    # Comments
    /*
    * @return list<CommentDto>
    */
    public function getComments(string $message_id): array
    {
        $statement = $this->dbh->prepare("SELECT comment_id, message_id, created_at, text FROM comments WHERE message_id = ?");

        $statement->execute([$message_id]);
        $result = $statement->fetchAll(PDO::FETCH_CLASS, "MessageDto");

        return $result;
    }

    public function createComment(BaseMessageReplyType $comment): bool
    {
        $statement = $this->dbh->prepare(
            "INSERT INTO comments
                (message_id, text)
            VALUES (?, ?)"
        );

        try {
            $this->dbh->beginTransaction();
            $statement->execute([$comment->message_id, $comment->text]);

            return $this->dbh->commit();
        } catch (Exception $e) {
            $this->dbh->rollBack();
            throw $e;
            return false;
        }
    }

    # Reports
    public function createReport(BaseMessageReplyType $report): bool
    {

        $statement = $this->dbh->prepare(
            "INSERT INTO reports
                (message_id, text)
            VALUES (?, ?)"
        );

        try {
            $this->dbh->beginTransaction();
            $statement->execute([$report->message_id, $report->text]);

            return $this->dbh->commit();
        } catch (Exception $e) {
            $this->dbh->rollBack();
            throw $e;
            return false;
        }
    }

    # uuid funksjon hentet fra:
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
}
