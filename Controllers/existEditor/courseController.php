<?php

require '../../DbCalls/coursesTable.php';
if (isset($_POST['deleteObject'])) {
    /// the purpose is to create a student object with all courses needed details for showing.
//// getting vars
    $objectID = $_POST['objectID'];
    $imageDir = $_POST['imageDir'];
    $which = $_POST['which'];
    if (!($imageDir === "" || $imageDir === null)) {
        $imageDir = '../../' . $_POST['imageDir'];
        /// image may exist, delete it;
        // Create the user folder if missing
        if (!file_exists($imageDir)) {
            @mkdir($imageDir, 0777, false);
        };
        // If the user file already exists, delete it
        if (file_exists($imageDir)) {
            try {
                $delete = @unlink($imageDir);
                if (!($delete)) {
                    // something is probably wrong with path :O;
                    throw new Exception("1");
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        };
    };
// this function is going to delete the student and all the deals he had with courses.
    $response = deleteCourse($objectID);
    if (!($response)) {
        $sendArray = array($which, "deleting failed");
        echo json_encode($sendArray);
    } else {
        $sendArray = array($which, "success");
        echo json_encode($sendArray);
    }
//
    //// update object
} elseif (isset($_POST['edit'])) {
    /// grab details
    $objectID = $_POST['objectID'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    if (!(isset($_POST['image']))) {
        try {
            /// image need to be deleted and uploaded.
            $preImgDir = "uploads/courses/" . $objectID;
            $imageDir = "../../".$preImgDir;
            /// image may exist, delete it;
            if (file_exists($imageDir)) {
                $delete = @unlink($imageDir);
            };
            if (!(move_uploaded_file($_FILES['image']['tmp_name'], $imageDir . $_FILES['image']['name']))) {
                throw new Exception("");
            };
        } catch (Exception $e) {
            //means upload failed;
            echo "";
        };
        $preImgDir = $preImgDir . ".jpg";
    } else {
        $preImgDir = null;
    };
    ////
    $response = updateCourse($objectID, $name, $desc, $preImgDir);
//
    if ($response) {
        // update happened, left to add courses.
        //worked
        //lets get now course object to show user.
        $returnArray = array($objectID, 'success.');
        echo json_encode($returnArray);
    } else {
        $returnArray = array($objectID, "Couldn't Fetch Update, Please try again later.");
        echo json_encode($returnArray);
    };
}
/// removeImage
elseif (isset($_POST['removeExistingImage'])) {
    $id = $_POST['id'];
    $yes = removeImage($id);
    if ($yes) {
        $imageDir = "../../uploads/courses/".$id.".jpg";
        if (!file_exists($imageDir)) {
            @mkdir($imageDir, 0777, false);
        };
        // If the user file already exists, delete it
        if (file_exists($imageDir)) {
            try {
                $delete = @unlink($imageDir);
                if (!($delete)) {
                    throw new Exception("");
                }
            } catch (Exception $e) {
                
            };
        };
        //that's it image should've been deleted.
    };
};
