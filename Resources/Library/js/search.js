jQuery.expr[':'].contains = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
      .indexOf(m[3].toUpperCase()) >= 0;
};

$(function() {

	$("#search-box").on("change paste keyup", function() {
		$(".card").hide().filter(':contains(' + $(this).val()  + ')').show();
	});
})


