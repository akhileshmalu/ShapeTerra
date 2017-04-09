$(document).ready(function() {
    $('.cancelbox').on("click", function () {
        var choice = confirm("Are you sure you want to cancel");
        if (choice == true) {
            $(window).attr('location', 'bphome.php')
        }
    });

    $('.cancelbpbox').on("click", function () {

        var ayname = $('#ayname').text();
        var ouname = $('#ouabbrev').text();
        if (confirm("Are you sure you want to cancel")) {
            $(window).attr('location', 'bphome.php?ayname=' + ayname + "&ou_abbrev=" + ouname);
        }
    });

    $('.cancelFUbox').on("click", function () {

        var ay = document.getElementById('fuayname');
        var te = ay.value;
        var choice = confirm("Are you sure you want to cancel");
        if (choice == true) {
            $(window).attr('location', 'fileuploadhome.php?ayname=' + ay)
        }
    });

    $('.canceldatadictbox').on("click", function () {
        var choice = confirm("Are you sure you want to cancel");
        if (choice == true) {
            $(window).attr('location', 'datadicthome.php')
        }
    });
});