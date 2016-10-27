$( document ).ready(function() {
  var rightVal = 0; // base value

    $("tr#year").click(function () {
        yearID = $(this).attr('class');
        $("tr#goal.active").addClass("hidden");
        $("tr#goal.active").removeClass("active");


         $("tr#goal." + yearID).removeClass("hidden");
         $("tr#goal." + yearID).addClass("active");


    });

    $("tr#goal").click(function () {
        goalID = $(this).attr('class').split(' ')[0];
        $("aside.active").animate({right: -850 + 'px'}, {duration: 500});
        $("aside.active").addClass("hidden");
        $("aside.active").removeClass("active");

         $("aside." + goalID).removeClass("hidden");
         $("aside." + goalID).addClass("active");
         $("aside." + goalID).css("display", "block");

         $("aside." + goalID).animate({right: rightVal + 'px'}, {queue: false, duration: 500});

    });

});

