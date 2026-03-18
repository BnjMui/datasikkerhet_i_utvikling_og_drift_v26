<?php

class Authorization {

    // Sjekker om brukeren er innlogget
    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    // Gammel autentisering via HTTP header
    public function require_auth() {
        if (!isset($_SERVER["HTTP_AUTHENTICATION"])) {
            send_error("Unauthorized", 401);
            exit;
        }
        return $this->authenticate($_SERVER["HTTP_AUTHENTICATION"]);
    }

    private function authenticate($user_token) {
        $repository = new Repository();
        $user_id = $repository->getUserById($user_token);

        if ($user_token == $user_id->user_id) {
            return [
                "authenticated" => true,
                "user_id" => $user_id->user_id,
                "role" => $user_id->role
            ];
        } else {
            send_error("Unauthorized", 401);
        }
    }
}