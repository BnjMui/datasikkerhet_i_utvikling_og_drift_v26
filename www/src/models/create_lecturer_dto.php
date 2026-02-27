<?php
include_once "create_course_dto.php";
include_once "create_user_dto.php";
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
