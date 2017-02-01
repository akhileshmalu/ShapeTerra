$(document).ready(function(){
    var item = $('#goalstlist').val();
    control(item);// onload it will call the function
});

$('#goalstlist').change(function (){
    control(this.value);
});

function control(select) {
    var k = select;

    if (k != 3 && k != 7 && k != 8) {
        $('#goalachtext').attr('required');
        $('#goalresutil').removeClass('hidden');
        $('#goalresutiltext').attr('required');


        var n = $('#resoneed');
        var p = $('#goalconti');
        if (k == 4 || k == 5) {
            $('#resoneedlable').removeClass('hidden');
            n.removeClass('hidden');
            $('#resoneedtext').attr('required');

            p.removeClass('hidden');
            $('#goalcontilable').removeClass('hidden');
            $('#goalcontitext').attr('required');
        } else {
            n.addClass('hidden');
            $('#resoneedlable').addClass('hidden');
            $('#resoneedtext').removeAttr('required');

            p.addClass('hidden');
            $('#goalcontilable').addClass('hidden');
            $('#goalcontitext').removeAttr('required');
        }

    } else {
        $('#goalachtext').removeAttr('required');

        $('#goalresutil').addClass('hidden');
        $('#goalresutiltext').removeAttr('required');

    }

    // if (k == 3 || k == 7 || k == 8) {
    //
    // } else {
    //
    // }



}

// $('#savebtn').on('click',function () {
//
//     $('#savebtn').addClass('hidden');
//     $('#cancelbtn').addClass('hidden');
//     $('#approve').removeClass('hidden');
//
// });