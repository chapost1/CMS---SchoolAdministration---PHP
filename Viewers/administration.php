<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>MvC | Administration</title>
    </head>
    <body>
        <!--ShowDetails ,  which Shit is showing..-->
        <div class="bigGilaFather col-md-4"><p class="lead"><div class="container"></div>
            <div class="row bigGila">
                <!-- Administators -->
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <div class="navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">
                                <li class = "nav-item">
                                    <span>Administators</span>
                                </li>
                            </ul>
                            <!--Course Name ,Picture and Description-->
                            <form class="form-inline my-2 my-lg-0">
                                <ul class="i-b navbar-nav mr-auto">
                                    <li class = "nav-item">
                                        <button type="button" class="addingFormButton btn btn-basic bg-light"><strong>+</strong></button>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </nav>
                    <div id="administatorsContainer" class="gilaCont">
                    </div>
                </div>
            </div>
        </div>
        <!--Main container ,  which Shit Happens..-->
        <div id='mainContainer' class="col-lg-8 jumbotron text-center bg-light">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div id="mainContainerNav" class="navbar-collapse" id="navbarSupportedContent">
                    Main Container
                </div>
            </nav>
            <div id="mainContainerInner">

            </div>
        </div>
        <div id="loading">
            <img id="loading-image" src="images/ajax-loading.gif" alt="Loading..." />
        </div>
    </body>
</html>
