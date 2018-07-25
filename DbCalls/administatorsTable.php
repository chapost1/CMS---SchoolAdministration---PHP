<?php

require 'connection.php';
require '../../Models/administator.php';

////Add An Admin function
/// Gets Admin Properties to add
/// returns if it added to db or not.
function addAdministator($administator_id, $name, $phone, $email, $role, $password, $image) {
    $conn = $GLOBALS['conn'];
    $currentAdmin = new administator($administator_id, $name, $phone, $email, $role, $password, $image);
    $sqlAddAdmin = $conn->prepare("INSERT INTO administators (name, email, phone, role, password, image) VALUES (?, ?, ?, ?,?, ?)");
    $sqlAddAdmin->bind_param("ssssss", $currentAdmin->name, $currentAdmin->email, $currentAdmin->phone, $currentAdmin->role, $currentAdmin->password, $currentStudent->image);
    try {
        $result = $sqlAddAdmin->execute();
        if (!$result) {
            throw new Exception("");
        };
        $worked = true;
    } catch (Exception $e) {
        $worked = false;
    }
    $sqlAddAdmin->close();
    $conn->close();
    return $worked;
}

;

//// get Last Admin ID For adding image if uploaded.
//// happens instantly after adding admin.
// returns id.
function getLastAdmin_id($phone) {
    require 'connection.php';
    /// phone is uniqe globally
    $sqlGetLast = "SELECT * FROM administators WHERE phone ='" . $phone . "'";
    try {
        $result = $conn->query($sqlGetLast);
        if (!$result) {
            throw new Exception("");
        }
        $row = $result->fetch_assoc();
        $row = $row['administator_id'];
    } catch (Exception $e) {
        $row = "something happened.";
    }
    $conn->close();
    return $row;
}

////Get all admins records from db
///makes an array of it
/// returns the array
function getAllAdmins() {
    require 'connection.php';
    $sqlSelectAll = "SELECT * FROM administators ORDER BY FIELD(role, 'Owner') DESC, role";
    try {
        $result = $conn->query($sqlSelectAll);
        if (!$result) {
            throw new Exception("");
        }
        $adminsArray = array();
        while ($row = $result->fetch_assoc()) {
            $currentAdmin = new administator($row['administator_id'], $row['name'], $row['phone'], $row['email'], $row['role'], "censored", $row['image']);
            array_push($adminsArray, $currentAdmin);
        }
    } catch (Exception $e) {
        
    }
    $conn->close();
    return $adminsArray;
}

;

////Get all admins records from db
///makes an array of it
/// returns the array
function getAllAdminsExceptOwner() {
    require 'connection.php';
    $sqlSelectAll = "SELECT * FROM administators WHERE role != 'Owner' ORDER BY FIELD(role, 'Manager') DESC, role";
    try {
        $result = $conn->query($sqlSelectAll);
        if (!$result) {
            throw new Exception("");
        }
        $adminsArray = array();
        while ($row = $result->fetch_assoc()) {
            $currentAdmin = new administator($row['administator_id'], $row['name'], $row['phone'], $row['email'], $row['role'], "censored", $row['image']);
            array_push($adminsArray, $currentAdmin);
        }
    } catch (Exception $e) {
        
    }
    $conn->close();
    return $adminsArray;
}

;

//// save new Image Dir.
/// gets from controller ID and dir
/// reply if worked
function saveImageDir($lastAdmin_id, $pre_target_file) {
    require 'connection.php';
    $sqlUpdateSentence = $conn->prepare("UPDATE administators SET image = ? WHERE administator_id = $lastAdmin_id");
    $sqlUpdateSentence->bind_param("s", $pre_target_file);
    try {
        $result = $sqlUpdateSentence->execute();
        if (!$result) {
            throw new Exception("");
        };
        $worked = true;
    } catch (Exception $e) {
        echo $e->getMessage();
        $worked = false;
    }
    $sqlUpdateSentence->close();
    $conn->close();
    return $worked;
}

;

