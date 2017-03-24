function showVisualData() {

  var datatablename = $('#data-table-name').html().substr(30);
  var academicYear = $('#fuayname').html();
  var ouchoice = $('#ou').val();
  var functionNumber = 0;

  switch(datatablename) {

    case "IR_AC_Enrollments":
      functionNumber = 1;
      break;

    case "IR_AC_DiversityStudent":
      functionNumber = 2;
      break;

    case "IR_AC_DiversityPersonnel":
     functionNumber = 3;
      break;

    default :
      functionNumber=0;
          break;
  }

  // if(!functionNumber)
  $("#dataValidation").load("../Resources/Includes/ChartVisualizations.php?functionNum="+functionNumber+"&yearDescription="+academicYear+"&ouchoice="+ouchoice,function(){
    console.log("successfully loaded data");
  });
}
