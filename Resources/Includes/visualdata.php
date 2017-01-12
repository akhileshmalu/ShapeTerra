<?php

  error_reporting(1);
  @ini_set('display_errors', 1);
  session_start();
  ob_start();

  if(isset($_POST['functionNum']))
    $function = $_POST['functionNum'];

  if (isset($_GET['functionNum']))
    $function = $_GET['functionNum'];

  if (!empty($function)){

    $VisualData = new VisualData;

  }

  switch ($function) {
    case 1:
      $yearDescription = $_GET["yearDescription"];
      $VisualData->chartAcademicEnrollements($yearDescription);
      break;
    default:
      break;
  }

  Class VisualData{

    private $conection;

    function __construct(){
      $this->connection = $this->connection();
    }

    private function connection(){

      try{

        $connection = new PDO(sprintf('mysql:host=%s;dbname=%s', "localhost", "TESTDB"), "root", "");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;

      }catch(PDOException $e){

        return $e->getMessage();
        exit();

      }

    }

    public function getAcademicYears(){

      $getAcademicYears = $this->connection->prepare("SELECT * FROM `AcademicYears`");
      $getAcademicYears->execute();
      $rowsGetAcademicYears = $getAcademicYears->rowCount();

      if ($rowsGetAcademicYears > 0){

        echo "
          <div class='dropdown'>
            <button class='btn btn-default dropdown-toggle' type='button' id='academic-year' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
              Select By Year
              <span class='caret'></span>
            </button>
            <ul class='dropdown-menu' aria-labelledby='academic-year'>
        ";

        while ($data = $getAcademicYears->fetch()){

          $yearDescription = $data["ACAD_YEAR_DESC"];
          echo "<li><div onclick=".'"switchAcademicYearView('."'$yearDescription'".')"'.">$yearDescription</div></li>";

        }

        echo "
          </ul>
        </div>
        ";

      }else{

        echo "No Academic Years";

      }

    }

    public function chartEnrollements($yearDescription){

      $getAcademicEnrollements = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ?");
      $getAcademicEnrollements->bindParam(1,$yearDescription,PDO::PARAM_STR);
      $getAcademicEnrollements->execute();
      $rowsGetAcademicEncrollements = $getAcademicEnrollements->rowCount();

      if ($rowsGetAcademicEncrollements > 0){

        while ($data = $getAcademicEnrollements->fetch()){

            $freshman = $data["ENROLL_HC_FRESH"];
            $sophmore = $data["ENROLL_HC_SOPH"];
            $jumior = $data["ENROLL_HC_JUNR"];
            $seniors = $data["ENROLL_HC_SENR"];
            $masters = $data["ENROLL_HC_MASTERS"];
            $doctorial = $data["ENROLL_HC_DOCTORAL"];
            $medicine = $data["ENROLL_HC_MEDICINE"];
            $law = $data["ENROLL_HC_LAW"];
            $pharm = $data["ENROLL_HC_PHARMD"];
            $cert = $data["ENROLL_HC_GRAD_CERT"];

            echo "
              <h2 class='text-center'>Year Selected: $yearDescription</h2>
              <div class='container-fluid'>
                <div class='row'>
                  <div class='col-md-6'>
                    <canvas id='chart1' width='300' height='300'></canvas>
                  </div>
                  <div class='col-md-6'>
                    <canvas id='chart2' width='300' height='300'></canvas>
                  </div>
                </div>
              </div>
              <script>
                var ctx = document.getElementById('chart1');
                var myChart = new Chart(ctx, {
                  type: 'doughnut',
                  data: {
                    labels: ['Freshman', 'Sophmore', 'Junior', 'Senior', 'Masters', 'Doctors','Medicine','Law','Pharama','Certification'],
                    datasets: [{
                      label: 'School Population',
                      data: [$freshman, $sophmore, $junior, $seniors, $masters, $doctorial, $medicine, $law, $phram, $cert],
                      backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                      ],
                      borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                      ],
                      borderWidth: 1
                    }]
                  },
                  options: {
                    responsive: false
                  }
                });
              </script>
              <script>
                var ctx = document.getElementById('chart2');
                var myChart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                    labels: ['Freshman', 'Sophmore', 'Junior', 'Senior', 'Masters', 'Doctors','Medicine','Law','Pharama','Certification'],
                    datasets: [{
                      label: 'School Population',
                      data: [$freshman, $sophmore, $junior, $seniors, $masters, $doctorial, $medicine, $law, $phram, $cert],
                      backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                      ],
                      borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                      ],
                      borderWidth: 1
                    }]
                  },
                  options: {
                    responsive: false
                  }
                });
              </script>
            ";

        }

      }else{

        echo "There are no enrollements in the database";

      }

    }

    public function chartFaculity(){

      $getFaculityData = $this->connection->prepare("SELECT * FROM `IR_AC_FacultyPop`");
      $getFaculityData->execute();
      $rowsGetFaculityData = $getFaculityData->rowCount();

      if ($rowsGetFaculityData > 0){

        while($data = $getFaculityData->fetch()){

          $professorTenur = $data["TTF_FTE_PROF_TNR"];
          $assistantProfessorTenur = $data["TTF_FTE_ASSOC_PROF_TNR"];
          $professor = $data["TTF_FTE_PROF"];
          $associateProfessor = $data["TTF_FTE_ASSOC_PROF"];
          $assistantProfessor = $data["TTF_FTE_ASSIST_PROF"];
          $tenurTrackFaculty = $data["TTF_FTE_ALL"];
          $researchProfessor = $data["RSRCH_FTE_PROF"];
          $associateResearchProfessor = $data["RSRCH_FTE_ASSOC_PROF"];
          $assistantResearchProfessor = $data["RSRCH_FTE_ASSIST_PROF"];
          $researchFaculty = $data["RSRCH_FTE_ALL"];
          

          echo "
            <h2 class='text-center'>Year Selected: $yearDescription</h2>
            <div class='container-fluid'>
              <div class='row'>
                <div class='col-md-6'>
                  <canvas id='chart3' width='300' height='300'></canvas>
                </div>
                <div class='col-md-6'>
                  <canvas id='chart4' width='300' height='300'></canvas>
                </div>
              </div>
            </div>
            <script>
              var ctx = document.getElementById('chart1');
              var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                  labels: ['Freshman', 'Sophmore', 'Junior', 'Senior', 'Masters', 'Doctors','Medicine','Law','Pharama','Certification'],
                  datasets: [{
                    label: 'School Population',
                    data: [$freshman, $sophmore, $junior, $seniors, $masters, $doctorial, $medicine, $law, $phram, $cert],
                    backgroundColor: [
                      'rgba(255, 99, 132, 0.2)',
                      'rgba(54, 162, 235, 0.2)',
                      'rgba(255, 206, 86, 0.2)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                      'rgba(255,99,132,1)',
                      'rgba(54, 162, 235, 1)',
                      'rgba(255, 206, 86, 1)',
                      'rgba(75, 192, 192, 1)',
                      'rgba(153, 102, 255, 1)',
                      'rgba(255, 159, 64, 1)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)'
                    ],
                    borderWidth: 1
                  }]
                },
                options: {
                  responsive: false
                }
              });
            </script>
            <script>
              var ctx = document.getElementById('chart2');
              var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                  labels: ['Freshman', 'Sophmore', 'Junior', 'Senior', 'Masters', 'Doctors','Medicine','Law','Pharama','Certification'],
                  datasets: [{
                    label: 'School Population',
                    data: [$freshman, $sophmore, $junior, $seniors, $masters, $doctorial, $medicine, $law, $phram, $cert],
                    backgroundColor: [
                      'rgba(255, 99, 132, 0.2)',
                      'rgba(54, 162, 235, 0.2)',
                      'rgba(255, 206, 86, 0.2)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                      'rgba(255,99,132,1)',
                      'rgba(54, 162, 235, 1)',
                      'rgba(255, 206, 86, 1)',
                      'rgba(75, 192, 192, 1)',
                      'rgba(153, 102, 255, 1)',
                      'rgba(255, 159, 64, 1)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)'
                    ],
                    borderWidth: 1
                  }]
                },
                options: {
                  responsive: false
                }
              });
            </script>
          ";

        }

      }else{

        echo "There is no faculity data";

      }

    }

  }
?>
