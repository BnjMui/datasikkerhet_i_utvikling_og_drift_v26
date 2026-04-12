<?php

namespace DatasikkerhetG7\Repository;

require __DIR__ . "/../../vendor/autoload.php";

use DatasikkerhetG7\Models\BaseMessageReply;
use DatasikkerhetG7\Models\Comment;
use DatasikkerhetG7\Models\Course;
use DatasikkerhetG7\Models\CreateBaseMessageReply;
use DatasikkerhetG7\Models\CreateCourseDto;
use DatasikkerhetG7\Models\CreateLecturerDto;
use DatasikkerhetG7\Models\CreateMessageDto;
use DatasikkerhetG7\Models\CreateSecurityQuestion;
use DatasikkerhetG7\Models\CreateStudentDto;
use DatasikkerhetG7\Models\CreateUserDto;
use DatasikkerhetG7\Models\Lecturer;
use DatasikkerhetG7\Models\Message;
use DatasikkerhetG7\Models\Reply;
use DatasikkerhetG7\Models\Report;
use DatasikkerhetG7\Models\Student;
use DatasikkerhetG7\Models\User;
use DatasikkerhetG7\Models\UserLogin;
use DatasikkerhetG7\Models\UserSecurityAnswer;
use DatasikkerhetG7\Models\UserSecurityQuestion;
use PDO;
use PDOException;

class Repository
{
    private Database $db;
    private PDO $dbh;

    public function __construct()
    {
        $this->db = new Database("db", "steg2_datasikkerhet", "root", "dev");
        $this->dbh = $this->db->getDb();
    }

    # Users
    public function getUserByMail(string $mail): User | false
    {
        # TODO: Fjerne passord og lage nytt objekt for bruker uten passord
        $statement = $this->dbh->prepare("SELECT user_id, first_name, last_name, mail, role, password FROM users WHERE mail = ?");
        $statement->execute([$mail]);
        $result = $statement->fetchObject(User::class);

        return $result;
    }

    public function getUserById(string $id): User | false
    {
        # TODO: Fjerne passord og lage nytt objekt for bruker uten passord
        $statement = $this->dbh->prepare("SELECT user_id, first_name, last_name, mail, role FROM users WHERE user_id = ?");
        $statement->execute([$id]);
        $result = $statement->fetchObject(User::class);

        return $result;
    }

    public function getUserLoginInfo(string $mail): UserLogin | false
    {
        $statement = $this->dbh->prepare("SELECT user_id, mail, password FROM users WHERE mail = ?");

        $statement->execute([$mail]);
        $result = $statement->fetchObject(UserLogin::class);

        return $result;
    }

    public function createSecurityQuestion(CreateSecurityQuestion $security_question): bool
    {
        $security_question_uid = $this->uuid();

        $statement = $this->dbh->prepare(
            "INSERT INTO security_questions
                (question_id, user_id, security_question, security_answer)
            VALUES (?, ?, ?, ?)"
        );

        return $statement->execute([$security_question_uid, $security_question->user_id, $security_question->security_question, $security_question->security_answer]);
    }

