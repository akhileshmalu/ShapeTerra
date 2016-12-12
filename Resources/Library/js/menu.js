$(function() {
	$("#menu li#header a").click(function(){
		var className = this.className;
		if($("a#" + className).hasClass("hidden")){
			$("a#" + className).addClass("animated fadeIn");
			$("a#" + className).removeClass("hidden");
			$("span#" + className + ".plus").addClass("hidden")
			$("span#" + className + ".minus").removeClass("hidden")
		}else{
			$("a#" + className).addClass("hidden");
			$("span#" + className).removeClass("add")
			$("span#" + className + ".plus").removeClass("hidden")
			$("span#" + className + ".minus").addClass("hidden")
		}
   		
	});


})