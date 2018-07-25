<?php
// Grabs the URI and breaks it for routing
$request_uri = explode('/', $_SERVER['REQUEST_URI'], 2);
///
require_once 'Models/administator.php';
session_start();
if (isset($_SESSION['admin'])) {
    $currentAdmin = $_SESSION['admin'];
    $adminRole = $currentAdmin->role;
    $adminId = $currentAdmin->administator_id;
    $adminName = $currentAdmin->name;
    $adminImage = $currentAdmin->image;
    // check if image is not uploaded, use default.
    if ($adminImage === null) {
        $adminImage = "images/defaultImageAdministator.png";
    };
    switch ($request_uri[1]) {
        case '':
        case 'home':
        case 'login':
            /// if logged in -  until press log out
            header('location: /school');
            break;
        case 'administration':
            if (!($adminRole === "Manager" || $adminRole === "Owner")) {
                header('location: /school');
            };
            break;
        case 'deals':
            if (!($adminRole === "Sales" || $adminRole === "Owner")) {
                header('location: /school');
            };
            break;
    };
};
//
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <link rel="icon" type="image/png" href="images/admin-icon.png" />
        <meta charset="UTF-8">
        <!--jQuery-->
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <!--Bootsrtap-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
              crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
        crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
        crossorigin="anonymous"></script>
        <!--Fonts-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Delius" rel="stylesheet">
        <!--CSS-->
        <link href="styles/style.css" rel="stylesheet" type="text/css"/>
        <?php
        ////jquery dataTable plugin - specificly needed in deals page
        // Route it up!
        switch ($request_uri[1]) {
            case 'deals':
                ?>
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
                <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
                <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
                <?php
                break;
        }
        ?>
    </head>
    <body>
        <!--START of nav bar-->
        <nav class="navbar navbar-expand-lg navbar-light">
            <!--Brand Logo Always shown-->
            <a class="navbar-brand" href="#"><img id="logoImage" class="nav-brand-img" src="images/MvClogo.svg" alt="logo" /></a>
            <?php
            switch ($request_uri[1]) {
                //// allowed pages - show content
                case 'school':
                case 'administration':
                case 'deals':
                    //if Not Logged In.. don't show nav content.
                    if (isset($_SESSION['admin'])) {
                        ?> 
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <!--Menu Buttons, school and administration-->
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="mainNavUl navbar-nav mr-auto">
                                <?php
                                //if  URI/School page - active nav button
                                switch ($request_uri[1]) {
                                    // School page
                                    case 'school':
                                        ?><li class = "nav-item active"> <?php
                                        break;
                                    default:
                                        ?> <li class = "nav-item"> <?php
                                            break;
                                    }
                                    ?>
                                    <a class="nav-link" href="school"><span style="color:black;"></span>School<span style="color:black;"></span></a>
                                </li>
                                <?php
                                /// Admininstration Page =  if manager or owner, else he won't see it. ( sales )
                                if ($adminRole === "Manager" || $adminRole === "Owner") {
                                    //if URI/Administration page - active nav button
                                    switch ($request_uri[1]) {
                                        // Administration page
                                        case 'administration':
                                            ?><li class = "nav-item active"> <?php
                                            break;
                                        default:
                                            ?> <li class = "nav-item"> <?php
                                                break;
                                        }
                                        ?>
                                        <a class="nav-link" href="administration">Administration<span style="color:black;"></span></a>
                                    </li>
                                    <?php
                                }
                                // end of if
                                //
                                /// Deals Page = if sales or owner, else he won't see it. ( manager )
                                if ($adminRole === "Sales" || $adminRole === "Owner") {
                                    //if URI/Deals page - active nav button
                                    switch ($request_uri[1]) {
                                        // Administration page
                                        case 'deals':
                                            ?><li class = "nav-item active"> <?php
                                            break;
                                        default:
                                            ?> <li class = "nav-item"> <?php
                                                break;
                                        }
                                        ?>
                                        <a class="nav-link" href="deals">Deals<span style="color:black;"></span></a>
                                    </li>
                                    <?php
                                }
                                // end of if
                                //
                            ?>
                            </ul>
                            <!--Employee Name , Role , Picture and Logout-->
                            <form class="form-inline adminFormy my-2 my-lg-0">
                                <ul class="navbar-nav mr-auto">
                                    <li class = "nav-item">
                                        <div class="row">
                                            <div class="col-xs-8"><span class="nameRole"><?php echo $adminName; ?>, <span id='adminRole'><?php echo $adminRole; ?></span></span>
                                                <a id="logoutBTN" class="nav-link" href="Controllers/unsetSession.php">Logout</a></div>                                        
                                            <img id='loggedImg' class="standardImg col-xs-4" data-num="<?php echo $adminId; ?>" src="<?php echo $adminImage; ?>" alt="you" />
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <?php
                    };
                    //end of if - isset admin session
                    break;
                // end of nav content
                //ain't logged in. empty from nav contents except logo
                case '':
                case 'home':
                case 'login':
                // default for unknown page for example eror 404 .
                default:
                    /// empty
                    break;
                // end of big switch case
            }
            ?>
        </nav>
        <!--END of nav bar-->

        <!--START of Article - container-->
        <article class="container-fluid">
            <div class="row">
                <!-- Content depend on page-->
                <?php
                // Route it up!
                switch ($request_uri[1]) {
                    ///Home -login page
                    case '':
                    case 'home':
                    case 'login':
                        require_once 'Viewers/login.php';
                        break;
                    //school page
                    case 'school':
                        if (isset($_SESSION['admin'])) {
                            require_once 'Viewers/school.php';
                        } else {
                            require_once 'Viewers/404.php';
                        };
                        break;
                    case 'administration':
                        if (isset($_SESSION['admin'])) {
                            if ($adminRole === "Manager" || $adminRole === "Owner") {
                                require_once 'Viewers/administration.php';
                            } else {
                                require_once 'Viewers/404.php';
                            };
                        } else {
                            require_once 'Viewers/404.php';
                        }
                        break;
                    case 'deals':
                        if (isset($_SESSION['admin'])) {
                            if ($adminRole === "Sales" || $adminRole === "Owner") {
                                require_once 'Viewers/deals.php';
                            } else {
                                require_once 'Viewers/404.php';
                            };
                        } else {
                            require_once 'Viewers/404.php';
                        }
                        break;
//                     Everything else - catch error 404
                    default:
                        require_once 'Viewers/404.php';
                        break;
                }
                ?>
            </div>
        </article>
        <!--END of Article - container-->
        <!--Delete Object Popup Modal-->
        <div id='popupDeleteAsk' class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <!--Delete Object Popup Modal-->
    </body>
    <?php
    ////script maintain
    // Route it up!
    switch ($request_uri[1]) {
        case 'school':
        case 'administration':
            ?>
            <script src="scripts/mainPages.js" type="text/javascript"></script>
            <?php
            break;
        case '':
        case 'home':
        case 'login':
            ?>
            <script src="scripts/login.js" type="text/javascript"></script>
            <?php
            break;
        case 'deals':
            ?>
            <script src="scripts/dealsTable.js" type="text/javascript"></script>
            <?php
            break;
    }
    ?>
</html>