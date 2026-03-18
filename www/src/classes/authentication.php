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
        $_SESSION = ['login_attempts' => 0, 'user_id' => $user_data->user_id, 'role' => $user_data->role];
        return ['success' => true];
    }
}