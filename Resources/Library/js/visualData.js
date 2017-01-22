function showAcademicYearView(academicYear){
  //testing purposes only
  $("#academic-chart").load("../Resources/Includes/visualData.php?functionNum=1&yearDescription="+academicYear,function(){
    console.log("successfully loaded data");
  });
}

function showFacultyDataByYear(academicYear){
  $("#faculty-chart").load("../Resources/Includes/visualData.php?functionNum=2&yearDescription="+academicYear,function(){
    console.log("successfully loaded data");
  });
}

function showDiversityStudentDataByYear(academicYear){
  $("#student-diversity-chart").load("../Resources/Includes/visualData.php?functionNum=3&yearDescription="+academicYear,function(){
    console.log("successfully loaded data");
  });
}

function showDiversityFacultyDataByYear(academicYear){
  $("#faculty-diversity-chart").load("../Resources/Includes/visualData.php?functionNum=4&yearDescription="+academicYear,function(){
    console.log("successfully loaded data");
  });
}
