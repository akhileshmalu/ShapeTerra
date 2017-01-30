jQuery.expr[':'].contains = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
      .indexOf(m[3].toUpperCase()) >= 0;
};

$(function() {

	$("#search-box").on("change paste keyup", function() {
		$(".card").hide().filter(':contains(' + $(this).val()  + ')').show();

		$(".provost-dropdown").filter(':contains(' + $(this).val()  + ')').each(function(i, obj) {
			var $id = $(obj).attr("id");
			$(".card#" + $id).show();
		});

		$(".provost-dropdown").removeClass('noDisplay').hide().filter(':contains(' + $(this).val()  + ')').show();
		$("ul.items").hide().filter(':contains(' + $(this).val()  + ')').show();
	});
})


