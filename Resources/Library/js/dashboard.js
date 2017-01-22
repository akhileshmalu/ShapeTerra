$(function() {
	$(".provost-card").click(function(){
		$year = $(this).attr("id");
		$(".provost-dropdown#" + $year).slideToggle();

	})
})