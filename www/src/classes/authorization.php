<?php

class Authorization {

    // Sjekker om brukeren er innlogget ved å se om user_id finnes i session
    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    // Sjekker om brukeren har en spesifikk rolle (student, lecturer eller admin), returnerer false hvis rolle ikke finnes i session
    public function hasRole(string $role): bool {
        return ($_SESSION['role'] ?? null) === $role;
    }

    // Stopper execution hvis brukeren ikke har tilgang
    public function requireRole(string $role): void {
    if (!$this->isLoggedIn() || !$this->hasRole($role)) {
        http_response_code(403);
        echo json_encode(['error' => 'Ingen tilgang']);
        exit;
    }
}
}