<?php

class UserDto
{
    public string $user_id;
    public string $first_name;
    public string $last_name;
    public string $mail;
    public string $role;
}

class StudentDto extends UserDto
{
    public string $study_field;
    public int $class_year;
}

class LecturerDto extends UserDto
{
    public string $avatar;
}
