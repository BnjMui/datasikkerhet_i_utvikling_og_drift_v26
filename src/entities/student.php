<?php
namespace Entities;
use Entities\User;
class Student extends User
{
    public int $student_id;
    public string $study_field;
    public int $class_year;

    public function __construct(int $id, string $name, string $mail, string $role, string $password, string $study_field, int $class_year)
    {
        parent::__construct($id, $name, $mail, $role, $password);

        $this->student_id = $id;
        $this->study_field = $study_field;
        $this->class_year = $class_year;

    }
}

