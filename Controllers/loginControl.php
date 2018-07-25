<?php
require '../DbCalls/multipleQuerys.php';
session_start();
//
if (isset($_POST['emailFormatCheck'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
        // wrong email format, can't let user keep going to next stage.
        echo 'wrong email format.';
    };
}
//// email format was ok, let's check DB match.
elseif(isset($_POST['matchMarker'])){
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $TempPassword = $_POST['password'];
    $chemicalX = "p0wer_Puff_oohhhhh@^%#";
    $password = sha1($TempPassword . $chemicalX);
    $objectAdmin = matchMarker($email , $password);
    if(!($objectAdmin === "not exist")){
        /// admin exist, we got an admin!!
        /// lets put inside user a session depends on role :)
        $_SESSION['admin'] = $objectAdmin;
    } else{
        // not a match...
        echo 'failed';
    }
}
