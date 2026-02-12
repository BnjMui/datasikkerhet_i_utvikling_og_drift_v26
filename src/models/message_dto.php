<?php

class MessageDto
{
    public string $message_id;
    public ?string $student_id;
    public string $course_id;
    public string $created_at;
    public string $text;

    public function __construct(string $message_id, ?string $student_id, string $course_id, string $created_at, string $text)
    {
        $this->message_id = $message_id;
        $this->student_id = $student_id;
        $this->course_id = $course_id;
        $this->created_at = $created_at;
        $this->text = $text;
    }
}
