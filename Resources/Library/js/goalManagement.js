$( document ).ready(function() {

    $("tr#year").click(function () {
        goalID = $(this).attr('class').split(' ')[0];
        $("aside.active").addClass("hidden");
        $("aside.active").removeClass("active");

         $("aside." + goalID).removeClass("hidden");
         $("aside." + goalID).addClass("active");
         $("aside." + goalID).css("display", "block");

    });

});

