<?php
namespace DatasikkerhetG7\Models;

class Message
{
    public string $message_id;
    public ?string $student_id;
    public string $course_id;
    public string $created_at;
    public string $text;

    public array $replies;
    public array $comments;
}
