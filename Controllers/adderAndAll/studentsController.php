<?php

require '../../DbCalls/studentsTable.php';
//

if (isset($_POST['addStudent'])) {
    //// add student control.
    $student_id = "";
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = $_POST['phone'];
    //// image null as default.
    $image = null;
    $worked = addStudent($student_id, $name, $email, $phone, $image);
    if (!($worked)) {
        /// something happened in query.
        $returnAfterArray = array(null, "couldn't add student. please try again later.");
        echo json_encode($returnAfterArray);
    } else {
        /// keep going..student created.
        // as email and phone are uniqe in our db , we can use them to grab last student_id for image & courses ,adding
        $lastStudent_id = getLastStudent_id($phone);
        if (!($lastStudent_id == "something happened.")) {
            // modifying array containing object Id For future purose
            $returnAfterArray = array($lastStudent_id, "success.");
            /// check if student did chose any course
            if (count($selectedCourses = json_decode($_POST['selectedCourses'])) > 0) {
                $deal_id = "";
                $worked2 = addDeal($deal_id, $selectedCourses, $lastStudent_id);
                if (!($worked2)) {
                    $returnAfterArray = array(null, "couldn't add courses to the studnet , try again later.");
                    echo json_encode($returnAfterArray);
                };
            };
            ///  now check if needed to add an image.
            // image ends with .jpg - Js responsibillity.
            if (!(isset($_POST['image']))) {
                /// image need to be uploaded to DB.
                /// continue..
                $pre_target_dir = "uploads/students/";
                $pre_target_file = $pre_target_dir . basename($lastStudent_id . $_FILES["image"]['name']);
                //for folders
                $target_dir = "../../uploads/students/";
                $target_file = $target_dir . basename($lastStudent_id . $_FILES["image"]['name']);
                if (move_uploaded_file($_FILES["image"]['tmp_name'], $target_file)) {
                    //// file image shouldv'e added to folder. let's see.
                    /// save new $target_dir in user DB.
                    $response = saveImageDir($lastStudent_id, $pre_target_file);
                    if ($response) {
                        /// finally. in student table.
                        echo json_encode($returnAfterArray);
                    } else {
                        /// oops, student table didn't get dir.
                        $returnAfterArray = array(null, "couldn't add image , but student added successfully.");
                        echo json_encode($returnAfterArray);
                    }
                } else {
                    //// couldn't save image
                    $returnAfterArray = array(null, "couldn't add image , but student added successfully.");
                    echo json_encode($returnAfterArray);
                };
            } else {
                /// user chose to not upload an image so it's done.
                echo json_encode($returnAfterArray);
            }
        } else {
            $returnAfterArray = array(null, "couldn't add courses or image to the studnet , try again later.");
            echo json_encode($returnAfterArray);
        };
    }
}
///////// if asking to get all users so..
elseif (isset($_POST['getAll'])) {
    $studentsArray = getAllStudents();
    echo json_encode($studentsArray);
};
