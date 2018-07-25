<?php

class administator {

    public $administator_id;
    public $name;
    public $phone;
    public $email;
    public $role;
    public $password;
    public $image;

    function __construct($administator_id, $name, $phone, $email, $role, $password, $image) {
        if (!($administator_id == "")) {
            $this->administator_id = $administator_id;
        };
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->role = $role;
        $this->password = $password;
        if (!($image === null)) {
            $this->image = $image;
        };
    }

}
