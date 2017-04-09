$('#AYgoal').on('change',function (e) {

    var that = $("option:selected",this),
        start = that.attr('dummy1'),
        end =that.attr('dummy2'),
        cencus =that.attr('dummy3');

    $('#startdate').val(start);
    $('#enddate').val(end);
    $('#cencusdate').val(cencus);

    $('#editdates').removeClass("hidden");

    /*
    To restrcit change of selection
     */
    $('#AYgoal option:not(:selected)').prop('disabled', true);


});

$('#datetimepicker1').datetimepicker({
    viewMode: 'years',
    format: 'MM/DD/YYYY'
});

$('#datetimepicker2').datetimepicker({
    viewMode: 'years',
    format: 'MM/DD/YYYY'
});


$('#datetimepicker3').datetimepicker({
    viewMode: 'years',
    format: 'MM/DD/YYYY'
});

