<?php

class UserDto
{
    public string $user_id;
    public string $first_name;
    public string $last_name;
    public string $mail;
    public string $role;
}

class ExtendedUserDto extends UserDto
{
    public StudentDataDto|LecturerDataDto $extended_data;
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

class StudentDataDto
{
    public string $study_field;
    public int $class_year;
}

class LecturerDataDto {
    public string $avatar;
    public string $security_question;
    public string $security_answer;
}
