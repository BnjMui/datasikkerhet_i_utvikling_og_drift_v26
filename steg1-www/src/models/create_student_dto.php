<?php
require_once "create_user_dto.php";
class CreateStudentDto extends CreateUserDto
{
    public string $study_field;
    public int $class_year;
}
