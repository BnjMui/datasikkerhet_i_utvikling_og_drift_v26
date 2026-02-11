<?php

include "repository.php";
$repo = new Repository();

$lecturer = new CreateLecturerDto();
$lecturer->first_name = "Tom H.";
$lecturer->last_name = "N";
$lecturer->mail = "thn@foreleser.no";
$lecturer->role = "lecturer";
$lecturer->password = "sikekrt passord";

$lecturer->avatar = "url/til/profilbilde.png";
$lecturer->security_question = "Hva er en potet?";
$lecturer->security_answer = "En rund rotgrønnsak";

$repo->createLecturer($lecturer);
