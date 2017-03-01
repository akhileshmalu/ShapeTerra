
$(function() {

	$('input[name="optionalCheck"]').on('change', function() {

		$textarea = $(this).attr("id");
		var r;
		if($('textarea[name="' + $textarea + '"]').val().length > 0 && !$('textarea[name="' + $textarea + '"]').attr('disabled')){	
			r = confirm("Selecting this option will leave this section blank regardless of your current input, are you sure you wish to select this?");
			if(!r){
				$(this).attr('checked', false);
				return;
			}
		}	

		
		if($('textarea[name="' + $textarea + '"]').attr('disabled')){
			$('textarea[name="' + $textarea + '"]').attr('disabled', false);
		}else if(!$('textarea[name="' + $textarea + '"]').attr('disabled')){
			$('textarea[name="' + $textarea + '"]').attr('disabled', true).val('');

		}
    	
	});
});

//.attr("disabled","disabled");