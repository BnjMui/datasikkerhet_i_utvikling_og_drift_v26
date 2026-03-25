<?php
namespace DatasikkerhetG7\Models;

class CreateLecturerDto extends CreateUserDto
{
    public string $avatar;
    public string $security_question;
    public string $security_answer;
    public CreateCourseDto $course;

    public function __construct()
    {
        $this->course = new CreateCourseDto();
    }
}
