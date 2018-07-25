//// Main Container - we want to change this friend
const mainContainerNav = document.getElementById('mainContainerNav');
const mainContainerInner = document.getElementById('mainContainerInner');
/// we will use that in the future.
const adminRole = $('#adminRole')[0].innerText.toLowerCase();
////
/// onload functions
/// called by body onload function
window.onload = () => {
    /// route current Page we are at -  school or administator.
    // modify what to handle
    switch (location.pathname.substr(1)) {
        case "school":
            startTheMagic(['course', 'student']);
            //prevent from sales admin to be able to add course.
            if (adminRole == "sales") {
                salesCannotAddCourse();
            }
            ;
            break;
        case "administration":
            startTheMagic(['administator']);
            break;
    }
    ;
    // logo get our main container back to default page state. 
    $($('#logoImage')[0]).on("click", showNumbers);
};


////
/// Function
//// Add event listeners to buttons for adding an object(student, course, admin and etc..
/// showNum = true is for the function to call specific function when it's done (default value is false) - get all asked function appear on side.
let startTheMagic = someArray => {
    for (let i = 0; i < someArray.length; i++) {
        getAllAsked(someArray[i], showNum = true);
        $($('.addingFormButton')[i]).click(function () {
            cookCoursesIfNeededToChooseSave(someArray[i]);
        });
    }
    ;
}
;
////
/// main container defult - show number of objects which relevant
/// called onload when finished getAllAsked function or by delete button , and logo.
function showNumbers() {
    $('#mainContainerNav').html("Main Container");
    /// route current Page we are at -  school or administator.
    switch (location.pathname.substr(1)) {
        case "school":
            freshMainContainer(['course', 'student']);
            break;
        case "administration":
            freshMainContainer(['administator']);
            break;
    }
    ;
}
;
let freshMainContainer = someArray => {
    let content = "";
    for (let i = 0; i < someArray.length; i++) {
        let number = $(`#${someArray[i]}sContainer`)[0].children.length;
        let word = someArray[i].charAt(0).toUpperCase() + someArray[i].slice(1);
        content += `<div>Number of ${word}s: ${number}</div>`;
    }
    ;
    $('#mainContainerInner').html(content);
};
//
function salesCannotAddCourse() {
    //0 is course button
    $($('.addingFormButton')[0]).unbind("click");
    $($('.addingFormButton')[0]).click(function () {
        $(mainContainerNav).html("Add Course")
        $(mainContainerInner).html("Sales Administrator cannot add new course.");
    });
}
;
////
//// Function
////// it's role is to filter if needed to collect courses to display for student creation and closing deal or not.
///called by buttuns - In HTML [ + ] of adding Students / Courses Have onclik functions
function cookCoursesIfNeededToChooseSave(which) {
    switch (which) {
        case 'student':
            $.post('Controllers/adderAndAll/coursesController.php', {getAllChoose: null, which: which}, function (coursesToSign) {
                coursesToSign = JSON.parse(coursesToSign);
                //courses
                var texture = "";
                if (coursesToSign[0].length > 0) {
                    ////start to organize it.
                    coursesToSign[0].forEach(function (object, i) {
                        texture += "" + object.name + "&nbsp;<input class='i-b courseSelect' type='checkbox' data-courseId='" + object.course_id + "' value=''/>";
                    });
                }
                ;
                showAddCont({texture: texture, which: coursesToSign[1]});
            });
            break;
        case 'course':
        case 'administator':
            showAddCont({texture: "", which: which});
            break;
    }
}
////
//// Function
////// When Press Add a something - know what to display on main-Container
///called by  cookCoursesIfNeededToChoose function -  it gets an object whith type(which) to create and courses to choose if needed. (textrure)
function showAddCont(getObject) {
    /// get Courses to let students be able to close "deal" with them. 
    var texture = getObject.texture;
    // keep going..
    var which = getObject.which;
    //// change all the scripts depends on type(which).
    /// d-n class means display: none;
    /// whichU is just what entered with first initial Uppercase for calling differ functions.
    //local vars
    let nameOnblurFunction = '';
    let dNoneIfCourse = '';
    let dNoneIfStudent = '';
    let dNoneIfAdministator = '';
    switch (which) {
        case 'student':
            var whichU = 'Student';
            dNoneIfStudent = ' d-n ';

            break;
        case 'course':
            var whichU = 'Course';
            dNoneIfCourse = ' d-n ';
            /// only if course, validate name.
            nameOnblurFunction = 'onblur=checkCourseNameExistence(this);';
            break;
        case 'administator':
            var whichU = 'Administator';
            dNoneIfAdministator = ' d-n ';
            break;
    }
    mainContainerNav.innerHTML = "Add " + whichU;
    mainContainerInner.innerHTML = `
    <form action='' id='uploader' method='post' data-which='${which}' onsubmit='saveObject(this);'>
        <div class='buttonsRow row'>
            <div class='col-md-3'>
                <input type='submit' class='btn btn-purple' value='Save' />
            </div>
            <div class='col-md-6'></div>
            <div class='col-md-3'></div>
        </div>
        <div id='addingErrMsg' class='errMsg'></div>
        <div id='addingSucsMsg' class='sucsMsg'></div>
        <div class='form-group'>
            <label for='${which}Name'>
                Name</label>
            <input type='text' class='form-control' id='${which}Name' placeholder='${whichU} Name' ${nameOnblurFunction}>
        </div>
        <div id='nameErrMsg' class='errMsg'></div>
        <div class='${dNoneIfCourse}form-group'>
            <label for='${which}Phone'>
                Phone (10 digits) </label>
            <input type='tel' class='form-control' id='${which}Phone' placeholder='052-111-4444' onblur='checkPhoneFormat(this);'>
            <div id='phoneErrMsg' class='errMsg'></div>
        </div>
        <div class='${dNoneIfStudent} ${dNoneIfAdministator}form-group'>
            <label for='${which}Description'>Description</label>
            <textarea class='courseTextarea form-control' id='${which}Description'></textarea>
            <div id='descriptionErrMsg' class='errMsg'></div>
        </div>
        <div class='${dNoneIfCourse}form-group'>
            <label for='${which}Email'>Email address</label>
            <input type='email' class='form-control' id='${which}Email' placeholder='example@email.com' onblur=checkEmailFormat(this);>
            <div id='emailErrMsg' class='errMsg'></div>
        </div>
        <div class='${dNoneIfCourse} ${dNoneIfStudent}form-group'>
            <label for='${which}Password'>Password</label>
            <input type='password' class='form-control' id='${which}Password' placeholder='Password' onblur="passwordOnBlur(this);">
            <div class='errMsg' id='passErrMsg'></div>
        </div>
        <div class='imageUploadForm form-group'>
            <img id='imageDisplayer' class='previewImg' src='images/defaultImage${whichU}.png' alt='image' />
            <label for='${which}ImageUpload'>&nbsp;Upload Image (optional): </label>
            <input class='form-control btn btn-info' type='file' name='${which}ImageUpload' id='${which}Image' onchange='checkUploadedImage(this);'
            />
            <div id='uploadImgErrMsg' class='errMsg'></div>
        </div>
        <div class='${dNoneIfCourse} ${dNoneIfAdministator} form-group'>
            <label for='${which}Courses'>
                <strong>Courses: </strong>
            </label>
            <div id='${which}Courses' class='form-control'>
                &nbsp; ${texture}
            </div>
        </div>
    </form>
    <input id='hiddenInput' type='hidden' data-what='add' />
    `;
    //roles select for administator
    switch (which) {
        case 'administator':
            let text = "<option>Sales</option>";
            if (adminRole == "owner") {
                text += `<option>Manager</option>`;
            }
            ;
            $('#uploader').append(`<div class='selectRoleCont'<label for='beautifulSelect'>Role&nbsp;<select id='beautifulSelect' onclick='roleSelect(this);'>
                    <option>choose any role..</option>
                        ` + text + `
                    </select></div>`);
            break;
    }
    ///// don't let upload from the start....validate is not empty.
    letAddRole = false;
    letSaveImage = null;
    letSaveEmail = false;
    letSavePhone = false;
    letSaveCName = false;
    letSavePass = true;
    changePassword = true;
    ///// prevent default submit.
    $('#uploader').submit(false);
}
;
//
// function role select
// disable first option onclick
let roleSelect = (it) => {
    $(it.firstElementChild).attr("disabled", "disabled");
    $(it.firstElementChild.nextElementSibling).attr('selected', true);
    letAddRole = true;
};
// 
////
//// Function
/////check uploaded image type & size. onchange 
function checkUploadedImage(fileInput) {
    let uploadImgErrMsg = document.getElementById('uploadImgErrMsg');
    let image = fileInput.files;
    /// check if really choosed something
    if (image.length > 0) {
        let url = 'Controllers/multipleController.php';
        let objectToSend = {imageType: image[0].type, imageSize: image[0].size, checkImage: null};
        $.post(url, objectToSend, function (response) {
            let uploadImgErrMsg = document.getElementById('uploadImgErrMsg');
            if ((response === 'ok')) {
                letSaveImage = true;
                uploadImgErrMsg.innerHTML = "";
                uploadImgErrMsg.style.display = "none";
                /// open file reader to display image
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('imageDisplayer').src = e.target.result;
                }
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                letSaveImage = false;
                uploadImgErrMsg.innerHTML = response;
                uploadImgErrMsg.style.display = "block";
            }
        });
        /// in case chanded is mind and chose to not upload......
    } else {
        letSaveImage = null;
        uploadImgErrMsg.innerHTML = "";
        uploadImgErrMsg.style.display = "none";
    }
}
//
////
//// Function
///check course name existence onblur before saving, modifying it as uniqe parameter.
function checkCourseNameExistence(nameInput) {
    let name = nameInput.value;
    var hidden = $('#hiddenInput')[0];
    var what = hidden.dataset.what;
    //check what we are doing right now.
    switch (what) {
        case 'add':
            var url = 'Controllers/multipleController.php';
            var objectToSend = {name: name, checkCNameExistence: null};
            break;
        case 'edit':
            // inner switch
            var who = hidden.dataset.who;
            var url = 'Controllers/multipleController2.php';
            var objectToSend = {name: name, checkCNameExistenceExceptWho: null, objectID: who};
            break;
    }
    ;
    $.post(url, objectToSend, function (response) {
        let nameErrMsg = document.getElementById('nameErrMsg');
        if (response === 'ok') {
            // if not exist let user continue
            letSaveCName = true;
            nameErrMsg.innerHTML = "";
            nameErrMsg.style.display = "none";
        } else {
            // name already exists, block him out.
            letSaveCName = false;
            nameErrMsg.innerHTML = response;
            nameErrMsg.style.display = "block";
        }
    })
}
//
////
//// Function
///check email format and existence onblur
function checkEmailFormat(emailInput) {
    let email = emailInput.value;
    email = email.split(' ').join('');
    var hidden = $('#hiddenInput')[0];
    var what = hidden.dataset.what;
    //check what we are doing right now.
    switch (what) {
        case 'add':
            var url = 'Controllers/multipleController.php';
            var objectToSend = {email: email, checkEmailFormat: null};
            break;
        case 'edit':
            // inner switch
            var which = hidden.dataset.which;
            var who = hidden.dataset.who;
            var url = 'Controllers/multipleController2.php';
            switch (which) {
                case 'student':
                    var objectToSend = {email: email, checkStudentEmailExistence: null, objectID: who};
                    break;
                case 'administator':
                    var objectToSend = {email: email, checkAdministatorEmailExistence: null, objectID: who};
                    break;
            }
            ;
            // end of inner switch
            break;
    }
    ;
    $.post(url, objectToSend, function (response) {
        let emailErrMsg = document.getElementById('emailErrMsg');
        if (response === 'ok') {
            letSaveEmail = true;
            emailErrMsg.innerHTML = "";
            emailErrMsg.style.display = "none";
        } else {
            letSaveEmail = false;
            emailErrMsg.innerHTML = response;
            emailErrMsg.style.display = "block";
        }
    })
}
//
////
//// Function
///check phone format onblur
function checkPhoneFormat(phoneInput) {
    let phone = phoneInput.value;
    /// let user put '-' in phone input.
    let digitphone = phone.split('-').join('');
    let isnum = /^\d+$/.test(digitphone);
    //// check phone contain only numbers and only 10.
    if ((digitphone.length === 10) && isnum) {
        let phoneErrMsg = document.getElementById('phoneErrMsg');
        var hidden = $('#hiddenInput')[0];
        var what = hidden.dataset.what;
        //check what we are doing right now.
        switch (what) {
            case 'add':
                var url = 'Controllers/multipleController.php';
                var objectToSend = {phone: digitphone, checkPhoneExistence: null};
                break;
            case 'edit':
                // inner switch
                var which = hidden.dataset.which;
                var who = hidden.dataset.who;
                var url = 'Controllers/multipleController2.php';
                switch (which) {
                    case 'student':
                        var objectToSend = {phone: digitphone, checkStudentPhoneExistence: null, objectID: who};
                        break;
                    case 'administator':
                        var objectToSend = {phone: digitphone, checkAdministatorPhoneExistence: null, objectID: who};
                        break;
                }
                ;
                // end of inner switch
                break;
        }
        ;
        $.post(url, objectToSend, function (response) {
            let phoneErrMsg = document.getElementById('phoneErrMsg');
            if (response === 'ok') {
                letSavePhone = true;
                phoneErrMsg.innerHTML = "";
                phoneErrMsg.style.display = "none";
            } else {
                //// not ok.
                letSavePhone = false;
                phoneErrMsg.innerHTML = response;
                phoneErrMsg.style.display = "block";
            }
        });
    } else {
        //// not ok.
        letSavePhone = false;
        phoneErrMsg.innerHTML = "phone number should be 10 digits numbers only.";
        phoneErrMsg.style.display = "block";
    }
    ;
}
function passwordOnBlur(input) {
    if ($(input).val().length < 4) {
        $('#passErrMsg').html("password is too short-minimum length: 4 digits.");
        $('#passErrMsg').show();
    } else{
         $('#passErrMsg').empty();
        $('#passErrMsg').hide();
    };
}
;
//
////
//// Function
//// save object in DB - called by save button onsubmit event - add / edit object form
function saveObject(form) {
    /// know the object
    var which = form.dataset.which;
    // save values as vars
    let objectName = document.getElementById('' + which + 'Name');
    /// preparing form final validation
    //// checking what object to save in DB
    switch (which) {
        case 'student':
            /// gets all checkBox of courses selected to add them to Deals Table.
            var selectedCoursesArray = [];
            var myCheckBoxContChildren = $('#' + which + 'Courses').children();
            for (var i = 0; i < myCheckBoxContChildren.length; i++) {
                if (myCheckBoxContChildren[i].checked) {
                    selectedCoursesArray.push(myCheckBoxContChildren[i].dataset.courseid)
                }
            }
            // phone and email validations happened onblur events
            var objectPhone = document.getElementById('' + which + 'Phone');
            var objectEmail = document.getElementById('' + which + 'Email');
            var formOk = (objectName.value.length > 0 && letSaveEmail && letSavePhone);
            break;
        case 'course':
            var objectDescription = document.getElementById('' + which + 'Description');
            ///Course name existence checked ontop on blur event.
            var formOk = (courseName.value.length > 0 && letSaveCName && objectDescription.value.length > 0);
            break;
        case 'administator':
            // phone and email validations happened onblur events
            var objectPhone = document.getElementById('' + which + 'Phone');
            var objectEmail = document.getElementById('' + which + 'Email');
            if (changePassword) {
                /// determine first if user clicked on change password button
                var objectPassword = document.getElementById('' + which + 'Password').value;
                ///add case
                if (objectPassword.length >= 4) {
                    //letSavePass stays true
                    $('#passErrMsg').hide();
                    $('#passErrMsg').empty();
                    /// edit case
                    if (letSavePass === null) {
                        ///null happens only in edit case: if no request to change its still a null.
                        letSavePass = false;
                    }
                    ;
                } else {
                    ///err.. password is too short
                    $('#passErrMsg').html("password is too short-minimum length: 4 digits.");
                    $('#passErrMsg').show();
                }
                ;
            }
            ;
            var objectPassword = document.getElementById('' + which + 'Password');
            var objectRole = document.getElementById('beautifulSelect');
            if (letSavePass === null) {
                //edit case
                if (changePassword) {
                    //need to add password.
                    var formOk = (objectName.value.length > 0 && $('#' + which + 'Password').val().length >= 4 && letSaveEmail && letSavePhone && letAddRole);

                } else {
                    //no need to add password.
                    var formOk = (objectName.value.length > 0 && letSaveEmail && letSavePhone && letAddRole);
                }
            } else if (letSavePass === false || letSavePass === true) {
                // need to add password.
                var formOk = (objectName.value.length > 0 && $('#' + which + 'Password').val().length >= 4 && letSaveEmail && letSavePhone && letAddRole);
            }
            ;
            break;
    }
    ;
    let addingSucsMsg = document.getElementById('addingSucsMsg');
    let addingErrMsg = document.getElementById('addingErrMsg');
    addingSucsMsg.style.display = "none";
    //// make sure didn't upload any wrong type of image
    if ((letSaveImage) || letSaveImage === null) {
        ////
        ///// make sure name is set and email is in the right format.
        if (formOk) {
            addingErrMsg.innerHTML = "";
            addingErrMsg.style.display = "none";
            ////
            /// last modifying before calling controller.
            var formData = new FormData();
            formData.append('name', objectName.value);
            /// modifying depends on which object
            switch (which) {
                case 'student':
                    formData.append('email', objectEmail.value);
                    formData.append('phone', objectPhone.value.split('-').join(''));
                    formData.append('selectedCourses', JSON.stringify(selectedCoursesArray));
                    formData.append('addStudent', null);
                    break;
                case 'course':
                    formData.append('description', objectDescription.value);
                    formData.append('addCourse', null);
                    break;
                case 'administator':
                    formData.append('email', objectEmail.value);
                    formData.append('phone', objectPhone.value.split('-').join(''));
                    if (!(letSavePass === null)) {
                        ///user want to update password
                        formData.append('password', objectPassword.value);
                    } else {
                        //no change needed
                        formData.append('password', "no");
                    }
                    ;
                    formData.append('role', objectRole.value);
                    formData.append('addAdministator', null);
                    break;
            }
            ;
            //// if chose to not use image - give him default image
            if (letSaveImage === null) {
                formData.append('image', 'useDefault');
            } else {
                //// user uploaded an image
                let fileSelect = document.getElementById('' + which + 'Image');
                let file = fileSelect.files[0];
                //// name is going to be student_id for future uses..
                let filename = '.jpg';
                formData.append('image', file, filename);
            }
            ;
            // use hidden
            var hidden = $('#hiddenInput')[0];
            var what = hidden.dataset.what;
            //check what we are doing right now.
            switch (what) {
                case 'add':
                    //// calling XHR post Function. for adding new object
                    XHRcall(formData, which);
                    break;
                case 'edit':
                    // gather data about the user and send it along with form
                    let who = hidden.dataset.who;
                    formData.append('objectID', who);
                    formData.append('edit', null);
                    setTimeout(() => {
                        saveNUpdate(formData, which);
                    }, 50);
                    if (($('#existingImageOpinion')[0].checked)) {
                        removeExistingImage(who, which);
                    }
                    ;
                    break;
            }
            ;

        } else {
            ///// not everything is as requested.
            addingErrMsg.innerHTML = "Some of the required fields are empty or not as requested.";
            addingErrMsg.style.display = "block";
            /// wait 4 seconds, let this error gone away.
            setTimeout(function () {
                addingErrMsg.style.display = "none";
            }, 4000)
        }
    }
    ;
}
;
//
////
//// Function
//// XHR self-made function - working when sending adding form
function XHRcall(formData, which) {
    /// formData is the form we are going to send with the image if needed.
    // which is case depened. student / course / admin(?)
    // Set up the request.
    var xhr = new XMLHttpRequest();
    // Open the connection.
    xhr.open('POST', 'Controllers/adderAndAll/' + which + 'sController.php', true);
    // Set up a handler for when the request finishes.
    xhr.onload = function () {
        let addingErrMsg = document.getElementById('addingErrMsg');
        if (xhr.status === 200) {
            // request to php worked.
            addingErrMsg.style.display = "none";
            ///// get response from php controller
            /// that's an array, [0] is id [1] is response
            var return_data = xhr.responseText;
            return_data = JSON.parse(return_data);
            if (return_data[1] === "success.") {
                //// everything went smooth, let the user know it.
                /// affect main container and display object data
                var sendObject = {objectID: return_data[0], which: which};
                setTimeout(function () {
                    getSpecificObject(sendObject);
                }, 180)
            } else {
                /// oops, something happened..
                addingErrMsg.innerHTML = return_data[1];
                addingErrMsg.style.display = "block";
                if (return_data[1] === "couldn't add " + which + ". please try again later.") {
                    // something serious, adding failed, stay here. default mesage when all failed. ^
                    setTimeout(function () {
                        addingErrMsg.style.display = "none";
                    }, 4000)
                } else {
                    ///just a problem with uploading image or course, let him know and show him the object after 4 sec
                    var sendObject = {objectID: return_data[0], which: which};
                    setTimeout(function () {
                        getSpecificObject(sendObject);
                    }, 4000)
                }
            }
        } else {
            //// couldn't request php page.
            addingErrMsg.innerHTML = "couldn't communicate server.";
            addingErrMsg.style.display = "block";
        }
    };
    // Send the Data.
    xhr.send(formData);
    ////// after everything is done..
    //// fresh object added glila
    setTimeout(function () {
        getAllAsked(which);
    }, 120);
    //
}
//
////
//// Function
/// getting all students and courses from DB for showing them
function getAllAsked(which, showNum = false) {
    let nowContainer = document.getElementById('' + which + 'sContainer');
    let url = 'Controllers/adderAndAll/' + which + 'sController.php';
    let objectToSend = {getAll: null};
    $.post(url, objectToSend, function (objects) {
        objects = JSON.parse(objects);
        nowContainer.innerHTML = "";
        if (objects.length > 0) {
            ////start to organize it.
            objects.forEach(function (object, i) {
                // Object Name = prevent little longer name than usual to destroy design - class font-size: 1rem ==>  0.98rem;
                if (object.name.length > 12) {
                    var smallerFontSize = "<div class='smallerFontSize'>";
                } else {
                    var smallerFontSize = "<div>";
                }
                ;
                //image
                if (object.image === null) {
                    //// give deafault image if needed.
                    object.image = 'images/defaultImage' + which.charAt(0).toUpperCase() + which.slice(1) + '.png';
                } else {
                    object.image = "'" + object.image + "?" + new Date().getTime() + "'";
                }
                ;
                let gilaImgSize = ' standardImg ';
                let objectEmail = "";
                let objectRole = "";
                switch (which) {
                    //// check which object are we talking about to organize what to show..
                    case 'student':
                        var modeled = [object.phone.slice(0, 3), '-', object.phone.slice(3)].join('');
                        modeled = [modeled.slice(0, 7), '-', modeled.slice(7)].join('');
                        var objectID = object.student_id;
                        break;
                    case 'course':
                        var modeled = object.desc.substring(0, 10) + "...";
                        var objectID = object.course_id;
                        break;
                    case 'administator':
                        var modeled = [object.phone.slice(0, 3), '-', object.phone.slice(3)].join('');
                        modeled = [modeled.slice(0, 7), '-', modeled.slice(7)].join('');
                        var objectID = object.administator_id;
                        objectEmail = object.email;
                        var smallerFontSize = "<div>";
                        gilaImgSize = ' standardXLImg ';
                        objectRole = ", " + object.role;
                        break;
                }
                /// want to pass 2 parameters so save it as object first.
                var sendObject = JSON.stringify({objectID: objectID, which: which});
                //adding object data by name and creating tags
                nowContainer.innerHTML += "<div class='dataAppearRow row' onclick='getSpecificObject(" + sendObject + ");'><img class='" + gilaImgSize + " col-xs-3'\n\
                    src=" + object.image + " alt='image'/><div class='col-xs-7'>" + smallerFontSize + object.name + objectRole + "</div><div>" + modeled + "</div><div class=''>" + objectEmail + "</div></div></div>";
            })
        } else {
            ///empty array of objects.
        }
        ;
        if ((showNum)) {
            //show numbers of relevant objects on main container
            showNumbers();
            $('#loading').hide();
        }
        ;
    })
}
//
////
//// Function
/// cooking object before show his data
function getSpecificObject(getObject) {
    let url = 'Controllers/GetSpecific/' + getObject.which + 'Controller.php';
    //// send object id and type (inside "getObject" Var.
    $.post(url, getObject, function (response) {
        let sendObject = JSON.parse(response);
        showData(sendObject);
    });
}
;
//
////
//// Function
/// Main Container ObjectDATA Place
function showData(getObject) {
    // use adminRole var to detect what buttons can he see.
    // 
    /// split object params.
    var objectImg = getObject[0].image;
    var objectName = getObject[0].name;
    if (getObject[0].objectSons) {
        var objectSons = getObject[0].objectSons;
    } else {
        var objectSons = "";
    }

    // second array item is which.
    var which = getObject[1];
    //main image
    if (objectImg === null) {
        //// give deafault image if needed.
        objectImg = 'images/defaultImage' + which.charAt(0).toUpperCase() + which.slice(1) + '.png';
    }
    ;
    //start of switch
    let objectDesc = "";
    let dNoneIfAdmin = '';
    let dNoneIfStudent = '';
    let dNoneEditIfCourse = '';
    let courseNumAssigned = '';
    let dNoneIfCourse = '';
    switch (which) {
        case 'student':
            var objectID = getObject[0].student_id;
            var objectPhone = [getObject[0].phone.slice(0, 3), '-', getObject[0].phone.slice(3)].join('');
            objectPhone = [objectPhone.slice(0, 7), '-', objectPhone.slice(7)].join('');
            var objectEmail = getObject[0].email;
            dNoneIfStudent = ' d-n ';
            var StuCourse = "Assigned To:";
            var sonWhich = 'course';
            break;
        case 'course':
            var objectEmail = "";
            var objectPhone = "";
            var objectID = getObject[0].course_id;
            objectDesc = getObject[0].desc;
            courseNumAssigned = ", " + getObject[0].objectSons.length + " Students";
            var StuCourse = "Students:";
            //sales admin cannot edit a course.
            if (adminRole === "sales") {
                dNoneEditIfCourse = ' d-n ';
            }
            ;
            dNoneIfCourse = ' d-n ';
            var sonWhich = 'student';
            break;
        case 'administator':
            dNoneIfAdmin = ' d-n ';
            var objectID = getObject[0].administator_id;
            var objectPhone = [getObject[0].phone.slice(0, 3), '-', getObject[0].phone.slice(3)].join('');
            objectPhone = [objectPhone.slice(0, 7), '-', objectPhone.slice(7)].join('');
            var objectEmail = getObject[0].email;
            break;
    }
    /// end of switch
    //nav
    mainContainerNav.innerHTML = ` <div class='col-md-1'>${which.charAt(0).toUpperCase() + which.slice(1)}</div>
    <div class='showDataContNavMiddle col-md-10'></div>
    <div class='col-md-1'>
        <button id='editBTN' class='${dNoneEditIfCourse}btn btn-purple' onclick='cookCoursesIfNeededToChooseEdit(${JSON.stringify(getObject)});'>Edit</button>
    </div>`;
    //inner
    mainContainerInner.innerHTML = ` <section class='container showObjectDataPreInner mt-4 mb-4'>
        <div class='container showObjectDataInner'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='d-flex flex-row'>
                        <div class='p-0'>
                            <img src='${objectImg}` + `?` + `${new Date().getTime()}' alt='image' class='img-thumbnail border-0 mainImg'
                            />
                        </div>
                        <div class='pl-2 masterObject'>
                            <h2>${objectName} ${courseNumAssigned}</h2>
                            <button class='${dNoneIfStudent} ${dNoneIfAdmin}btn btn-basic' data-toggle='collapse' data-target='#courseBoard'>Description</button>
                            <textarea readonly id='courseBoard' class='collapse col-md-9 course-board'>${objectDesc}</textarea>
                            <ul class='${dNoneIfCourse}m-0 float-left' style='list-style: none; margin:0; padding: 0'>
                                <li>
                                    <i class='fas fa-phone'></i> ${objectPhone}</li>
                                <li>
                                    <i class='far fa-envelope'></i> ${objectEmail}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class='${dNoneIfAdmin}showStudentDataCoursesRow row'>
        <div class='row h'>${StuCourse}</div>
        <form class='studentDataCoursesContainer'></form>
    </div>`;
    //// if its a student or course let's collect courses or stucents chosen and append them - objectSons
    switch (which) {
        case 'student':
        case 'course':
            // hide courses of student if donot have any assign yet
            if (objectSons.length < 1) {
                $('.showStudentDataCoursesRow .h').css("display", "none");
                /// TO DO:
                // funny something about seller didn't succeed or something..
            }
            objectSons.forEach(function (son, i) {
                var SonImg = getObject[0].objectSons[i].image;
                //course image
                if (SonImg === null) {
                    //// give deafault image if needed.
                    SonImg = 'images/defaultImage' + sonWhich + '.png';
                } else {
                    SonImg += "?" + new Date().getTime();
                }
                ;
                //// courses shown in student place - need to have click event to getSpecificObject(getObject) - create object when time comes.
                switch (which) {
                    case 'student':
                        var sonObjectID = getObject[0].objectSons[i].course_id;
                        break;
                    case 'course':
                        var sonObjectID = getObject[0].objectSons[i].student_id;
                        break;
                }
                var studentCourseSendObject = JSON.stringify({objectID: sonObjectID, which: sonWhich});
                var appenddedSon = `<div class='showStudentDataCourseRow courseStudentAppear row' onclick='getSpecificObject(${studentCourseSendObject});'>
                                      <div class='row'>
                                        <img class='standardImg' src='${SonImg}' alt='Image' />${getObject[0].objectSons[i].name}
                                      </div>
                                    </div>`;
                $('.studentDataCoursesContainer').append(appenddedSon);
            });
            break;
        case 'administator':
            /// add role and icon
            let adRole = getObject[0].role;
            let adminIcon;

            switch (adRole) {
                case "Sales":
                    adminIcon = '<li><i class="fab fa-earlybirds">';
                    break;
                case "Manager":
                    adminIcon = '<li><i class="fas fa-puzzle-piece">';
                    ////
                    /// detemeine also edit for manager only if its him 
                    if (!(objectID === $('#loggedImg')[0].dataset.num)) {
                        // owner can do as he like...
                        if (!(adminRole === "owner")) {
                            $('#editBTN').hide();
                        }
                        ;
                    }
                    ;
                    break;
                case "Owner":
                    adminIcon = '<li><i class="fab fa-phoenix-framework">';
                    break;
            }
            $('.masterObject').children('ul').append(adminIcon + '&nbsp;</i>' + getObject[0].role + '</li>');
            break;
    }
    ;
    //end of switch for objectSons
}
;
////
//// Function
////// it's role is to filter if needed to collect courses to display for student creation and closing deal or not.
///called by buttuns of edit in edit show data page
function cookCoursesIfNeededToChooseEdit(getObject) {
    let which = getObject[1];
    let objectToKeep = getObject[0];
    switch (which) {
        case 'student':
            $.post('Controllers/adderAndAll/coursesController.php', {getAllChooseAndKeep: null, objectToKeep: objectToKeep, which: which}, function (coursesToSign) {
                coursesToSign = JSON.parse(coursesToSign);
                var texture = "";
                if (coursesToSign[0].length > 0) {
                    ////start to organize it.
                    coursesToSign[0].forEach(function (object, i) {
                        texture += "" + object.name + "&nbsp;<input class='i-b courseSelect' type='checkbox' data-courseId='" + object.course_id + "' value=''/>";
                    })
                }
                ;
                showEditCont({texture: texture, which: coursesToSign[1], objectToKeep: coursesToSign[2]});
            });
            break;
        case 'course':
        case 'administator':
            showEditCont({texture: "", which: which, objectToKeep: objectToKeep});
            break;
    }
}
;
///
//// Function
////// Edit Container -  object
/// called by cooking function which called by edit button - gets an object from cooking function with the object asked to edit details and type
function showEditCont(getObject) {
    /// get Courses to let students be able to close "deal" with them. 
    var texture = getObject.texture;
    // keep going..
    var which = getObject.which;
    /// create courses to choose if that's student
    switch (which) {
        case 'student':
            $('#studentCourses').append(texture);
            // check if student actually assigned any course.
            if (getObject.objectToKeep.objectSons) {
                var selectedCourses = getObject.objectToKeep.objectSons;
                setTimeout(function () {
                    if (selectedCourses.length > 0) {
                        selectedCourses.forEach(function (selected, j) {
                            var options = $('.courseSelect');
                            for (let i = 0; i < options.length; i++) {
                                if ($('.courseSelect')[i].dataset.courseid === selected.course_id) {
                                    $('.courseSelect')[i].checked = true;
                                }
                            }
                        })
                    }
                    ;
                }, 100)
                break;
            }
            ;
    }
    ;

    // local vars
    let dNoneIfStudent = '';
    let dNoneIfCourse = '';
    let dNoneIfAdministator = '';
    let objectDesc = "";
    let nameOnblurFunction = '';
    let objectName = getObject.objectToKeep.name;
    let objectEmail = "";
    let objectPhone = '';
    let dNoneEditIfAdministator = '';
    //// change all the scripts depends on type(which).
    switch (which) {
        case 'student':
            var whichU = 'Student';
            var objectID = getObject.objectToKeep.student_id;
            objectPhone = getObject.objectToKeep.phone;
            objectEmail = getObject.objectToKeep.email;
            dNoneIfStudent = ' d-n ';
            break;
        case 'course':
            var whichU = 'Course';
            var objectID = getObject.objectToKeep.course_id;
            objectDesc = getObject.objectToKeep.desc;
            dNoneIfCourse = ' d-n ';
            nameOnblurFunction = 'onblur=checkCourseNameExistence(this);';
            break;
        case 'administator':
            var whichU = 'Administator';
            var objectID = getObject.objectToKeep.administator_id;
            objectPhone = getObject.objectToKeep.phone;
            objectEmail = getObject.objectToKeep.email;
            dNoneIfAdministator = ' d-n ';
            if (!(adminRole === "owner")) {
                dNoneEditIfAdministator = ' d-n ';
            }
            ;
            break;
    }

    // check if object have an image already to show. This will help us after..
    if (getObject.objectToKeep.image === "") {
        getObject.objectToKeep.image = null;
    }
    ;
    if (!(getObject.objectToKeep.image === null)) {
        var previewImage = getObject.objectToKeep.image + "?" + new Date().getTime();
    } else {
        var previewImage = "images/defaultImage" + whichU + ".png";
    }
    ;
    mainContainerNav.innerHTML = "Edit " + whichU;
    mainContainerInner.innerHTML = `    <form action='' id='updater' method='post' data-which='${which}' onsubmit='saveObject(this);'>
        <div class='buttonsRow row'>
            <div class='editContButtons col-md-3'>
                <input type='submit' class='btn btn-warning' value='Save' />
            </div>
            <div class='editContButtonsAir col-md-6'></div>
            <div class='editContButtons col-md-3'>
                <input type='button' class='btn btn-danger' value='Delete' id='preDeleteBTN' data-toggle='modal' data-target='#popupDeleteAsk'
                    onclick='controlDelete(${JSON.stringify({objectID: objectID, which: which, imageDir: getObject.objectToKeep.image})});'
                />
            </div>
        </div>
        <div id='addingErrMsg' class='errMsg'></div>
        <div id='addingSucsMsg' class='sucsMsg'></div>
        <form>
            <div class='form-group'>
                <label for='${which}Name'>
                    Name</label>
                <input type='text' class='form-control' id='${which}Name' placeholder='${whichU} Name' ${nameOnblurFunction} value='${objectName}'>
            </div>
            <div id='nameErrMsg' class='errMsg'></div>
            <div class='${dNoneIfCourse}form-group'>
                <label for='${which}Phone'>
                    Phone (10 digits) </label>
                <input type='tel' class='form-control' id='${which}Phone' placeholder='052-111-4444' onblur='checkPhoneFormat(this);' value='${objectPhone}'>
                <div id='phoneErrMsg' class='errMsg'></div>
            </div>
            <div class='${dNoneIfStudent} ${dNoneIfAdministator}form-group'>
                <label for='${which}Description'>Description</label>
                <textarea class='courseTextarea form-control' id='${which}Description'>${objectDesc}</textarea>
                <div id='descriptionErrMsg' class='errMsg'></div>
            </div>
            <div class='${dNoneIfCourse}form-group'>
                <label for='${which}Email'>Email address</label>
                <input type='email' class='form-control' id='${which}Email' placeholder='example@email.com' onblur=checkEmailFormat(this);
                    value='${objectEmail}'>
                <button type='button' id='changePassBTN' class='${dNoneIfStudent} ${dNoneIfCourse}btn btn-basic' data-toggle='collapse' data-target='#passKeeper'>Change Password</button>
                <div id='passKeeper' class='collapse ${dNoneIfCourse} ${dNoneIfStudent}form-group'>
                    <label for='${which}Password'>New Password</label>
                    <input type='password' class='form-control' id='${which}Password' placeholder='New Password'>
                    <div class='errMsg' id='passErrMsg'></div>
                </div>
                <div id='emailErrMsg' class='errMsg'></div>
            </div>
            <div class='imageUploadForm form-group'>
                <img id='imageDisplayer' class='previewImg' data-pre="${previewImage}" src='${previewImage}' alt='image' />
                    <ul class="navbar-nav removeImageOrNot-Main">
                <li><label for='${which}ImageUpload'>&nbsp;Change Image (optional): </label></li>    
                <li>&nbsp;<input type="checkbox" id="existingImageOpinion" onchange="removeImageTrigger(this);"/> Remove Existing</li>
                    </ul>
                <input class='form-control btn btn-info' type='file' name='${which}ImageUpload' id='${which}Image' onchange='checkUploadedImage(this);'
                />
                <div id='uploadImgErrMsg' class='errMsg' data-helper="${"images/defaultImage" + whichU + ".png"}"></div>
            </div>
            <div class='${dNoneIfCourse} ${dNoneIfAdministator}form-group'>
                <label for='${which}Courses'>
                    <strong>Courses: </strong>
                </label>
                <div id='${which}Courses' class='form-control'>
                    &nbsp;${texture}
                </div>
            </div>
        </form>
        <input id='hiddenInput' type='hidden' data-what='edit' data-which='${which}' data-who='${objectID}' />`;
    //show number of students assigned if course.
    switch (which) {
        case 'course':
            var numOfAssignedStudents = getObject.objectToKeep.objectSons.length;
            if (numOfAssignedStudents > 0) {
                $('#preDeleteBTN').attr("class", "d-n");
            }
            $('#updater').append("<div class='middle row'><label class='middle form-control'>Total&nbsp;" + numOfAssignedStudents + "&nbsp;students&nbsp;taking&nbsp;this&nbsp;course</label></div>")
            break;
        case 'administator':
            /// if owner cannot be chagned at all.
            if (!(getObject.objectToKeep.role === "Owner")) {
                /// know what is default for owner user to be able to change others.
                if (getObject.objectToKeep.role === "Sales") {
                    var otherRole = "Manager";
                } else {
                    var otherRole = "Sales";
                }
                ;
                $('#updater').append(`<div class='${dNoneEditIfAdministator} selectRoleCont'<label for='beautifulSelect'>Role&nbsp;<select id='beautifulSelect'>
                    <option>${getObject.objectToKeep.role}</option>
                    <option>${otherRole}</option>
                    </select></div>`);
            } else {
                /// if owner.. d-n is display none and v-v is visiblity hidden.
                $('#updater').append(`<div class='d-n v-v selectRoleCont'<label for='beautifulSelect'>Role&nbsp;<select id='beautifulSelect'>
                    <option>Owner</option>
                    </select></div>`);
                //cannot delete owner
                $('#preDeleteBTN').val("ownership transfer");
                $('#preDeleteBTN').attr("onclick", `preTransferOwnership(${objectID});`);
            }
            ;
            ///
            ///// manager cannot delete himself
            /// add role and icon
            let adRole = getObject.objectToKeep.role;
            switch (adRole) {
                //// manager cannot edit other managers
                case "Manager":
                    /// he could get here only if that's himself already..
                    if ((adminRole === "manager")) {
                        $('#preDeleteBTN').hide();
                    }
                    ;
                    break;
            }
            ;
            if (!(adminRole === "owner")) {
                /// only owner can change passwords, and he don't have to..
                $('#changePassBTN').hide();
            }
            ;
            break;
    }

    ///// don't let upload from the start....validate is not empty.
    letSaveEmail = true;
    letSavePhone = true;
    letSaveCName = true;
    letAddRole = true;
    /// this time NULL will mean, do not touch image in DB.
    letSaveImage = null;
    letSavePass = null;
    ///// prevent default submit.
    $('#updater').submit(false);
    //
    //handler of Password Changer Collapse
    changePassword = false;
    $('#passKeeper').on('shown.bs.collapse', function () {
        changePassword = true;
    });
    //
    $('#passKeeper').on('hidden.bs.collapse', function () {
        changePassword = false;
    });
}
;
///
//// Function
////// pre-delete
/// called by Delete Button in Edit Cont. pass to delete function the details needed by passing it to modal deleteBTN data.
function controlDelete(getObject) {
    $(".modal-title").html("Confirm Delete");
    $(".modal-body").html("<p>Are You Sure you want to Procceed?</p>");
    $(".modal-footer").html(`
<button id='deleteBTN' type="button" class="btn btn-danger" autofocus="autofocus">Delete</button>
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`);
    setTimeout(function () {
        $('#deleteBTN').attr("onclick", "deleteObject(" + JSON.stringify(getObject) + ");")
    }, 50);
}
;
///
//// Function
////// Delete Object
/// called by Delete Button delete confirmation modal
function deleteObject(getObject) {
    $('#deleteBTN').removeAttr("onclick");
    $('#popupDeleteAsk').modal('hide');
    $.post('Controllers/existEditor/' + getObject.which + 'Controller.php', {objectID: getObject.objectID, imageDir: getObject.imageDir, which: getObject.which, deleteObject: null}, function (response) {
        response = JSON.parse(response);
        if (response[1] === "success" || response[1] === "1success") {
            /// if success, transport to main container
            setTimeout(function () {
                getAllAsked(response[0], showNum = true);
            }, 120);
            /// couldn't delete
        } else {
            $('#addingErrMsg').text("Couldn't Delete " + response[0] + ", please try again later.");
            setTimeout(function () {
                showNumbers();
            }, 4000);
        }
        ;
    });
}
;
///
//// Function
////// Update Object Send via $.ajax
/// called by Function saveObject which called by Save Button in Edit Cont
function saveNUpdate(formData, which) {
    $.ajax({
        /// it gets in form Data 'edit'+which+'' , in post method for else if querys
        url: "Controllers/existEditor/" + which + "Controller.php",
        type: "POST",
        data: formData,
        success: function (response) {
            let addingErrMsg = document.getElementById('addingErrMsg');
            // request to php worked.
            addingErrMsg.style.display = "none";
            /// that's an array, [0] is id [1] is response
            response = JSON.parse(response);
            if (response[1] === "success.") {
                //// everything went smooth, let the user know it.
                /// affect main container and display object data
                var sendObject = {objectID: response[0], which: which};
                setTimeout(function () {
                    getSpecificObject(sendObject);
                }, 240);
                setTimeout(function () {
                    getAllAsked(which);
                }, 200);
            } else {
                /// oops, something happened..
                addingErrMsg.innerHTML = response[1];
                addingErrMsg.style.display = "block";
                setTimeout(function () {
                    addingErrMsg.style.display = "none";
                }, 4000);
            }
            ;
        },
        cache: false,
        contentType: false,
        processData: false
    });
}
;


