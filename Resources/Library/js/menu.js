$(function() {
	$("#menu li#header a").click(function(){
		var className = this.className;
		if($("a#" + className).hasClass("hidden")){
			$("a#" + className).addClass("animated fadeIn");
			$("a#" + className).removeClass("hidden");
			$("span#" + className).addClass("caret-reversed")
		}else{
			$("a#" + className).addClass("hidden");
			$("span#" + className).removeClass("caret-reversed")
		}
   		
	});
})