<?php
require_once "repository.php";
$repo = new Repository();


print_r($repo->getSecurityQuestionByMail("foreleser1@foreleser.no"));