//
///Function removeImageTrigger - called by edit Cont - checkbox.
/// let the user enjoy preview image in live event listening to his opinion
function removeImageTrigger(checkbox) {
    let previous = $('#imageDisplayer').attr("data-pre");
    let defaultive_1 = $('#uploadImgErrMsg').attr("data-helper");
    if (checkbox.checked && (!(letSaveImage))) {
        $('#imageDisplayer').attr("src", defaultive_1);
    } else if (((!(letSaveImage)) || letSaveImage === null) && (!(checkbox.checked))) {
        $('#imageDisplayer').attr("src", previous);
    }
    ;
}
;

//
///Function
//// ajax post call, delete existing image, called if chosen from saveObject function. edit case;
function removeExistingImage(id, which) {
    $.ajax({
        url: 'Controllers/existEditor/' + which + 'Controller.php',
        type: "POST",
        data: {id: id, removeExistingImage: true},
        success: function (response) {
            //no need to response in this case.
        },
    });
}
;

//
///Function
//// build modal for ownership trasfer if owner want to
function preTransferOwnership(ownerID) {
    letOwnerTransfer = false;
    $(".modal-title").html("New Owner");
    $(".modal-body").html(`
    <label> New Owner: </label>
    <input id="newOwnerEmail" type="text" data-id=${ownerID} placeholder="New Owner Email" onblur="checkIfFoundAdmin(this)" autofocus="autofocus"/>
    <p>Once Transfer happens, you will be logged out and your role will change to Manger.
    <br/>Are you sure you want to proceed?</p>`);
    $(".modal-footer").html(`
<button id='deleteBTN' type="button" class="btn btn-danger">Transfer</button>
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`);
    setTimeout(function () {
        $('#deleteBTN').attr("onclick", `trasferOwnership(${ownerID});`)
    }, 50);
    // enter while in newOwnerEmail input
    $('#newOwnerEmail').on('keyup', (e) => {
        if (e.keyCode == 13) {
            $('#deleteBTN').focus();
            trasferOwnership(ownerID);
        }
        ;
    });
}
;

