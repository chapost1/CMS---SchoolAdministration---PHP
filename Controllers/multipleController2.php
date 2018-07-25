<?php

require '../DbCalls/multipleQuerys.php';
// check student email existance and format
if (isset($_POST['checkStudentEmailExistence'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        /// we know email format is ok, let's check if he already exists in our Db
        $objectID = $_POST['objectID'];
        $response = checkStudentEmailExistence($email, $objectID);
        if ($response === "ok") {
            echo "ok";
        } else {
            echo "email already exists.";
        };
    } else {
        echo "wrong email format.";
    }
    // check admin email existance and format
} elseif (isset($_POST['checkAdministatorEmailExistence'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        /// we know email format is ok, let's check if he already exists in our Db
        $objectID = $_POST['objectID'];
        $response = checkAdministatorEmailExistence($email, $objectID);
        if ($response === "ok") {
            echo "ok";
        } else {
            echo "email already exists.";
        };
    } else {
        echo "wrong email format.";
    }
}
///// check student phone existence in db
elseif (isset($_POST['checkStudentPhoneExistence'])) {
    $objectID = $_POST['objectID'];
    $phone = $_POST['phone'];
    /// we know phone format is ok, let's check if he already exists in our Db
    $response = checkStudentPhoneExistence($phone, $objectID);
    if ($response === "ok") {
        echo "ok";
    } else {
        echo "phone is already exists.";
    }
}
///// check admin phone existence in db
elseif (isset($_POST['checkAdministatorPhoneExistence'])) {
    $objectID = $_POST['objectID'];
    $phone = $_POST['phone'];
    /// we know phone format is ok, let's check if he already exists in our Db
    $response = checkAdministatorPhoneExistence($phone, $objectID);
    if ($response === "ok") {
        echo "ok";
    } else {
        echo "phone is already exists.";
    }
}///// check phone existence in db
elseif (isset($_POST['checkCNameExistenceExceptWho'])) {
    $objectID = $_POST['objectID'];
    $name = $_POST['name'];
    /// let's check if he already exists in our Db
    $response = checkCNameExistenceExceptWho($name, $objectID);
    if ($response === "ok") {
        echo "ok";
    } else {
        echo "course name is already exists.";
    }
}
////elseif get all deals
elseif (isset($_POST['getAllDeals'])) {
    $dealsArray = getAllDeals();
    if ((is_array($dealsArray))) {
        echo json_encode($dealsArray);
    } elseif($dealsArray === "1") {
        //error occured
        echo $dealsArray;
    }elseif ($dealsArray === "2") {
        ///no deals :O empty
        echo $dealsArray;
    }
}