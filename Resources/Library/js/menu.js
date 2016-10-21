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

	$('#tabs a').click(function (e) {
  		e.preventDefault()
  		$(this).tab('show')
	})

	var url = document.location.toString();
if (url.match('#')) {
    $('#tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
} 

// Change hash for page-reload
// $('#tabs a').on('shown.bs.tab', function (e) {
//     window.location.hash = e.target.hash;
// })
})