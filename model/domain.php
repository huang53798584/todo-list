<?php
require_once("../config/constants.php");

function create_user_object($firstName,$lastName,$email,$password,$salt,$type,$enabled=true){
	$user = array (
		"firstName"=>$firstName,
		"lastName"=>$lastName,
		"email"=>$email,
		"password"=>$password,
		"salt"=>$salt,
		"type"=>$type,
		"enabled"=>$enabled
	);

	return $user;
}

function create_todo_object($id,$desc,$date,$status=todo_status_NOT_STARTED){
	$todo = array (
		todo_ID=>$id,
		todo_DESCRIPTION=>$desc,
		todo_DATE=>$date,
		todo_STATUS=>$status
	);

	return $todo;
}

?>