<?php
require_once 'functions.php';
require_once 'Model/Member.php';
require_once 'framework/Tools.php';
require_once 'framework/Configuration.php';

$member= get_user_or_redirect();

$members = Member::get_members();
$view= new View("members");
$view->show(array("members"=>$members));

?>
