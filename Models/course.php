<?php

class course {

    public $course_id;
    public $name;
    public $desc;
    public $image;
    public $objectSons;

    function __construct($course_id, $name, $desc, $image , $students) {
        if (!($course_id == "")) {
            $this->course_id = $course_id;
        };
        if (!($students == "")) {
            $this->objectSons = $students;
        };
        $this->name = $name;
        $this->desc = $desc;
        if (!($image === null)) {
            $this->image = $image;
        };
    }

}
