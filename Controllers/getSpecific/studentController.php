<?php
/// the purpose is to create a student object with all courses needed details for showing.
require '../../DbCalls/studentsTable.php';
//// getting vars
$which = $_POST['which'];
$student_id = $_POST['objectID'];
//
$studentObject = getStudentAndCourses($student_id);
/// cooking array with object we made and type ($which)
/// [0] is student . student have inside his courses.     [1] is type.
$sendArray = array($studentObject,$which);
echo json_encode($sendArray);