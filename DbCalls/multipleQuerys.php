<?php

require 'connection.php';
require '../Models/administator.php';
require '../Models/tableDeal.php';

//function check email existance
// gets email
// run it on db potentialic tables
function checkEmailExistence($email) {
    $conn = $GLOBALS['conn'];

    try {
        $sqlSentence = "SELECT * FROM students WHERE email ='" . $email . "'";
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        if ($result->num_rows > 0) {
            $conn->close();
            return "exist";
        } else {
            $sqlSentence2 = "SELECT * FROM administators WHERE email ='" . $email . "'";
            $result = $conn->query($sqlSentence2);
            $conn->close();
            if ($result->num_rows > 0) {
                return "exist";
            } else {
                return "ok";
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

//function check phone existance
// gets email
// run it on db potentialic tables
function checkPhoneExistence($phone) {
    $conn = $GLOBALS['conn'];

    try {
        $sqlSentence = "SELECT * FROM students WHERE phone ='" . $phone . "'";
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        if ($result->num_rows > 0) {
            $conn->close();
            return "exist";
        } else {
            $sqlSentence2 = "SELECT * FROM administators WHERE phone ='" . $phone . "'";
            $result = $conn->query($sqlSentence2);
            $conn->close();
            if ($result->num_rows > 0) {
                return "exist";
            } else {
                return "ok";
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

//function check course name existance
// gets name
// run it on db courses tables
function checkNameExistence($name) {
    $conn = $GLOBALS['conn'];

    try {
        $sqlSentence = "SELECT * FROM courses WHERE name ='" . $name . "'";
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        $conn->close();
        if ($result->num_rows > 0) {
            return "exist";
        } else {
            return "ok";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

//function check Student phone existance
// gets email
// run it on db potentialic tables
function checkStudentPhoneExistence($phone, $objectID) {
    $conn = $GLOBALS['conn'];

    try {
        $sqlSentence = "SELECT * FROM students WHERE phone ='" . $phone . "' AND student_id !='" . $objectID . "'";
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        if ($result->num_rows > 0) {
            $conn->close();
            return "exist";
        } else {
            return "ok";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

//function check admin phone existance
// gets email
// run it on db potentialic tables
function checkAdministatorPhoneExistence($phone, $objectID) {
    $conn = $GLOBALS['conn'];

    try {
        $sqlSentence = "SELECT * FROM administators WHERE phone ='" . $phone . "' AND administator_id !='" . $objectID . "'";
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        $conn->close();
        if ($result->num_rows > 0) {
            return "exist";
        } else {
            return "ok";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

//function check student email existance
// gets email
// run it on db potentialic tables
function checkStudentEmailExistence($email, $objectID) {
    $conn = $GLOBALS['conn'];
    try {
        $sqlSentence = "SELECT * FROM students WHERE email ='" . $email . "' AND student_id !='" . $objectID . "'";
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        if ($result->num_rows > 0) {
            $conn->close();
            return "exist";
        } else {
            return "ok";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

//function check admin email existance
// gets email
// run it on db potentialic tables
function checkAdministatorEmailExistence($email, $objectID) {
    $conn = $GLOBALS['conn'];
    try {
        $sqlSentence = "SELECT * FROM administators WHERE email ='" . $email . "' AND administator_id !='" . $objectID . "'";
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        if ($result->num_rows > 0) {
            $conn->close();
            return "exist";
        } else {
            return "ok";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

//function check course name existance
// gets name
// run it on db courses tables
function checkCNameExistenceExceptWho($name, $objectID) {
    $conn = $GLOBALS['conn'];

    try {
        $sqlSentence = "SELECT * FROM courses WHERE name ='" . $name . "' AND course_id !='" . $objectID . "'";
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        $conn->close();
        if ($result->num_rows > 0) {
            return "exist";
        } else {
            return "ok";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

//
//function check admin existance
// gets email and pass
// run it on db potentialic tables
function matchMarker($email, $password) {
    $conn = $GLOBALS['conn'];

    try {
        $sqlSentence = "SELECT * FROM administators WHERE email ='" . $email . "' AND password ='" . $password . "'";
        $result = $conn->query($sqlSentence);
        if (!$result) {
            throw new Exception("");
        };
        $conn->close();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentAdmin = new administator($row['administator_id'], $row['name'], $row['phone'], $row['email'], $row['role'], "censored", $row['image']);
            return $currentAdmin;
        } else {
            return "not exist";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

;

//// get all Deals 
/// gets nothing
/// returns dealsArray with names and all..
function getAllDeals() {
    require 'connection.php';
    $sqlDealsSentence = "SELECT * FROM deals ORDER BY course_id ASC";
    try {
        $result = $conn->query($sqlDealsSentence);
        if (!$result) {
            throw new Exception("1");
        };
//        $sqlDealsSentence->close();
        $dealsArray = array();
        if ($result->num_rows > 0) {
            //there are deals to show, continue..
            while ($row = $result->fetch_assoc()) {
                $student_id = $row['student_id'];
                $sqlStudentSentence = "SELECT * FROM students WHERE student_id=" . $student_id . "";
                $resultStudents = $conn->query($sqlStudentSentence);
                if (!$resultStudents) {
                    throw new Exception("1");
                };
                $rowStudent = $resultStudents->fetch_assoc();
                $course_id = $row['course_id'];
                $sqlCourseSentence = "SELECT * FROM courses WHERE course_id=" . $course_id . "";
                $resultCourses = $conn->query($sqlCourseSentence);
                if (!$resultCourses) {
                    throw new Exception("1");
                };
                $rowCourse = $resultCourses->fetch_assoc();
                ///
                $currentDeal = new tableDeal($row['deal_id'], $rowCourse['name'], $rowCourse['image'], $rowStudent['name'], $rowStudent['image']);
                array_push($dealsArray, $currentDeal);
            };
            $conn->close();
            return $dealsArray;
        } else {
            $conn->close();
            return "2";
        }
    } catch (Exception $e) {
        return "1";
    };
}

;