//
///Function
//// onlur called by new owner input, does ajax call to check in Db existance except of owner id;
function checkIfFoundAdmin(it) {
    let ownerID = it.dataset.id;
    let emailToFind = $(it).val();
    $.ajax({
        url: 'Controllers/existEditor/administatorController.php',
        type: "POST",
        data: {id: ownerID, email: emailToFind, getAdminToTransfer: true},
        success: function (response) {
            ///1 means found
            if (response == "1") {
                letOwnerTransfer = true;
                $(it).css("border", "2px solid green");
            } else {
                letOwnerTransfer = false;
                $(it).css("border", "3px solid red");
            }
            ;
        },
    });
}
;

//
///Function
//// ajax call, change owner role to maanger and admin chosen to new owner to owner, if worked - logging out owner;
function trasferOwnership(ownerID) {
    let newOwnerEmail = $('#newOwnerEmail').val();
    //check already found an admin to transfer
    if (letOwnerTransfer && newOwnerEmail.length > 0) {
        //
        $.ajax({
            url: 'Controllers/existEditor/administatorController.php',
            type: "POST",
            data: {previousOwnerid: ownerID, newOwnerEmail: newOwnerEmail, transferOwnership: true},
            success: function (response) {
                ///1 means worked
                if (response == "1") {
                    //logout
                    window.location.href = "Controllers/unsetSession.php";
                } else {
                    $(".modal-footer").html(`<div class="errMsg form-control" style="display:block">Error Ouccured, please try again later.</div>`);
                    setTimeout(function () {
                        $('#popupDeleteAsk').modal('hide');
                    }, 3000);
                }
                ;
            },
        });
    }
    ;
}
;