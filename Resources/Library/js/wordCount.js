	
$('textarea.wordCount').each(function(i, obj) {
    var max_length = $(obj).attr("maxlength");
	var id = $(obj).attr("id");

	$( "<p id='"+ id +"' class='word-count status pull-right'>" + max_length + " remaining</p>" ).insertAfter( obj );

	$(obj).keyup(function() {
		var text_length = $(obj).val().length;
	  	var text_remaining = max_length - text_length;

	  	var textid = $(this).attr("id");
	  
	  	$('.word-count#' + id).html(text_remaining + ' remaining');
	});
});




