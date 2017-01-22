$('.alert').fadeIn("slow", function() {
    $('.overlay').removeClass("hidden");
});

$('.alert .end').click(function(){
	$('.alert').fadeOut("fast");
	$('.overlay').fadeOut("fast", function() {
    	$('.overlay').addClass("hidden");
	});
	$redirect = $('.alert button').attr('redirect');
	$(window).attr('location',$redirect)
});