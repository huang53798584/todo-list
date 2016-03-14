<?php
require_once ("../util/validators.php");

require_once(__DIR__ . "/../config/config.php");
require_once(__DIR__ . "/../model/domain.php");
require_once(__DIR__ . "/../util/security.php");

if(DATASOURCE_TYPE === DATASOURCE_JSON){
    require_once(__DIR__ . "/../model/json_data_access.php");
} else if(DATASOURCE_TYPE === DATASOURCE_CSV) {
    require_once(__DIR__ . "/../model/csv_data_access.php");
} else if(DATASOURCE_TYPE === DATASOURCE_MYSQL){
    require_once(__DIR__ . "/../model/mysql_data_access.php");
}

function validate_registration_form($form) {
    $errors = [];
    
    $firstName = $form["firstName"];
    $lastName = $form["lastName"];
    $userName = $form["userName"];
    $password = $form["password"];        
    
    $firstNameValid = validate($firstName, 1); //Validate
    if(!$firstNameValid) {
        $errors["firstName"] = "First name is required";
    }
    
    $lastNameValid = validate($lastName, 1); //Validate
    if(!$lastNameValid) {
        $errors["lastName"] = "Last name is required";
    }

    if (!filter_var($userName, FILTER_VALIDATE_EMAIL)) {
        $errors["userName"] = "User name is required and should be a valid email address";
    }

    if (username_already_existed($userName)) {
        $errors["userName"] = "User name has already existed";
    }

    $passwordValid = validate($password, 4); //Validate
    if(!$passwordValid) {
        $errors["password"] = "Password is required and should have at least 4 characters";
    }
        
    return $errors;
}

function username_already_existed($userName) {
    $split = explode("@", $userName);
    $file_name = '../data/'.$split[0].'.json';
    if (get_user_object($userName) || file_exists($file_name)) {
        return true;
    } else return false;
}

?>
