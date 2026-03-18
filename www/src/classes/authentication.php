<?php

class Authentication {
    private $repository;

    public function __construct($repository) {
        $this->repository = $repository;
    }

    public function login(string $mail, string $password): array {
        // Brute force, sjekk antall forsøk
      $_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
      $_SESSION['last_attempt'] = $_SESSION['last_attempt'] ?? time();

        if ($_SESSION['login_attempts'] >= 5 && time() - $_SESSION['last_attempt'] < 900) {
            return ['success' => false, 'message' => 'For mange forsøk, prøv igjen senere'];
        }

        $user_data = $this->repository->getUserLoginInfo($mail);

        // Generisk feilmelding uansett om mail eller passord er feil
        if (!$user_data || !password_verify($password, $user_data->password)) {
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt'] = time();
            return ['success' => false, 'message' => 'Ugyldig e-post eller passord'];
        }

        // Ny session-ID ved innlogging
        session_regenerate_id(true);
        $_SESSION['login_attempts'] = 0;
        $_SESSION['session_data'] = $user_data;
        return ['success' => true];
    }

    // Registrere ny bruker - logikk hentet fra register/index.php
 public function register(array $data): array {
    if (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email'];
    }

    if (strlen($data['password']) < 8) {
        return ['success' => false, 'message' => 'Password must be atleast 8 characters'];
    }

    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

    switch ($data["role"]) {
        case 'student':
            $user_object = new CreateStudentDto();
            $user_object->first_name = $data["first_name"];
            $user_object->last_name = $data["last_name"];
            $user_object->mail = $data["mail"];
            $user_object->password = $hashedPassword;
            $user_object->role = $data["role"];
            $user_object->study_field = $data["study_field"];
            $user_object->class_year = $data["class_year"];
            return ['success' => $this->repository->createStudent($user_object)];

        case 'lecturer':
            $user_object = new CreateLecturerDto();
            $hashed_security_answer = password_hash($data["security_answer"], PASSWORD_BCRYPT);
            $user_object->first_name = $data["first_name"];
            $user_object->last_name = $data["last_name"];
            $user_object->mail = $data["mail"];
            $user_object->password = $hashedPassword;
            $user_object->role = $data["role"];
            $user_object->avatar = $data["avatar"];
            $user_object->security_question = $data["security_question"];
            $user_object->security_answer = $hashed_security_answer;
            $user_object->course->course_code = $data["course_code"];
            $user_object->course->course_name = $data["course_name"];
            $user_object->course->pin_code = $data["pin_code"];
            return ['success' => $this->repository->createLecturer($user_object)];

        default:
            return ['success' => false, 'message' => 'Ugyldig rolle'];
    }
}

    // Glemt passord - logikk hentet fra forgot_password/index.php
    public function forgotPassword(string $mail, string $security_answer, string $new_password): array {
        $security_data = $this->repository->getSecurityAnswerByMail($mail);

        if (!$security_data || !password_verify($security_answer, $security_data["security_answer"])) {
            return ['success' => false, 'message' => 'Answer is not correct'];
        }

        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $this->repository->updatePasswordByUserId($security_data["user_id"], $hashed_password);

        return ['success' => true];
    }

    // Bytte passord - logikk hentet fra update_password/index.php
    public function changePassword(string $new_password): array {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $success = $this->repository->updatePasswordByUserId($_SESSION['session_data']->user_id, $hashed_password);
        return ['success' => $success];
    }


}