    /*
    * @return list<UserSecurityQuestion>
    */
    public function getSecurityQuestionsByMail(string $mail): array
    {
        $statement = $this->dbh->prepare("
            SELECT question_id, security_question FROM security_questions sq, users u
            WHERE sq.user_id = u.user_id AND u.mail = ?
            ");

        $statement->execute([$mail]);

        $result = $statement->fetchAll(PDO::FETCH_CLASS, UserSecurityQuestion::class);

        return $result;
    }

    public function getSecurityAnswersByMail(string $mail): mixed
    {
        $statement = $this->dbh->prepare("
            SELECT sq.question_id, u.mail, u.user_id, sq.security_question, sq.security_answer FROM security_questions sq, users u
            WHERE sq.user_id = u.user_id AND u.mail = ?
            ");

        $statement->execute([$mail]);

        $result = $statement->fetchAll(PDO::FETCH_CLASS, UserSecurityAnswer::class);

        return $result;
    }

    public function createUser(string $uid, CreateUserDto $userData): bool
    {
        $statement = $this->dbh->prepare(
            "INSERT INTO users
            (user_id, first_name, last_name, mail, role, password)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        ####### TO REVIEW!!!
        foreach ($userData->security_questions as $security_question_without_uid) {
            $security_question = new CreateSecurityQuestion();
            $security_question->user_id = $uid;
            $security_question->security_question = $security_question_without_uid["security_question"];
            $security_question->security_answer = $security_question_without_uid["security_answer"];

            $this->createSecurityQuestion($security_question);
        }


        return $statement->execute([$uid, $userData->first_name, $userData->last_name, $userData->mail, $userData->role, $userData->password]);
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
        } catch (PDOException $e) {
            $this->dbh->rollBack();
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
        } catch (PDOException $e) {
            $this->dbh->rollBack();
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
        } catch (PDOException $e) {
            $this->dbh->rollBack();
            return false;
        }
    }

    public function getStudentDataById(string $user_id): Student | false
    {
        $statement = $this->dbh->prepare("
            SELECT study_field, class_year FROM students
            WHERE student_id = ?
            ");

        $statement->execute([$user_id]);

        $result = $statement->fetchObject(Student::class);

        return $result;
    }

    public function getLecturerDataById(string $user_id): Lecturer | false
    {
        $statement = $this->dbh->prepare("
            SELECT lecturer_id, first_name, last_name, mail, avatar FROM lecturers l, users u
            WHERE lecturer_id = user_id AND lecturer_id = ?
            ");

        $statement->execute([$user_id]);

        $result = $statement->fetchObject(Lecturer::class);

        return $result;
    }


    # Courses
    /*
    * @return list<Course>
    */
    public function getCourses(): array
    {
        $statement = $this->dbh->prepare(
            "SELECT course_id, lecturer_id, course_code, course_name FROM courses"
        );

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_CLASS, Course::class);

        return $result;
    }

    /*
    * @return list<Course>
    */
    public function getStudentCourses(string $user_id): array
    {
        $statement = $this->dbh->prepare(
            "SELECT c.course_id, lecturer_id, course_code, course_name, pin_code
            FROM courses c, students_courses s
            WHERE c.course_id = s.course_id AND s.student_id = ?"
        );

        $statement->execute([$user_id]);
        $result = $statement->fetchAll(PDO::FETCH_CLASS, Course::class);

        return $result;
    }

    public function getCourseById(int $course_id): Course | false
    {
        $statement = $this->dbh->prepare(
            "SELECT course_id, lecturer_id, course_code, course_name, pin_code 
            FROM courses WHERE course_id = ?"
        );

        $statement->execute([$course_id]);

        $result = $statement->fetchObject(Course::class);

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
        $statement = $this->dbh->prepare(
            "INSERT INTO courses
                (lecturer_id, course_code, course_name, pin_code)
            VALUES(?, ?, ?, ?)"
        );

        return $statement->execute([$lecturer_id, $courseData->course_code, $courseData->course_name, $courseData->pin_code]);
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
        } catch (PDOException $e) {
            $this->dbh->rollBack();
            return false;
        }
    }

    # Messages
    /*
    * @return list<Message>
    */
    public function getMessages(int $course_id): array
    {
        $statement = $this->dbh->prepare("SELECT message_id, course_id, created_at, text FROM messages WHERE course_id = ?");

        $statement->execute([$course_id]);
        $result = $statement->fetchAll(PDO::FETCH_CLASS, Message::class);

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
        } catch (PDOException $e) {
            $this->dbh->rollBack();
            return false;
        }
    }

    # Responses
    /*
    * @return list<BaseMessageReply>
    */
    public function getReplies(int $message_id): array
    {
        $statement = $this->dbh->prepare("SELECT reply_id, message_id, created_at, text FROM replies WHERE message_id = ?");

        $statement->execute([$message_id]);
        $result = $statement->fetchAll(PDO::FETCH_CLASS, Reply::class);

        return $result;
    }

    public function createReply(CreateBaseMessageReply $reply): bool
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
        } catch (PDOException $e) {
            $this->dbh->rollBack();
            return false;
        }
    }

    # Comments
    /*
    * @return list<Comment>
    */
    public function getComments(string $message_id): array
    {
        $statement = $this->dbh->prepare("SELECT comment_id, message_id, created_at, text FROM comments WHERE message_id = ?");

        $statement->execute([$message_id]);
        $result = $statement->fetchAll(PDO::FETCH_CLASS, Comment::class);

        return $result;
    }

    public function createComment(CreateBaseMessageReply $comment): bool
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
        } catch (PDOException $e) {
            $this->dbh->rollBack();
            return false;
        }
    }

    # Reports
    public function createReport(CreateBaseMessageReply $report): bool
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
        } catch (PDOException $e) {
            $this->dbh->rollBack();
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
