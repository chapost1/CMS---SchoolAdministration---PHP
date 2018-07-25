<?php

class deal {

    public $deal_id;
    public $course_id;
    public $student_id;

    function __construct($deal_id, $course_id, $student_id) {
        if (!($deal_id == "")) {
            $this->deal_id = $deal_id;
        };
        $this->course_id = $course_id;
        $this->student_id = $student_id;
    }
}
