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



$("#missionbtn").click(function(){
    var y =  document.getElementById('missiontitle');
    var x = document.getElementById('missionstate');
    y.innerHTML =x.value;
    y.value =x.value;
    y.setAttribute("readonly","readonly");
    document.getElementById("missionbtn").setAttribute("data-dismiss","modal");
    document.getElementById("missionbtn").setAttribute("aria-label","Close");
});

$("#visionbtn").click(function(){
    var y =  document.getElementById('visiontitle');
    var x = document.getElementById('visionstate');
    y.innerHTML =x.value;
    y.value =x.value;
    y.setAttribute("readonly","readonly");
    document.getElementById("visionbtn").setAttribute("data-dismiss","modal");
    document.getElementById("visionbtn").setAttribute("aria-label","Close");
});

$("#valuebtn").click(function(){
    var y =  document.getElementById('valuetitle');
    var x = document.getElementById('valuestate');
    y.innerHTML =x.value;
    y.value =x.value;
    y.setAttribute("readonly","readonly");
    document.getElementById("valuebtn").setAttribute("data-dismiss","modal");
    document.getElementById("valuebtn").setAttribute("aria-label","Close");
});
// $("#unitgoalbtn").click(function(){
//     document.getElementById("unitgoalbtn").setAttribute("data-dismiss","modal");
//     document.getElementById("unitgoalbtn").setAttribute("aria-label","Close");
// });


$(document).ready(function() {
    $("#unitgoalbtn").click(function() {

// function Submitdata() {
//     var formData = {
//         'AY': $('input[name=AY]').val(),
//         'goaltitle': $('input[name=goaltitle]').val(),
//         'goallink': $('input[name=goallink]').val(),
//         'goalstatement': $('input[name=goalstatement]').val(),
//         'goalalignment': $('input[name=goalalignment]').val()
//     };

//     // process the form
//     $.ajax({
//         type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
//         url: 'approvebp.php', // the url where we want to POST
//         data: formData // our data object
//     });
//
// }
        var AY = $('input[name=AY]').val();
        var goaltitle = $('input[name=goaltitle]').val();
        var goallink = $('input[name=goallink]').val();
        var goalstatement = $('input[name=goalstatement]').val();
        var goalalignment = $('input[name=goalalignment]').val();

    $.post("approvebp.php", {
        AY: AY,
        goaltitle: goaltitle,
        goallink: goallink,
        goalstatement: goalstatement,
        goalalignment: goalalignment
    }, function(data) {
        document.getElementById("unitgoalbtn").setAttribute("data-dismiss","modal");
        document.getElementById("unitgoalbtn").setAttribute("aria-label","Close");
    });
    return false;
    });
});

