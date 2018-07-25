<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Eror 404</title>
    </head>
    <body>
        <!--Eror 404 page-->
        Oops! Seems you've lost..&nbsp;
        <?php
        //// start of a href cliker
        ?><a class="err404-Redirect"<?php
           /// href depends on session
           // if logged in.. take him to school page
           if (isset($_SESSION['admin'])) {
               ?>href="school"
               <?php
           } else {
               // not signed in - take him to log in page
               ?>
               href='login'
               <?php
           };
           //end of if
           ?> >click here!</a> <?php ?>
        &nbsp;to get back safety again.
    </body>
</html>
