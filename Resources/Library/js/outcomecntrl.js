$(document).ready(function(){
    var item = $('#goalstlist').val();
    control(item);// onload it will call the function
});

$('#goalstlist').change(function (){
    control(this.value);
});

function control(select) {
    var status = select;

    var goalAchContainer = $('#goalachcont');
    var goalAch = $('#goalachtext');

    var goalResUtilContainer = $('#goalresutilcont');
    var goalResUtil = $('#goalresutiltext');

    var goalContiContainer = $('#goalconticont');
    var goalConti = $('#goalcontitext');

    var goalIncompContainer = $('#goalincompcont');
    var goalIncomp = $('#goalincomptext');

    var goalUpcominContainer = $('#goalupcomincont');
    var goalUpcomin = $('#goalupcomintext');

    var resNeedContainer = $('#resoneedcont');
    var resNeed = $('#resoneedtext');

//  Initial stage to reset items when choice is changed.

    goalAchContainer.addClass('hidden');
    goalAch.removeAttr('required');

    goalResUtilContainer.addClass('hidden');
    goalResUtil.removeAttr('required');

    goalContiContainer.addClass('hidden');
    goalConti.removeAttr('required');

    goalUpcominContainer.addClass('hidden');
    goalUpcomin.removeAttr('required');

    resNeedContainer.addClass('hidden');
    resNeed.removeAttr('required');

    goalIncompContainer.addClass('hidden');
    goalIncomp.removeAttr('required');

    // choice update actions

    if (status != 3 && status != 7 && status != 8 && status != 0) {

        goalAchContainer.removeClass('hidden');
        goalAch.attr('required');

        goalResUtilContainer.removeClass('hidden');
        goalResUtil.attr('required');


        if (status == 4 || status == 5) {

            goalContiContainer.removeClass('hidden');
            goalConti.attr('required');

            goalUpcominContainer.removeClass('hidden');
            goalUpcomin.attr('required');

            resNeedContainer.removeClass('hidden');
            resNeed.attr('required');

        } else {

            if (status == 6) {
                goalContiContainer.removeClass('hidden');
                goalConti.attr('required');

                goalIncompContainer.removeClass('hidden');
                goalIncomp.attr('required');

                resNeedContainer.removeClass('hidden');
                resNeed.attr('required');
            }
        }
    } else {
        if (status != 0) {
            goalAchContainer.addClass('hidden');
            goalAch.removeAttr('required');

            goalResUtilContainer.addClass('hidden');
            goalResUtil.removeAttr('required');

            if (status == 7) {
                resNeedContainer.removeClass('hidden');
                resNeed.attr('required');
            }
        }
    }
}

// $('#savebtn').on('click',function () {
//
//     $('#savebtn').addClass('hidden');
//     $('#cancelbtn').addClass('hidden');
//     $('#approve').removeClass('hidden');
//
// });