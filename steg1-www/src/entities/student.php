<?php
include "user.php";

class Student extends User
{
    public int $student_id;
    public string $study_field;
    public int $class_year;

    public function __construct(string $id, string $first_name, string $last_name, string $mail, string $role, string $password, string $study_field, int $class_year)
    {
        parent::__construct($id, $first_name, $last_name, $mail, $role, $password);

        $this->student_id = $id;
        $this->study_field = $study_field;
        $this->class_year = $class_year;

    }
}


class StudentNoId extends UserNoId
{
    public string $study_field;
    public int $class_year;

    public function __construct(string $first_name, string $last_name, string $mail, string $role, string $password, string $study_field, int $class_year)
    {
        parent::__construct($first_name, $last_name, $mail, $role, $password);

        $this->study_field = $study_field;
        $this->class_year = $class_year;

    }
}
