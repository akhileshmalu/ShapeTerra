
$('.cancelbox').on("click",function () {
   var choice = confirm("Are you sure you want to cancel");
if(choice == true){
    $(window).attr('location','bphome.php')
}
});
