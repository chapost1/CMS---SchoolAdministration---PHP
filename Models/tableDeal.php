<?php

class tableDeal {

    public $deal_id;
    public $course_name;
    public $course_image;
    public $student_name;
    public $student_image;

    function __construct($deal_id, $course_name, $course_image, $student_name, $student_image) {
        $this->deal_id = $deal_id;
        $this->course_name = $course_name;
        $this->course_image = $course_image;
        $this->student_name = $student_name;
        $this->student_image = $student_image;
    }

}
