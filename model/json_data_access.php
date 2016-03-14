<?php
require_once(__DIR__ . "/../config/constants.php");
require_once(__DIR__ . "/domain.php");
error_reporting(E_ALL);

$users_db_file = __DIR__ . "/../data/users.json";


$users_json_string = file_get_contents($users_db_file);
$usersDB = json_decode($users_json_string);

$todosDB = array();

function get_current_user_id(){
	if(session_id() == '' || !isset($_SESSION)) {
	    // session isn't started
	    session_start();
	}

	if(isset($_SESSION[CURRENT_USER])){
		$cusr = $_SESSION[CURRENT_USER];
		$split = explode("@",$cusr);
		return $split[0];
	}
	return false;
}

function save_user_object($user){
	global $users_db_file;
	global $usersDB;
	array_push($usersDB, $user);
	file_put_contents($users_db_file, json_encode($usersDB));
	chmod($users_db_file, 0777);

	// create todos db for the new user
	$todos = array("nextId"=>1, "todos"=>array());

	// Get the data after the @ sign
	$split = explode("@",$user['email']);
	$todo_file_path = __DIR__ . "/../data/".$split[0].".json";
	file_put_contents($todo_file_path, json_encode($todos));
	chmod($todo_file_path, 0777);
}

function get_user_array(){
	return array (
		//map,
		//map
	);
}

function get_user_object($userId){
	global $usersDB;
	$userCount = count($usersDB);
	
	if($userCount > 0) {
		$user = false;
		for($index=0;$index<$userCount;$index++){
			$usr = $usersDB[$index];			
			if($usr->email===$userId){
				//convert $usr to map
				$user = convert_usr_stdclass_to_map($usr);
				break;
			}
		}

		return $user;
	}

	return false;
}

function convert_usr_stdclass_to_map($usr){
	return array(
		user_FIRST_NAME=> $usr->firstName,
		user_LAST_NAME=> $usr->lastName,
		user_EMAIL=> $usr->email,
		user_PASSWORD=> $usr->password,
		user_SALT=> $usr->salt,
		user_TYPE=> $usr->type,
		user_ENABLED=> $usr->enabled
	);
}

function convert_todo_stdclass_to_map($tdo){
	return array(
		todo_ID=> $tdo->todo_ID,
		todo_DESCRIPTION=> $tdo->todo_DESCRIPTION,
		todo_DATE=> $tdo->todo_DATE,
		todo_STATUS=> $tdo->todo_STATUS
	);
}

function init_todos_db(){
	global $todosDB;
	if(!$todosDB){
		$currentUserId = get_current_user_id();
		if(!$currentUserId){
			trigger_error("Please login before trying to access your To Do list");
		}
		$todos_db_file = __DIR__ . "/../data/${currentUserId}.json";
		
		$todos_json_string = file_get_contents($todos_db_file);
		$tmpDB = json_decode($todos_json_string);

		$stdTodos = $tmpDB->todos;
		//print_r($stdTodos);

		$todoCount = count($stdTodos);
		//print_r($todoCount);

		$todosDB = array(
			"nextId"=>$tmpDB->nextId
		);

		$tmpTodos = array();
		for($index=0;$index<$todoCount;$index++){
			$tdo = $stdTodos[$index];
			$todoObj = convert_todo_stdclass_to_map($tdo);
			array_push($tmpTodos, $todoObj);
		}

		$todosDB["todos"] = $tmpTodos;
	}
}



function save_todo_object($todo){
	init_todos_db();
	//write JSON record
	global $todosDB;

	array_push($todosDB['todos'], $todo);
	$todosDB['nextId'] += 1;

	update_data();
}

function get_todo_object($id){
	global $todosDB;
	init_todos_db();
	$todoTasks = $todosDB["todos"];
	return count($todoTasks[$id - 1]) > 0 ? $todoTasks[$id - 1] : array();
}

function update_todo_object($description, $status, $taskId) {
	global $todosDB;
	init_todos_db();

	// update $todosDB
	$todosDB["todos"][$taskId - 1][todo_DESCRIPTION] = $description;
	$todosDB["todos"][$taskId - 1][todo_STATUS] = $status;

	// update data
	update_data();
}

function get_todo_array($user){	
	global $todosDB;
	init_todos_db();	
	return count($todosDB["todos"]) > 0 ? $todosDB["todos"] : array() ;
}

function generate_todo_id(){
	//
	global $todosDB;
	$id = isset($todosDB["nextId"]) ? $todosDB["nextId"] : 0;
	return $id;
}

function delete_todo($taskId) {
	global $todosDB;
	$todoTasks = $todosDB["todos"];
	unset($todoTasks[$taskId - 1]);
	$todosDB["todos"] = $todoTasks;
	$todosDB["nextId"] -= 1;

	update_data();
}

function update_data() {
	global $todosDB;
	$currentUserId = get_current_user_id();
	if(!$currentUserId){
		trigger_error("Please login before trying to access your To Do list");
	}
	$todos_db_file = __DIR__ . "/../data/${currentUserId}.json";
	file_put_contents($todos_db_file, json_encode($todosDB));
	chmod($todos_db_file, 0777);
}

get_todo_array(null);

?>