<?php

include "models/user_login_dto.php";
include "models/create_lecturer_dto.php";
include "repository.php";
$repo = new Repository();
# 
#  $lecturer = new CreateLecturerDto();
#  $lecturer->first_name = "Forel";
#  $lecturer->last_name = "eser";
#  $lecturer->mail = "foreleser2@foreleser.no";
#  $lecturer->role = "lecturer";
#  $lecturer->password = "ekstra usikkert passord";
# # 
#  $lecturer->avatar = "url/til/profilbilde.png";
#  $lecturer->security_question = "Hva er en gulrot?";
#  $lecturer->security_answer = "En rund rotgrønnsak";
# # 
#  $lecturer->course->course_code = "IRF25021-1 26V";
#  $lecturer->course->pin_code = 1239;
# #  
#  print_r($lecturer);
#  $repo->createLecturer($lecturer);
print_r( $repo->getMessages(2));
#
# $user_login = new UserLoginDto();
# 
# $user_login->mail = "j@mail.test";
# 
# $repo->checkUserLoginByMail($user_login);
#
