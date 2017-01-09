var $tabs = $('.tabs-nav li');
$('.changeTab1').click(function () {


    var nextcheck = true;


    if ($("input[name ='functionalname']").val() == "") {
        alert("Please enter Functional Name.");
        nextcheck = false;

    } else {
        if ($("input[name ='technicalname']").val() == "") {
            alert("Please enter Technical Name.");
            nextcheck = false;
        } else {
            if ($("select[name ='dataclass']").val() == "") {
                alert("Please select data classification value.");
                nextcheck = false;

            } else {
                if (!$.trim($("textarea[name ='basicmean']").val())) {
                    alert("Please enter Basic Meaning.");
                    nextcheck = false;
                } else {
                    if ($("input[name ='timebasis']:checked").length == 0) {
                        alert("Please select Time Basis for Outcome.");
                        nextcheck = false;
                    } else {
                        if ($("input[name ='bptopic[]']:checked").length == 0) {
                            alert("Please select Blueprint Topic.");
                            nextcheck = false;
                        } else {
                            if ($("#funcname_status").val() !== "") {
                                alert("Please enter unique function name.");
                                nextcheck = false;
                            } else {

                                if (nextcheck) {

                                    $tabs.filter('.active').removeClass('active').next('li').removeClass('disabled').addClass('active');
                                    var className = $tabs.filter('.active').attr("class").split(' ')[0];
                                    $("#actionlist.active").addClass("hidden");
                                    $("#actionlist." + className).removeClass("hidden");
                                    $("#actionlist." + className).addClass("active");
                                    window.scrollTo(0, 0);
                                }
                            }
                        }

                    }

                }

            }

        }

    }


});

$('.changeTab2').click(function () {


    var nextcheck = true;

    if (!$.trim($("textarea[name ='datasource']").val())) {
        alert("Please enter Data Source.");
        nextcheck = false;

    } else {
        if ($("input[name ='resparty']").val() == "") {
            alert("Please enter Technical Name.");
            nextcheck = false;
        } else {
            if ($("input[name ='datatype']").val() == "") {
                alert("Please select data type.");
                nextcheck = false;

            } else {
                if ($("input[name ='valuemand']").val() == "") {
                    alert("Please Select Value Mandatory.");
                    nextcheck = false;
                } else {

                    if (nextcheck) {
                        $tabs.filter('.active').removeClass('active').next('li').removeClass('disabled').addClass('active');
                        var className = $tabs.filter('.active').attr("class").split(' ')[0];
                        $("#actionlist.active").addClass("hidden");
                        $("#actionlist." + className).removeClass("hidden");
                        $("#actionlist." + className).addClass("active");
                        window.scrollTo(0,0);

                    }
                }

            }

        }

    }

});

$($tabs).click(function () {
    var className = $(this).attr("class").split(' ')[0];
    var status = $(this).attr("class").split(' ')[1];
    if (status != "disabled") {
        $tabs.filter('.active').removeClass('active')
        $(this).addClass("active");
        $("#actionlist.active").addClass("hidden");
        $("#actionlist." + className).removeClass("hidden");
        $("#actionlist." + className).addClass("active");
    }
});
$($tabs).hover(function () {
    var status = $(this).attr("class").split(' ')[1];
    if (status != "disabled") {
        $(this).css("text-decoration", "underline");
    }
}, function () {
    $(this).css("text-decoration", "none");

});