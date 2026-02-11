<?php
namespace Entities;

class User {
    private int $id;
    public string $name;
    public string $mail;
    public string $role;
    private string $password;

    public function __construct(int $id, string $name, string $mail, string $role, string $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->mail = $mail;
        $this->role = $role;
        $this->password = $password | "";
    }
}
