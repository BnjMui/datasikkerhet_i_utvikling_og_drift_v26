<?php

require_once "models/user_login_dto.php";
require_once "models/create_lecturer_dto.php";
require_once "repository.php";
$repo = new Repository();


# # Create lecturer
# $lecturer = new CreateLecturerDto();
# $lecturer->first_name = "Kar";
# $lecturer->last_name = "Mannen";
# $lecturer->mail = "fore@foreleser.no";
# $lecturer->role = "lecturer";
# $lecturer->password = "ekstra usikkert passord";
# 
# $lecturer->avatar = "url/til/profilbilde.png";
# $lecturer->security_question = "Hva er en gulrot?";
# $lecturer->security_answer = "En rund rotgrønnsak";
# 
# $lecturer->course->course_code = "IRF27021-1 26V";
# $lecturer->course->pin_code = 1239;
# 
# echo "create_lecturer: " . $repo->createLecturer($lecturer);
# echo "\n";
# # Create student
# $student = new CreateStudentDto();
# 
# $student->first_name = "Studenten Jonas";
# $student->last_name = "Mannen";
# $student->mail = "stud@ent.no";
# $student->role = "student";
# $student->password = "ekstra usikkert passord";
# 
# $student->study_field = "Kjedelig studie";
# $student->class_year = 2025;
# 
# echo "create_student: ";
# print_r($repo->createStudent($student));
# echo "\n";
# 
 # GetUserByMail
 $fetchedUserStudent = $repo->getUserByMail("stud@ent.no");
 $fetchedLecturer = $repo->getUserByMail("fore@foreleser.no");
 echo "getuserbymail: ";
 print_r($fetchedUserStudent);
 echo "\n";
 # GetUserLoginInfo
 echo "getUserLoginInfo: ";
 print_r($repo->getUserLoginInfo("stud@ent.no"));
 echo "\n";

#
# UpdatePasswordByUserId
echo "UpdatePasswordByUserId";
$repo->updatePasswordByUserId($fetchedUserStudent->user_id, "Nytt Passord!!!");
$fetchedUserStudent = $repo->getUserByMail("stud@ent.no");

print_r($fetchedUserStudent->password);
echo "\n";
# GetStudentDataById
echo "GetStudentDataById";
print_r($repo->getStudentDataById($fetchedUserStudent->user_id));
echo "\n";
# GetLecturerDataById
echo "GetLecturerDataById";
print_r($repo->getLecturerDataById($fetchedLecturer->user_id));
echo "\n";

# Get Courses
echo "Get courses";
print_r($repo->getCourses());
echo "\n";

# GetCourseById
echo "GetCourseById";
print_r($repo->getCourseById(1));
echo "\n";
# CreateCourse
# AddCourseToStudent
# echo "AddCourseToStudent";
# $repo->addCourseToStudent($fetchedUserStudent->user_id, 1);
# echo "\n";
# GetStudentCourses
echo "Get Students Courses";
print_r($repo->getStudentCourses($fetchedUserStudent->user_id));
echo "\n";
# CreateMessage
# GetMessages
# CreateReply
# GetReplies
# CreateComment
# GetComments
# CreateReport
