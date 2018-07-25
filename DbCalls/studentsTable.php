<?php

require 'connection.php';
require '../../Models/student.php';
require '../../Models/course.php';
require_once '../../Models/deal.php';


//
// add a deal Function
// gets inputs courses selected by student
// do it.
function addDeal($deal_id, $selectedCourses, $student_id) {
    require 'connection.php';
    foreach ($selectedCourses as $deal => $course_id) {
        $currentDeal = new deal($deal_id, $course_id, $student_id);
        $sqlAddDeal = $conn->prepare("INSERT INTO deals (course_id, student_id) VALUES (?, ?)");
        $sqlAddDeal->bind_param("ss", $currentDeal->course_id, $currentDeal->student_id);
        $result = $sqlAddDeal->execute();
        $sqlAddDeal->close();
    }
    $conn->close();
    if ((count($selectedCourses) > 0)) {
        return $result;
    } else{
        return true;
    }
}
//
////Add A Student function
/// Gets Student Properties to add
/// returns if it added to db or not.
function addStudent($student_id, $name, $email, $phone, $image) {
    $conn = $GLOBALS['conn'];
    // to unable construct crash.. courses is undefined yet..
    $courses = "";
    $currentStudent = new student($student_id, $name, $phone, $email, $image, $courses);
    $sqlAddStudent = $conn->prepare("INSERT INTO students (name, email, phone, image) VALUES (?, ?, ?, ?)");
    $sqlAddStudent->bind_param("ssss", $currentStudent->name, $currentStudent->email, $currentStudent->phone, $currentStudent->image);
    try {
        $result = $sqlAddStudent->execute();
        if (!$result) {
            throw new Exception("");
        };
        $worked = true;
    } catch (Exception $e) {
        $worked = false;
    }
    $sqlAddStudent->close();
    $conn->close();
    return $worked;
}

;

//// get Last Student ID For adding image if uploaded.
//// happens instantly after adding student.
// returns id.
function getLastStudent_id($phone) {
    require 'connection.php';
    /// phone is uniqe globally
    $sqlGetLast = "SELECT * FROM students WHERE phone ='" . $phone . "'";
    try {
        $result = $conn->query($sqlGetLast);
        if (!$result) {
            throw new Exception("");
        }
        $row = $result->fetch_assoc();
        $row = $row['student_id'];
    } catch (Exception $e) {
        $row = "something happened.";
    }
    $conn->close();
    return $row;
}

////Get all students records from db
///makes an array of it
/// returns the array
function getAllStudents() {
    require 'connection.php';
    $sqlSelectAll = "SELECT * FROM students";
    try {
        $result = $conn->query($sqlSelectAll);
        if (!$result) {
            throw new Exception("");
        }
        $studentsArray = array();
        while ($row = $result->fetch_assoc()) {
            $currentStudent = new student($row['student_id'], $row['name'], $row['phone'], $row['email'], $row['image'], "");
            array_push($studentsArray, $currentStudent);
        }
    } catch (Exception $e) {
        
    }
    $conn->close();
    return $studentsArray;
}

;

//// save new Image Dir.
/// gets from controller ID and dir
/// reply if worked
function saveImageDir($lastStudent_id, $pre_target_file) {
    require 'connection.php';
    $sqlUpdateSentence = $conn->prepare("UPDATE students SET image = ? WHERE student_id = $lastStudent_id");
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
function getStudentAndCourses($student_id) {
    require 'connection.php';
    // checkout deals
    $sqlSentence = "SELECT * FROM deals WHERE student_id = $student_id";
    try {
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        /// get courses
        $coursesArray = array();
        while ($row = $result->fetch_assoc()) {
            $currentCid = $row['course_id'];
            $sqlSentence2 = "SELECT * FROM courses WHERE course_id = $currentCid";
            $resultCourse = $conn->query($sqlSentence2);
            $rowCourse = $resultCourse->fetch_assoc();
            $currentCourse = new course($rowCourse['course_id'], $rowCourse['name'], $rowCourse['description'], $rowCourse['image'], "");
            array_push($coursesArray, $currentCourse);
        }
        // and finally student
        $sqlSentence3 = "SELECT * FROM students WHERE student_id = $student_id";
        $resultStudent = $conn->query($sqlSentence3);
        if (!$resultStudent) {
            throw new Exception("");
        };
        $rowStudent = $resultStudent->fetch_assoc();
        $conn->close();
        $currentStudent = new student($rowStudent['student_id'], $rowStudent['name'], $rowStudent['phone'], $rowStudent['email'], $rowStudent['image'], $coursesArray);
        return $currentStudent;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

;

//
// function deleteStudent
// gets ID 
// return response if worked;
function deleteStudent($objectID) {
    $conn = $GLOBALS['conn'];
    $sqlDelStudent = ("DELETE FROM students WHERE student_id=" . $objectID . "");
    $sqlDelDeals = ("DELETE FROM deals WHERE student_id=" . $objectID . "");
    try {
        $result = $conn->query($sqlDelStudent);
        if (!$result) {
            throw new Exception("Deleting has failed.");
        };
        $worked = true;
        $result2 = $conn->query($sqlDelDeals);
        if (!$result2) {
            throw new Exception("");
        };
    } catch (Exception $e) {
        $worked = false;
        echo $e->getMessage();
    }
    $conn->close();
    return $worked;
}

;

//
// function Update Student
// gets ID and all other values
// return response if worked;
function updateStudent($objectID, $name, $email, $phone, $imageDir, $selectedCourses) {
    $conn = $GLOBALS['conn'];
    $sqlDelDeals = ("DELETE FROM deals WHERE student_id=" . $objectID . "");
    if ($imageDir === null) {
        $sqlUpdateSentence = $conn->prepare("UPDATE students SET name = ? , email = ? , phone = ? WHERE student_id = $objectID");
        $sqlUpdateSentence->bind_param("sss", $name, $email, $phone);
    } else {
        $sqlUpdateSentence = $conn->prepare("UPDATE students SET name = ? , email = ? , phone = ? , image = ? WHERE student_id = $objectID");
        $sqlUpdateSentence->bind_param("ssss", $name, $email, $phone, $imageDir);
    }
    try {
        $result = $sqlUpdateSentence->execute();
        if (!$result) {
            throw new Exception("");
        };
        $worked = true;
        $result2 = $conn->query($sqlDelDeals);
        if (!$result2) {
            throw new Exception("");
        };
    } catch (Exception $e) {
        $worked = false;
    }
    $conn->close();
    return $worked;
}

;


//// save new Image Dir.
/// gets from controller ID and dir
/// reply if worked
function removeImage($id) {
    $null = NULL;
    require 'connection.php';
    $sqlUpdateSentence = $conn->prepare("UPDATE students SET image = ? WHERE student_id = $id");
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