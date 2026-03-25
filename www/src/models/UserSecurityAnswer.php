<?php
namespace DatasikkerhetG7\Models;

class UserSecurityAnswer extends UserSecurityQuestion
{
    public string $user_id;
    public string $mail;
    public string $security_answer;
}
