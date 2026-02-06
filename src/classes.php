<?php

class User
{
    public $id;
    public $name;
    public $mail;
}

class Student extends User
{
    public $study_field;
    public $class_year;
}

class Lecturer extends User
{
    public $img_url;
}

class Course
{
    public $id;
    public $lecturer_id;
    public $code;
    public $name;
    private $pin;
}

class Message
{
    public $id;
    public $student_id;
    public $course_id;
    public $created_at;
    public $text;
}

class Comment extends Message
{
    public $message_id;
}

class Report extends Message {
    public $message_id;
}
