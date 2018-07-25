<?php

require '../../DbCalls/administatorsTable.php';
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
    $response = deleteAdministator($objectID);
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
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    ///check if updating password is needed
    if (!($_POST['password'] === "no")) {
        /// adding sha1 and chemical X to password.
        $TempPassword = $_POST['password'];
        $chemicalX = "p0wer_Puff_oohhhhh@^%#";
        $password = sha1($TempPassword . $chemicalX);
    } else {
        $password = "no";
    };
    if (!(isset($_POST['image']))) {
        try {
            /// image need to be deleted and uploaded.
            $preImgDir = "uploads/admins/" . $objectID;
            $imageDir = "../../" . $preImgDir;
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
    $response = updateAdministator($objectID, $name, $phone, $email, $role, $password, $preImgDir);

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
        $imageDir = "../../uploads/admins/".$id.".jpg";
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
}
//// get Admin To Transfer Ownership
elseif(isset ($_POST['getAdminToTransfer'])){
    $id = $_POST['id'];
    $email = filter_var(filter_var($_POST['email'] ,FILTER_SANITIZE_EMAIL), FILTER_SANITIZE_STRING);
    //
    $response = findAdminToTransfer($id , $email);
    echo $response;
}
//// trasfer Ownership to selected admin
elseif(isset($_POST['transferOwnership'])){
    $previousOwnerid = $_POST['previousOwnerid'];
    $newOwnerEmail = $_POST['newOwnerEmail'];
    $worked = trasferOwnership($previousOwnerid, $newOwnerEmail);
    echo $worked;
};
