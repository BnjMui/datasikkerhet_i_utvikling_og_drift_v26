<?php

class UserLoginDto
{
    public ?string $user_id;
    public string $mail;
    public string $password;

    public function __construct(?string $user_id, string $mail, string $password)
    {
        $this->user_id = $user_id;
        $this->mail = $mail;
        $this->password = $password;
    }
}
