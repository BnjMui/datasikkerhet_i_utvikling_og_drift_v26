<?php
require_once "repository.php";
$repo = new Repository();


print_r($repo->getCourses());
