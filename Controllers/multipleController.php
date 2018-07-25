<?php

require '../DbCalls/multipleQuerys.php';
/////check Image file size and etc..
if (isset($_POST['checkImage'])) {
    $type = $_POST['imageType'];
    $size = $_POST['imageSize'];
    ///// check if file is actual image file
    if ($type == "image/jpeg" || $type == "image/jpg" || $type == "image/png" || $type == "image/bmp" || $type == "image/gif") {
        $ok = 1;
    } else {
        $ok = 2;
    };
    // Check file size
    if ($size > 4000000) {
        $ok = 3;
    };
    switch ($ok) {
        case 1:
            echo 'ok';
            break;
        case 2:
            echo 'file type is not an image.';
            break;
        case 3:
            echo 'file is too big. maximum size is 4MB.';
            break;
    }
    ///// check email existence in db
} elseif (isset($_POST['checkEmailFormat'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        /// we know email format is ok, let's check if he already exists in our Db
        $response = checkEmailExistence($email);
        if ($response === "ok") {
            echo "ok";
        } else {
            echo "email already exists.";
        };
    } else {
        echo "wrong email format.";
    }
}
///// check phone existence in db
elseif (isset($_POST['checkPhoneExistence'])) {
    $phone = $_POST['phone'];
    /// we know phone format is ok, let's check if he already exists in our Db
    $response = checkPhoneExistence($phone);
    if ($response === "ok") {
        echo "ok";
    } else {
        echo "phone is already exists.";
    }
}///// check phone existence in db
elseif (isset($_POST['checkCNameExistence'])) {
    $name = $_POST['name'];
    /// let's check if he already exists in our Db
    $response = checkNameExistence($name);
    if ($response === "ok") {
        echo "ok";
    } else {
        echo "course name is already exists.";
    }
}
