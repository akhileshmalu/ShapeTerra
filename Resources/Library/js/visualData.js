function showAcademicYearView(academicYear){
  //testing purposes only
  $("#academic-chart").load("../Resources/Includes/ChartVisualizations.php?functionNum=1&yearDescription="+academicYear,function(){
  });
}

function showFacultyDataByYear(academicYear){
  $("#faculty-chart").load("../Resources/Includes/ChartVisualizations.php?functionNum=2&yearDescription="+academicYear,function(){
  });
}

function showDiversityStudentDataByYear(academicYear){
  $("#student-diversity-chart").load("../Resources/Includes/ChartVisualizations.php?functionNum=3&yearDescription="+academicYear,function(){
  });
}

function showDiversityFacultyDataByYear(academicYear){
  $("#faculty-diversity-chart").load("../Resources/Includes/ChartVisualizations.php?functionNum=4&yearDescription="+academicYear,function(){
  });
}
