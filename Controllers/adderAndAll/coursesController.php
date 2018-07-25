<?php

require '../../DbCalls/coursesTable.php';
if (isset($_POST['addCourse'])) {
    //// add course control.
    $course_id = "";
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    //// image null as default.
    $image = null;
    $worked = addCourse($course_id, $name, $desc, $image);
    if (!($worked)) {
        /// something happened in query.
        $returnAfterArray = array(null, "couldn't add course. please try again later.");
        echo json_encode($returnAfterArray);
    } else {
        //// name is uniqe in course case.. so we can get course_id from db by asking for name
        $lastCourse_id = getLastCourse_id($name);
        if (!($lastCourse_id == "something happened.")) {
            // modifying array containing object Id For future purose
            $returnAfterArray = array($lastCourse_id, "success.");
            /// course added, now check if needed to add an image.
            if (!(isset($_POST['image']))) {
                /// image need to be uploaded to DB.
                /// continue..
                //// now we have the student ID we can choose name to save the image with.
                /// for db
                $pre_target_dir = "uploads/courses/";
                $pre_target_file = $pre_target_dir . basename($lastCourse_id . $_FILES["image"]['name']);
                //for folders
                $target_dir = "../../uploads/courses/";
                $target_file = $target_dir . basename($lastCourse_id . $_FILES["image"]['name']);
                if (move_uploaded_file($_FILES["image"]['tmp_name'], $target_file)) {
                    //// file image shouldv'e added to folder. let's see.
                    /// save new $target_dir in user DB.
                    $response = saveImageDir($lastCourse_id, $pre_target_file);
                    if ($response) {
                        /// finally. in student table.
                        echo json_encode($returnAfterArray);
                    } else {
                        /// oops, student table didn't get dir.
                        $returnAfterArray = array(null, "couldn't add image , but course added successfully.");
                        echo json_encode($returnAfterArray);
                    }
                } else {
                    //// couldn't save image
                    $returnAfterArray = array(null, "couldn't add image , but course added successfully.");
                    echo json_encode($returnAfterArray);
                };
            } else {
                /// user chose to not upload an image so it's done.
                echo json_encode($returnAfterArray);
            }
        } else {
            $returnAfterArray = array(null, "error: couldn't add image to the course , try again later.");
            echo json_encode($returnAfterArray);
        };
    }
}
///////// if asking to get all courses for showing so..
elseif (isset($_POST['getAll'])) {
    $coursesArray = getAllCourses();
    echo json_encode($coursesArray);
}
///////// if asking to get all courses for chhosing so..
elseif (isset($_POST['getAllChoose'])) {
    $which = $_POST['which'];
    $coursesArray = getAllCourses();
    $arrayToReturn = array($coursesArray,$which);
    echo json_encode($arrayToReturn);
}
///////// if asking to get all courses for chhosing so..
elseif (isset($_POST['getAllChooseAndKeep'])) {
    $which = $_POST['which'];
    $objectToKeep = $_POST['objectToKeep'];
    $coursesArray = getAllCourses();
    $arrayToReturn = array($coursesArray,$which,$objectToKeep);
    echo json_encode($arrayToReturn);
};