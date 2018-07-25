<?php

require '../../DbCalls/administatorsTable.php';
require_once '../../Models/administator.php';
session_start();
if (isset($_POST['addAdministator'])) {
//    echo 'hi..hehe..';
    //// add student control.
    $administator_id = "";
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    /// adding sha1 and chemical X to password.
    $TempPassword = $_POST['password'];
    $chemicalX = "p0wer_Puff_oohhhhh@^%#";
    $password = sha1($TempPassword . $chemicalX);
    //// image null as default.
    $image = null;
    $worked = addAdministator($administator_id, $name, $phone, $email, $role, $password, $image);
    if (!($worked)) {
        /// something happened in query.
        $returnAfterArray = array(null, "couldn't add admin. please try again later.");
        echo json_encode($returnAfterArray);
    } else {
        /// keep going..student created.
        // as email and phone are uniqe in our db , we can use them to grab last administator_id for image adding
        $lastAdmin_id = getLastAdmin_id($phone);
        if (!($lastAdmin_id == "something happened.")) {
            // modifying array containing object Id For future purose
            $returnAfterArray = array($lastAdmin_id, "success.");
            ///  now check if needed to add an image.
            // image ends with .jpg - Js responsibillity.
            if (!(isset($_POST['image']))) {
                /// image need to be uploaded to DB.
                /// continue..
                $pre_target_dir = "uploads/admins/";
                $pre_target_file = $pre_target_dir . basename($lastAdmin_id . $_FILES["image"]['name']);
                //for folders
                $target_dir = "../../uploads/admins/";
                $target_file = $target_dir . basename($lastAdmin_id . $_FILES["image"]['name']);
                if (move_uploaded_file($_FILES["image"]['tmp_name'], $target_file)) {
                    //// file image shouldv'e added to folder. let's see.
                    /// save new $target_dir in user DB.
                    $response = saveImageDir($lastAdmin_id, $pre_target_file);
                    if ($response) {
                        /// finally. in student table.
                        echo json_encode($returnAfterArray);
                    } else {
                        /// oops, student table didn't get dir.
                        $returnAfterArray = array(null, "couldn't add image , but admin added successfully.");
                        echo json_encode($returnAfterArray);
                    }
                } else {
                    //// couldn't save image
                    $returnAfterArray = array(null, "couldn't add admin , but student added successfully.");
                    echo json_encode($returnAfterArray);
                };
            } else {
                /// user chose to not upload an image so it's done.
                echo json_encode($returnAfterArray);
            }
        } else {
            $returnAfterArray = array(null, "couldn't add image , but admin added successfully.");
            echo json_encode($returnAfterArray);
        };
    }
}
//
/////// if asking to get all users so..
elseif (isset($_POST['getAll'])) {
    $currentAdmin = $_SESSION['admin'];
    /// if owner so let see owner.
    if ($currentAdmin->role === "Owner") {
        $adminsArray = getAllAdmins();
    } else {
        $adminsArray = getAllAdminsExceptOwner();
    };
    echo json_encode($adminsArray);
};
