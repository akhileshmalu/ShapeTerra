
$('.cancelbox').on("click",function () {
   var choice = confirm("Are you sure you want to cancel");
if(choice == true){
    $(window).attr('location','bphome.php')
}
});


$('.cancelFUbox').on("click",function () {

    var ay = document.getElementById('fuayname');
    var te = ay.value;
    alert(te);
    var choice = confirm("Are you sure you want to cancel");
    if(choice == true){
        $(window).attr('location','fileuploadhome.php?ayname='+ay)
    }
});

$('.canceldatadictbox').on("click",function () {
    var choice = confirm("Are you sure you want to cancel");
    if(choice == true){
        $(window).attr('location','datadicthome.php')
    }
});