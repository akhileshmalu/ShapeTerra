$(function() {
	$("#signupShow").click(function() {
        $("#signupShow").addClass("hidden");
        $("#signup").removeClass("hidden");
  		$("#forgot-link").addClass("hidden");
        $("#confirm-password").addClass("animated fadeInDown")
        $("#confirm-password").removeClass("hidden");
        $("#signup").css("background-color", "white").css("color", "#73000a");
        $("#back-link").addClass("animated fadeInUp");
        $("#back-link").removeClass("hidden")
        $("#login-button").addClass("animated fadeOut")
        $("#login-button").addClass("hidden");
	});

	$("#back-link").click(function(){
		$("#forgot-link").removeClass("hidden");
        $("#confirm-password").removeClass("fadeInDown");
        $("#confirm-password").addClass("hidden");
        $("#signup").css("background-color", "#73000a").css("color", "white");
        $("#back-link").removeClass("animated fadeInUp");
        $("#back-link").addClass("hidden")
        $("#login-button").removeClass("animated fadeOut")
        $("#login-button").removeClass("hidden");
	});
});