// script is on bottom, when load.. start the magic.
window.onload = () => {
    //for make sure user have loading feel..
    setTimeout(() => {
        getAllDeals(buildTable);
    }, 50);

};
//
/// function
// called by Onload Function - go to database and check for all deals, get parameter which function to trigger when finish.
let getAllDeals = (callbackFuncs) => {
    let formData = new FormData();
    formData.append('getAllDeals', "action");
    $.ajax({
        url: 'Controllers/multipleController2.php',
        type: "POST",
        data: formData,
        success: function (response) {
            $('thead').append(`<tr><th>Courses</th><th>Students</th></tr>`);
            if ((response === "2")) {
                ///empty array
                $('#deals_table').DataTable();
                $('#loading').hide();
            } else if (!(response === "1")) {
                ///everything is ok
                callbackFuncs(response);
                $('#loading').hide();
            } else {
                /// that's probably 1
                //error occured..
                $('#tBody').html("error occured. please try again later.");
            }
        },
        cache: false,
        contentType: false,
        processData: false
    });
};


///
///Function
//// Build Table, get called and gets deals array - insert it into a table.  setting table ready to view.
let buildTable = (deals) => {
    deals = JSON.parse(deals);
    $(deals).each((i, deal) => {
        if (deal.course_image === null) {
            //// give deafault image if needed.
            deal.course_image = 'images/defaultImageCourse.png';
        }
        ;
        if (deal.student_image === null) {
            //// give deafault image if needed.
            deal.student_image = 'images/defaultImageStudent.png';
        }
        ;
        ///append rows
        $('#tBody').append(`<tr data-id="${deal.deal_id}">
                        <td>
                            <img class='standardImgTable borderedTableImgCourse' src="${deal.course_image}"/>
                            <label>${deal.course_name}</label>
                        </td>
                         <td>
                            <img class='standardImgTable borderedTableImgStudent' src="${deal.student_image}"/>
                            <label>${deal.student_name}</label>
                        </td>
                    </tr>
        `);
    });
    $('#loading').hide();
    $('#deals_table').DataTable();
    //
    getAllCourses(deals);
};

///
///Function
//// after building the table and user can see it, go and get all courses options for creating pie graph of selling courses.
let getAllCourses = (deals) => {
    let formData = new FormData();
    formData.append('getAll', 'action');
    $.ajax({
        url: 'Controllers/adderAndAll/coursesController.php',
        type: "POST",
        data: formData,
        success: function (courses) {
            createGraph(deals, courses);
        },
        cache: false,
        contentType: false,
        processData: false
    });
};

///
///Function
//// this function gets the deals and courses arrays and check which course has been sold and how many times for setting it in the graph.
let createGraph = function (deals, courses) {
    courses = JSON.parse(courses);
    $('#chartContainer').css("height", "320px");
    let dealsArray = [];
    let stop = false;
    for (let i = 0; i < courses.length; i++) {
        let counter = 0;
        let currentCourse = courses[i];
        for (let j = 0; j < deals.length; j++) {
            if (currentCourse.name === deals[j].course_name) {
                counter++;
            }
            ;
        }
        ;
        dealsArray.push({label: currentCourse.name, y: counter});
        if (counter === 0 && stop === false) {
            /// in case there are courses which not selling , let them know specially.
            stop = true;
            $('#chartContFatherow').prepend(`<img class='noSellsMsg col-xs-12 col-sm-12 col-md-12 col-lg-4'
                src='images/coursesNoSellings.svg' alt='motivation message'/>`);
            $('#chartContainer').attr("class", "col-xs-12 col-md-12 col-lg-8");
        }
        ;
        counter = 0;
    }
    ;
    let  options = {
        title: {
            text: "Courses Sellings"
        },
        data: [{
                type: "pie",
                startAngle: 45,
                showInLegend: "true",
                legendText: "{label} (#percent%)",
                indexLabel: "{label} ({y})",
                yValueFormatString: "#,##0.#" % "",
                dataPoints: dealsArray
            }]
    };
    $("#chartContainer").CanvasJSChart(options);
    $('#chartContainer a').css("margin-top", "20px");
    appendParallaxer();
};
///
///Function
//// called after chartBuilding, adding parallax at side of window.
let appendParallaxer = () => {
    $('html').first("body").append(`<div class="parallaxer">
        <a class='parallaxers parallaxerGraph' href='#chartContFatherow'><p><i class="fas fa-chart-pie"></i></p></a>
    <a class='parallaxers parallaxerTable' href='#dealsTableH1'><p><i class="fas fa-table"></i></p></a>
    </div>`);
    $('.parallaxers').mouseover(function () {
        $(this).css("left", "8%");
    });
    $('.parallaxers').mouseout(function () {
        $(this).css("left", "-1%");
    });
};