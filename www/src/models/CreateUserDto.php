<?php
namespace DatasikkerhetG7\Models;

class CreateUserDto
{
    public string $first_name;
    public string $last_name;
    public string $mail;
    public string $role;
    public string $password;
    public array $security_questions;
}
