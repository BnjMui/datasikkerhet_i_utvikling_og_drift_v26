<?php

include "repository.php";
$repo = new Repository();
# 
# $lecturer = new CreateLecturerDto();
# $lecturer->first_name = "Forel";
# $lecturer->last_name = "eser";
# $lecturer->mail = "foreleser1@foreleser.no";
# $lecturer->role = "lecturer";
# $lecturer->password = "usikkert passord";
# 
# $lecturer->avatar = "url/til/profilbilde.png";
# $lecturer->security_question = "Hva er en gulrot?";
# $lecturer->security_answer = "En rund rotgrønnsak";
# 
# $lecturer->course->course_code = "ITF25021-1 26V";
# $lecturer->course->pin_code = 1239;
#  
# print_r($lecturer);
# $repo->createLecturer($lecturer);
$repo->getCourses();
#
