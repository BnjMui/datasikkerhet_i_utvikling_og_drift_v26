<?php
namespace DatasikkerhetG7\Models;

class CreateStudentDto extends CreateUserDto
{
    public string $study_field;
    public int $class_year;
}