//// get Student Object to use his details
/// gets from controller ID
/// returns Object
function getAdmin($administator_id) {
    require 'connection.php';
    try {
        $sqlSentence = "SELECT * FROM administators WHERE administator_id = $administator_id";
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        $row = $result->fetch_assoc();
        $conn->close();
        $currentAdmin = new administator($row['administator_id'], $row['name'], $row['phone'], $row['email'], $row['role'], "censored", $row['image']);
        return $currentAdmin;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

;

//
// function deleteStudent
// gets ID 
// return response if worked;
function deleteAdministator($objectID) {
    $conn = $GLOBALS['conn'];
    $sqlDelAdmin = ("DELETE FROM administators WHERE administator_id=" . $objectID . "");
    try {
        $result = $conn->query($sqlDelAdmin);
        if (!$result) {
            throw new Exception("Deleting has failed.");
        };
        $worked = true;
    } catch (Exception $e) {
        $worked = false;
        echo $e->getMessage();
    }
    $conn->close();
    return $worked;
}

;

//
// function Update Admin
// gets ID and all other values
// return response if worked;
function updateAdministator($administator_id, $name, $phone, $email, $role, $password, $image) {
    $conn = $GLOBALS['conn'];
    if ($image === null) {
        if ($password === "no") {
            $sqlUpdateSentence = $conn->prepare("UPDATE administators SET name = ? ,phone = ? ,email = ? ,role = ?  WHERE administator_id = $administator_id");
            $sqlUpdateSentence->bind_param("ssss", $name, $phone, $email, $role);
        } else {
            $sqlUpdateSentence = $conn->prepare("UPDATE administators SET name = ? ,phone = ? ,email = ? ,role = ? , password = ? WHERE administator_id = $administator_id");
            $sqlUpdateSentence->bind_param("sssss", $name, $phone, $email, $role, $password);
        };
    } else {
        if ($password === "no") {
            $sqlUpdateSentence = $conn->prepare("UPDATE administators SET name = ? ,phone = ? ,email = ? ,role = ? , image = ? WHERE administator_id = $administator_id");
            $sqlUpdateSentence->bind_param("sssss", $name, $phone, $email, $role, $image);
        } else {
            $sqlUpdateSentence = $conn->prepare("UPDATE administators SET name = ? ,phone = ? ,email = ? ,role = ? , password = ? , image = ? WHERE administator_id = $administator_id");
            $sqlUpdateSentence->bind_param("ssssss", $name, $phone, $email, $role, $password, $image);
        }
    }
    try {
        $result = $sqlUpdateSentence->execute();
        if (!$result) {
            throw new Exception("");
        };
        $worked = true;
    } catch (Exception $e) {
        $worked = false;
    }
    $conn->close();
    return $worked;
}

//// save new Image Dir.
/// gets from controller ID and dir
/// reply if worked
function removeImage($id) {
    $null = NULL;
    require 'connection.php';
    $sqlUpdateSentence = $conn->prepare("UPDATE administators SET image = ? WHERE administator_id = $id");
    $sqlUpdateSentence->bind_param("s", $null);
    try {
        $result = $sqlUpdateSentence->execute();
        $conn->close();
        if (!$result) {
            throw new Exception("");
        }
        $worked = true;
    } catch (Exception $e) {
        $worked = false;
    }
    return $worked;
}

;

//// get Owner ID to ignore, and email to check if exist in admins table
/// returns if exist
function findAdminToTransfer($id, $email) {
    require 'connection.php';
    try {
        $sqlSentence = "SELECT * FROM administators WHERE administator_id != $id AND email = '$email'";
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        $conn->close();
        if ($result->num_rows > 0) {
            return "1";
        } else {
            return "2";
        };
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

;

/// gets from controller ID and email to transfer ownership to
/// reply if worked
function trasferOwnership($ownerId, $email) {
    require 'connection.php';
    $sqlUpdateSentence1 = $conn->prepare("UPDATE administators SET role = ? WHERE email = '$email'");
    $sqlUpdateSentence2 = $conn->prepare("UPDATE administators SET role = ? WHERE administator_id = $ownerId");
    try {
        $owner = "Owner";
        $manager = "Manager";
        $sqlUpdateSentence1->bind_param("s", $owner);
        $result1 = $sqlUpdateSentence1->execute();
        if (!$result1) {
            throw new Exception("");
        };
        $sqlUpdateSentence2->bind_param("s", $manager);
        $result2 = $sqlUpdateSentence2->execute();
        if (!$result2) {
            throw new Exception("");
        };
        $conn->close();
        return "1";
    } catch (Exception $e) {
        echo $e->getMessage();
        return "2";
    }
}
;
