<?php
class CreateMessageDto
{
    public string $student_id;
    public int $course_id;
    public ?string $created_at = null;
    public string $text;

    # public function __construct(string $student_id, int $course_id, ?string $created_at, string $text)
    # {
    #     $this->student_id = $student_id;
    #     $this->course_id = $course_id;
    #     $this->created_at = $created_at;
    #     $this->text = $text;
    # }
}
