
//events to do loginBTN presses and start validations
// enter while in password input
$('#loginPassword').on('keyup', (e) => {
    if (e.keyCode == 13) {
        tryToLogin();
    };
});
// enter while in email input
$('#loginEmail').on('keyup', (e) => {
    if (e.keyCode == 13) {
        tryToLogin();
    };
});
//
// called by login BTN
$('#loginBTN').on("click", () => {
    tryToLogin();
});

// function
/// user trys to login
let tryToLogin = () => {
    $('#loginBTN').focus();
    let logErrMsg = $('#logErrMsg');
    let password = $('#loginPassword').val();
    let email = $('#loginEmail').val();
    let keepGoing = checkFields(password, email);
    if (keepGoing) {
        /// fields are not empty.
        // let's check email format and than -> password validity.
        checkEmailFormat(password, email);
    } else {
        logErrMsg.html("Plese Fill in The Fields in corrent Format.");
        logErrMsg.show();
        setTimeout(() => logErrMsg.hide(), 4000);
    }
    ;
};



/// function
// called by login BTN Event ,  check if all the fields are filled.
let checkFields = (password, email) => {
    let passErrMsg = $('#passErrMsg');
    let emailErrMsg = $('#emailErrMsg');
    let continueCheckPass = false;
    let continueCheckEmail = false;
    // email - first stage.
    if (email.length < 1) {
        emailErrMsg.html("Plese Fill in Email.");
        emailErrMsg.show();
        setTimeout(() => emailErrMsg.hide(), 4000);
    } else {
        continueCheckEmail = true;
        /// pass - first stage.
        if (password.length < 1) {
            passErrMsg.html("Plese Fill in Password.");
            passErrMsg.show();
            setTimeout(() => passErrMsg.hide(), 4000);
        } else {
            // pass is not empty too.
            continueCheckPass = true;
        }
        ;
    }
    /// check if everything is filled
    if (continueCheckPass && continueCheckEmail) {
        return true;
    } else {
        return false;
    }
    ;
};

/// function
// called by login BTN Event ,  after we know all the fields are filled, check email format to know if keep going.
let checkEmailFormat = (password, email) => {
    let formData = new FormData();
    formData.append('email', email);
    formData.append('emailFormatCheck', null);
    $.ajax({
        /// it gets in form Data 'edit'+which+'' , in post method for else if querys
        url: 'Controllers/loginControl.php',
        type: "POST",
        data: formData,
        success: function (response) {
            if (response === 'wrong email format.') {
                let emailErrMsg = $('#emailErrMsg');
                emailErrMsg.html(response);
                emailErrMsg.show();
                setTimeout(() => emailErrMsg.hide(), 4000);
            } else {
                /// let's check if there is a match.
                matchMarker(password, email);
            }
        },
        cache: false,
        contentType: false,
        processData: false
    });
};

/// function
// called by checkEmailFormat Function who called by login BTN Event ,  after we know email format is ok, let's check if there is a pass- mail match.
let matchMarker = (password, email) => {
    let formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    formData.append('matchMarker', null);
    $.ajax({
        /// it gets in form Data 'edit'+which+'' , in post method for else if querys
        url: 'Controllers/loginControl.php',
        type: "POST",
        data: formData,
        success: function (response) {
            if(response === "failed"){
                let logErrMsg = $('#logErrMsg');
                logErrMsg.html("Email and Password are not a match.");
                logErrMsg.show();
                setTimeout(() => logErrMsg.hide(), 5000);
            } else {
                window.location.href="school";
            }
        },
        cache: false,
        contentType: false,
        processData: false
    });
};