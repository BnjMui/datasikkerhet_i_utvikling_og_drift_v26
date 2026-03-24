<?php

class InputValidation {

    // Hentet fra Authentication.php
    public function validateEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Hentet fra Authentication.php
    public function validatePassword(string $password): bool {
        return strlen($password) >= 8;
    }

    // Hentet fra helpers.php (validate_required funksjonen)
    public function validateRequired(array $data, array $fields): array {
        return array_filter($fields, fn($f) => empty(trim($data[$f] ?? '')));
    }

}