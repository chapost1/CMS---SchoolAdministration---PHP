<?php

class student {

    public $student_id;
    public $name;
    public $phone;
    public $email;
    public $image;
    public $objectSons;

    function __construct($student_id, $name, $phone, $email, $image, $courses) {
        if (!($student_id == "")) {
            $this->student_id = $student_id;
        };
        if (!($courses === "")) {
            $this->objectSons = $courses;
        };
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        if (!($image === null)) {
            $this->image = $image;
        };
    }

}
