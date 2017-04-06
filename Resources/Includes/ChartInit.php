<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="../Library/js/chart.min.js"></script>
<?php

  require("ChartVisualizations.php");
  $ChartVisualizations = new ChartVisualizations();

  $ChartVisualizations->chartEnrollementStudent();
  $ChartVisualizations->chartDiversityStudent();
  $ChartVisualizations->chartDiversityFaculty();

  //header("Location: FPDFExtended.php");

?>
<script>
  $("body").hide();
  $("html").html("<h4>The PDF is loading, please wait...");

  window.setTimeout(test,10000);

  function test(){

    window.location.href = "FPDFExtended.php"

  }

</script>
