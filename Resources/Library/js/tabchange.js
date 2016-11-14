var $tabs = $('.tabs-nav li');

$('.changeTab').click(function () {
    $tabs.filter('.active').removeClass('active').next('li').removeClass('disabled').addClass('active');
    
    var className = $tabs.filter('.active').attr("class").split(' ')[0];

    $("#actionlist.active").addClass("hidden");
    $("#actionlist." + className).removeClass("hidden");
    $("#actionlist." + className).addClass("active");
});

$($tabs).click(function(){
    var className = $(this).attr("class").split(' ')[0];
    var status = $(this).attr("class").split(' ')[1];
    if(status != "disabled"){

        $tabs.filter('.active').removeClass('active')
        $(this).addClass("active");
        $("#actionlist.active").addClass("hidden");
        $("#actionlist." + className).removeClass("hidden");
        $("#actionlist." + className).addClass("active"); 
    }
});

$($tabs).hover(function(){
    var status = $(this).attr("class").split(' ')[1];
    if(status != "disabled"){
        $(this).css("text-decoration", "underline");
    }
    }, function(){
        $(this).css("text-decoration", "none");
    });
