<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>MvC | Login</title>
    </head>
    <body>
        <!--Login Page-->
        <div class="container">
            <div class="welcome row">
                <h1>Welcome!</h1>
            </div>
            <div class="row">
                <div class="loginContainer jumbotron text-center bg-light">
                    <!--Form <-> Form-->
                    <form>
                        <div class="form-group">
                            <label for="loginEmail1">Email address</label>
                            <input type="email" class="form-control" id="loginEmail" placeholder="example@email.com">
                            <div class="form-control errMsg" id="emailErrMsg"></div>
                        </div>
                        <div class="form-group">
                            <label for="loginPassword1">Password</label>
                            <input type="password" class="form-control" id="loginPassword" placeholder="Password">
                            <div class="form-control errMsg" id="passErrMsg"></div>
                        </div>
                        <button id="loginBTN" type="button" class="btn btn-purple">Login</button>
                        <div class="form-control errMsg" id="logErrMsg"></div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
