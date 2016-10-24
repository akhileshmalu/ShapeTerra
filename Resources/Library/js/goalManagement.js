$( document ).ready(function() {
  var rightVal = 0; // base value
    $("tr").click(function () {
        goalID = $(this).attr('class');
        $("aside.active").animate({right: -850 + 'px'}, {duration: 500});
        $("aside.active").addClass("hidden");
        $("aside.active").removeClass("active");
        
        // // calculate new value
        // $("a.active").removeClass("active")
        // $( this ).addClass( "active" );


         $("aside." + goalID).removeClass("hidden");
         $("aside." + goalID).addClass("active");
         $("aside." + goalID).css("display", "block");

         $("aside." + goalID).animate({right: rightVal + 'px'}, {queue: false, duration: 500});

    });

});

