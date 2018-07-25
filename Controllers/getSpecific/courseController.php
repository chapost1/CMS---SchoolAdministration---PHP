<?php
//// need to get course details and.. number of deals closed.
/// the purpose is to create a course object with all courses needed details for showing. and know number of students who are signed.
require '../../DbCalls/coursesTable.php';
//// getting vars
$which = $_POST['which'];
$course_id = $_POST['objectID'];
//
$courseObject = getCoursesAndStudents($course_id);
/// cooking array with object we made and type ($which)
/// [0] is student . student have inside his courses.     [1] is type.
$sendArray = array($courseObject,$which);
echo json_encode($sendArray);