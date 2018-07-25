<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "MvC_school_DB";

$conn = new mysqli($servername, $username, $password, $dbname);

$GLOBALS['conn'] = $conn;