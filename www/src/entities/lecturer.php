<?php
include "user.php";

class Lecturer extends User
{
    public string $avatar;
    public string $security_question;
    public string $security_answer;
}
