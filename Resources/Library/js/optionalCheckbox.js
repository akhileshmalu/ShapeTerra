$(function () {
    $('input[class="optionalCheck"]').on('change', function() {

        $textarea = $(this).attr("id");
        var r;
        if ($('textarea[name="' + $textarea + '"]').val().length > 0 && !$('textarea[name="' + $textarea + '"]').attr('disabled')) {
            $(this).attr("value", "1");

            r = confirm("Selecting this option will leave this section blank regardless of your current input, are you sure you wish to select this?");
            if (!r) {
                $(this).attr('checked', false);
                return;
            }
        }

        if ($('textarea[name="' + $textarea + '"]').attr('disabled')) {
            val = $(this).attr("value", "0");
            $(this).attr('checked', false);
            $('textarea[name="' + $textarea + '"]').attr('disabled', false);
        } else if (!$('textarea[name="' + $textarea + '"]').attr('disabled')) {
            val = $(this).attr("value", "1");
            $(this).attr('checked', true);
            $('textarea[name="' + $textarea + '"]').attr('disabled', true).val('');

        }

    });

});



// One time confirm box by pass to process onload value of checkbox form database.
// var realConfirm=window.confirm;
// window.confirm=function(){
//     window.confirm=realConfirm;
//     return true;
// };

//.attr("disabled","disabled");