//function to check username availability
function check_availability_func() {

    //get the username
    var funcname = $('#fname').val();
    //use ajax to run the check
    $.post("../Pages/taskboard/checkunique.php", {funcname: funcname},
        function (result) {
            //if the result is 1
            if (result == 0) {
                //show that the username is available
                $('#funcname_status').attr('style', 'color:red;').html(funcname + ' is not unique');
            } else {
                $('#funcname_status').attr('style', 'color:green;').html(funcname + ' is unique');
            }
        });

}
function check_availability_tech() {

    //get the username
    var tecname = $('#tecname').val();
    //use ajax to run the check
    $.post("../Pages/taskboard/checkunique.php", {tecname: tecname},
        function (result) {
            //if the result is 1
            if (result == 0) {
                //show that the username is available
                $('#tecname_status').attr('style', 'color:red;').html(tecname + ' is not unique');
            } else {
                $('#tecname_status').attr('style', 'color:green;').html(tecname + ' is unique');
            }
        });

}
function check_availability_ftitle() {

    //get the username
    var ftitle = $('#ftitle').val();
    //use ajax to run the check
    $.post("../Pages/taskboard/checkunique.php", {ftitle: ftitle},
        function (result) {
            //if the result is 1
            if (result == 0) {
                //show that the username is available
                $('#ftitle_status').attr('style', 'color:red;').html(ftitle + ' is not unique');
            } else {
                $('#ftitle_status').attr('style', 'color:green;').html(ftitle + ' is unique');
            }
        });

}