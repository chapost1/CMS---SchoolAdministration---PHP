<?php

require 'connection.php';
require '../../Models/course.php';
require '../../Models/student.php';

////Add A Course function
/// Gets Course Properties to add
/// returns if it added to db or not.
function addCourse($course_id, $name, $desc, $image) {
    $conn = $GLOBALS['conn'];
    // to unable construct crash.. students is undefined yet..
    $students = "";
    $currentCourse = new course($course_id, $name, $desc, $image , $students);
    $sqlAddCourse = $conn->prepare("INSERT INTO Courses (name, description, image) VALUES (?, ?, ?)");
    $sqlAddCourse->bind_param("sss", $currentCourse->name, $currentCourse->desc, $currentCourse->image);
    try {
        $result = $sqlAddCourse->execute();
        if (!$result) {
            throw new Exception("");
        };
        $worked = true;
    } catch (Exception $e) {
        $worked = false;
    }
    $sqlAddCourse->close();
    $conn->close();
    return $worked;
}

;

//// get Last Course ID For adding image if uploaded.
//// happens instantly after adding course.
// returns id.
function getLastCourse_id($name) {
    require 'connection.php';
    /// course name is uniqe tabeleyy
    $sqlGetLast = "SELECT * FROM courses WHERE name ='" . $name . "'";
    try {
        $result = $conn->query($sqlGetLast);
        if (!$result) {
            throw new Exception("");
        }
        $row = $result->fetch_assoc();
        $row = $row['course_id'];
    } catch (Exception $e) {
        $row = "something happened.";
    }
    $conn->close();
    return $row;
}

////Get all courses records from db
///makes an array of it
/// returns the array
function getAllCourses() {
    require 'connection.php';
    $sqlSelectAll = "SELECT * FROM courses";
    try {
        $result = $conn->query($sqlSelectAll);
        if (!$result) {
            throw new Exception("");
        }
        $coursesArray = array();
        while ($row = $result->fetch_assoc()) {
            $currentCourse = new course($row['course_id'], $row['name'], $row['description'], $row['image'] , "");
            array_push($coursesArray, $currentCourse);
        }
    } catch (Exception $e) {
        
    }
    $conn->close();
    return $coursesArray;
}

;

//// save new Image Dir.
/// gets from controller ID and dir
/// reply if worked
function saveImageDir($lastCourse_id, $pre_target_file) {
    require 'connection.php';
    $sqlUpdateSentence = $conn->prepare("UPDATE courses SET image = ? WHERE course_id = $lastCourse_id");
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

//// get Course Object to use his details
/// gets from controller ID
/// returns Object
function getCoursesAndStudents($course_id) {
    require 'connection.php';
    /// checkout deals
    $sqlSentence = "SELECT * FROM deals WHERE course_id = $course_id";
    try {
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        /// get students 
        $studentsArray = array();
        while ($row = $result->fetch_assoc()) {
            $courseSid = $row['student_id'];
            $sqlSentence2 = "SELECT * FROM students WHERE student_id = $courseSid";
            $resultStudent = $conn->query($sqlSentence2);
            $rowStudent = $resultStudent->fetch_assoc();
            $currentStudent = new student($rowStudent['student_id'], $rowStudent['name'], $rowStudent['phone'], $rowStudent['email'], $rowStudent['image'], "");
            array_push($studentsArray, $currentStudent);
        }
        // and finally our course
        $sqlSentence3 = "SELECT * FROM courses WHERE course_id = $course_id";
        $resultCourse = $conn->query($sqlSentence3);
        if (!$resultCourse) {
            throw new Exception("");
        };
        $rowCourse = $resultCourse->fetch_assoc();
        $conn->close();
        $currentCourse = new course($rowCourse['course_id'], $rowCourse['name'], $rowCourse['description'], $rowCourse['image'] , $studentsArray);
        return $currentCourse;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

;
//
// function deleteCourse
// gets ID 
// return response if worked;
function deleteCourse($objectID) {
    $conn = $GLOBALS['conn'];
    $sqlDelCourse = ("DELETE FROM courses WHERE course_id=" . $objectID . "");
    try {
        $result = $conn->query($sqlDelCourse);
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
// function Update Course
// gets ID and all other values
// return response if worked;
function updateCourse($objectID, $name, $desc, $imageDir) {
    $conn = $GLOBALS['conn'];
    if ($imageDir === null) {
        $sqlUpdateSentence = $conn->prepare("UPDATE courses SET name = ? , description = ? WHERE course_id = $objectID");
        $sqlUpdateSentence->bind_param("ss", $name, $desc);
    } else {
        $sqlUpdateSentence = $conn->prepare("UPDATE courses SET name = ? , description = ? , image = ? WHERE course_id = $objectID");
        $sqlUpdateSentence->bind_param("sss", $name, $desc, $imageDir);
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

;


//// save new Image Dir.
/// gets from controller ID and dir
/// reply if worked
function removeImage($id) {
    $null = NULL;
    require 'connection.php';
    $sqlUpdateSentence = $conn->prepare("UPDATE courses SET image = ? WHERE course_id = $id");
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