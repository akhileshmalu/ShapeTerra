$(document).ready(function(){
    $('#ckbCheckAll').click(function(event) {
        if($(this).is(":checked")) {
            $('.checkBoxClass').each(function(){
                $(this).prop("checked",true);
            });
        }
        else{
            $('.checkBoxClass').each(function(){
                $(this).prop("checked",false);
            });
        }
    });
});