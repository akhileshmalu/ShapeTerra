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



// $("#unitgoalbtn").click(function(){
//     document.getElementById("unitgoalbtn").setAttribute("data-dismiss","modal");
//     document.getElementById("unitgoalbtn").setAttribute("aria-label","Close");
// });


// $(document).ready(function() {
//     $("#unitgoalbtn").click(function() {
//
//
//         var AY = $('input[name=AY]').val();
//         var goaltitle = $('input[name=goaltitle]').val();
//         var goallink = $('input[name=goallink]').val();
//         var goalstatement = $('input[name=goalstatement]').val();
//         var goalalignment = $('input[name=goalalignment]').val();
//
//     $.post("approvebp.php", {
//         AY: AY,
//         goaltitle: goaltitle,
//         goallink: goallink,
//         goalstatement: goalstatement,
//         goalalignment: goalalignment
//     }, function(data) {
//         document.getElementById("unitgoalbtn").setAttribute("data-dismiss","modal");
//         document.getElementById("unitgoalbtn").setAttribute("aria-label","Close");
//     });
//     return false;
//     });
// });
