function switchAcademicYearView(academicYear){

  //testing purposes only
  $("#academic-chart").load("../Resources/Includes/visualdata.php?functionNum=1&yearDescription="+academicYear,function(){
    console.log("successfully loaded data");
  });

}

function showStudentDataByYear(academicYear){



}

function showFaculityDataByYear(){

}
