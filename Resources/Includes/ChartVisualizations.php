<?php

  /*TODO restrict by user's college*/

  error_reporting(1);
  @ini_set('display_errors', 1);
  session_start();
  ob_start();

  if(isset($_POST['functionNum']))
    $function = $_POST['functionNum'];

  if (isset($_GET['functionNum']))
    $function = $_GET['functionNum'];

  if (!empty($function)){

    $ChartVisualizations = new ChartVisualizations;

  }

  switch ($function) {
    case 1:
      $yearDescription = $_GET["yearDescription"];
      $ChartVisualizations->chartEnrollements($yearDescription);
      break;
    case 2:
      $yearDescription = $_GET["yearDescription"];
      $ChartVisualizations->chartFaculty($yearDescription);
      break;
    case 3:
      $yearDescription = $_GET["yearDescription"];
      $ChartVisualizations->chartDiversityStudent($yearDescription);
      break;
    case 4:
      $yearDescription = $_GET["yearDescription"];
      $ChartVisualizations->chartDiversityPersonnel($yearDescription);
      break;
    case 5:
      $ChartVisualizations->exportToPng($_POST["imagebase"],$_POST["name"]);
      break;
    default:
      break;
  }

  Class ChartVisualizations{

    private $conection;
    public $college, $year, $ouid;

    function __construct()
    {

      session_start();

      $this->connection = $this->connection();
      $this->ouid = $_SESSION['login_ouid'];
      $this->year = $_SESSION['bpayname'];

      if ($this->ouid == 4) {

        $this->college = $_SESSION['bpouabbrev'];

      }else{

        $this->college = $_SESSION['login_ouabbrev'];

      }

    }

    private function connection()
    {

      try{

        $connection = new PDO(sprintf('mysql:host=%s;dbname=%s', "localhost", "TESTDB"), "root", "");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;

      }catch(PDOException $e){

        return $e->getMessage();
        exit();

      }

    }

    public function chartEnrollements()
    {

      $getAcademicEnrollements = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getAcademicEnrollements->bindParam(1,$this->year,PDO::PARAM_STR);
      $getAcademicEnrollements->bindParam(2,$this->college,PDO::PARAM_STR);
      $getAcademicEnrollements->execute();
      $rowsGetAcademicEncrollements = $getAcademicEnrollements->rowCount();

      if ($rowsGetAcademicEncrollements > 0){

        while ($data = $getAcademicEnrollements->fetch()){

            $freshman = $data["ENROLL_HC_FRESH"];
            $sophmore = $data["ENROLL_HC_SOPH"];
            $junior = $data["ENROLL_HC_JUNR"];
            $seniors = $data["ENROLL_HC_SENR"];
            $masters = $data["ENROLL_HC_MASTERS"];
            $doctorial = $data["ENROLL_HC_DOCTORAL"];
            $medicine = $data["ENROLL_HC_MEDICINE"];
            $law = $data["ENROLL_HC_LAW"];
            $pharm = $data["ENROLL_HC_PHARMD"];
            $cert = $data["ENROLL_HC_GRAD_CERT"];

            echo "
              <h2 class='text-center'>".$this->college." Data</h2>
              <div class='container-fluid'>
                <div class='row'>
                  <div class='col-md-6'>
                    <div class='table-responsive'>
                      <table class='table table-condensed'>
                        <thead>
                          <tr>
                            <th>Data Type</th>
                            <th># of Students</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Freshman</td>
                            <td>$freshman</td>
                          </tr>
                          <tr>
                            <td>Sophmore</td>
                            <td>$sophmore</td>
                          </tr>
                          <tr>
                            <td>Junior</td>
                            <td>$junior</td>
                          </tr>
                          <tr>
                            <td>Senior</td>
                            <td>$seniors</td>
                          </tr>
                          <tr>
                            <td>Masters</td>
                            <td>$masters</td>
                          </tr>
                          <tr>
                            <td>Doctorial</td>
                            <td>$doctorial</td>
                          </tr>
                          <tr>
                            <td>Medicine</td>
                            <td>$medicine</td>
                          </tr>
                          <tr>
                            <td>Law</td>
                            <td>$law</td>
                          </tr>
                          <tr>
                            <td>Pharma</td>
                            <td>$pharm</td>
                          </tr>
                          <tr>
                            <td>Certification</td>
                            <td>$cert</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class='col-md-6'>
                    <canvas id='chart1' width='300' height='300'></canvas>
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
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                      ],
                      borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                      ],
                      borderWidth: 1
                    }]
                  },
                  options: {
                    responsive: false,
                    legend: {
                      display: false
                    },
                    animation: {
                      onComplete: function(){
                        $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: myChart.toBase64Image(), name: 'student-enrollements-".$this->year."', functionNum: '5'});
                      }
                    }
                  }
                });
              </script>
            ";

        }

      }else{

        echo "<h5>There is no data.</h5>";

      }

    }

    public function chartEnrollementsAll()
    {

      $getAcademicEnrollements = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ?");
      $getAcademicEnrollements->bindParam(1,$this->year,PDO::PARAM_STR);
      $getAcademicEnrollements->execute();
      $rowsGetAcademicEncrollements = $getAcademicEnrollements->rowCount();

      if ($rowsGetAcademicEncrollements > 0){

        while ($data = $getAcademicEnrollements->fetch()){

            $freshman += $data["ENROLL_HC_FRESH"];
            $sophmore += $data["ENROLL_HC_SOPH"];
            $junior += $data["ENROLL_HC_JUNR"];
            $seniors += $data["ENROLL_HC_SENR"];
            $masters += $data["ENROLL_HC_MASTERS"];
            $doctorial += $data["ENROLL_HC_DOCTORAL"];
            $medicine += $data["ENROLL_HC_MEDICINE"];
            $law += $data["ENROLL_HC_LAW"];
            $pharm += $data["ENROLL_HC_PHARMD"];
            $cert += $data["ENROLL_HC_GRAD_CERT"];

        }

        echo "
          <h2 class='text-center'>USCAAU Data</h2>
          <div class='container-fluid'>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th># of Students</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Freshman</td>
                        <td>$freshman</td>
                      </tr>
                      <tr>
                        <td>Sophmore</td>
                        <td>$sophmore</td>
                      </tr>
                      <tr>
                        <td>Junior</td>
                        <td>$junior</td>
                      </tr>
                      <tr>
                        <td>Senior</td>
                        <td>$seniors</td>
                      </tr>
                      <tr>
                        <td>Masters</td>
                        <td>$masters</td>
                      </tr>
                      <tr>
                        <td>Doctorial</td>
                        <td>$doctorial</td>
                      </tr>
                      <tr>
                        <td>Medicine</td>
                        <td>$medicine</td>
                      </tr>
                      <tr>
                        <td>Law</td>
                        <td>$law</td>
                      </tr>
                      <tr>
                        <td>Pharma</td>
                        <td>$pharm</td>
                      </tr>
                      <tr>
                        <td>Certification</td>
                        <td>$cert</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart15' width='300' height='300'></canvas>
              </div>
            </div>
          </div>
          <script>
            var ctx = document.getElementById('chart15');
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
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: myChart.toBase64Image(), name: 'all-student-enrollements-".$this->year."', functionNum: '5'});
                  }
                }
              }
            });
          </script>
        ";

      }else{

        echo "<h5>There is no data.</h5>";

      }

    }

    public function chartFaculty()
    {

      $getFacultyData = $this->connection->prepare("SELECT * FROM `IR_AC_FacultyPop` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getFacultyData->bindParam(1,$this->year,PDO::PARAM_STR);
      $getFacultyData->bindParam(2,$this->college,PDO::PARAM_STR);
      $getFacultyData->execute();
      $rowsGetFacultyData = $getFacultyData->rowCount();

      if ($rowsGetFacultyData > 0){

        while($data = $getFacultyData->fetch()){

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
          $clinicalProfessor = $data["CIF_FTE_CLNCL_PROF"];
          $clinicalAssociateProfessor = $data["CIF_FTE_CLNCL_ASSOC_PROF"];
          $clinicalAssitantProfessor = $data["CIF_FTE_CLNCL_ASSIST_PROF"];
          $instructorLecturer = $data["CIF_FTE_INSTR_LCTR"];
          $clinicalInstructionFaculty = $data["CIF_FTE_ALL"];
          $adjunctFaculty = $data["OTHRFAC_PT_ADJUNCT"];
          $otherFaculty = $data["OTHRFAC_PT_OTHER"];
          $otherFacultyTotal = $data["OTHRFAC_ALL"];
          $studentFacultyRatio = $data["STUDENT_FACULTY_RATIO"];

      }

        echo "
          <div class='container-fluid'>
            <h2 class='text-center'>".$this->college." Data (Regular Professor Positions)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th># of Regular Professors</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Professor Tenure</td>
                        <td>$professorTenur</td>
                      </tr>
                      <tr>
                        <td>Associate Professor /w Tenure</td>
                        <td>$assistantProfessorTenur</td>
                      </tr>
                      <tr>
                        <td>Professor</td>
                        <td>$professor</td>
                      </tr>
                      <tr>
                        <td>Associate Professor</td>
                        <td>$associateProfessor</td>
                      </tr>
                      <tr>
                        <td>Assistant Professor</td>
                        <td>$assistantProfessor</td>
                      </tr>
                      <tr>
                        <td>Tenure-Track Faculty</td>
                        <td>$tenurTrackFaculty</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart3' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>".$this->college." Data (Research Professor Positions)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th># of Research Professors</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Research Professor</td>
                        <td>$researchProfessor</td>
                      </tr>
                      <tr>
                        <td>Associate Research Professor</td>
                        <td>$associateResearchProfessor</td>
                      </tr>
                      <tr>
                        <td>Assistant Research Professor</td>
                        <td>$assistantResearchProfessor</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart4' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>".$this->college." Data (Clinical Professor Positions)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th># of Clinical Professors</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Clinical Professor</td>
                        <td>$clinicalProfessor</td>
                      </tr>
                      <tr>
                        <td>Associate Clinical Professor</td>
                        <td>$clinicalAssociateProfessor</td>
                      </tr>
                      <tr>
                        <td>Assistant Clinical Professor</td>
                        <td>$clinicalAssitantProfessor</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart5' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>".$this->college." Data (Other Professor Positions)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th># of Other Professors</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Instructor/Lecturer</td>
                        <td>$instructorLecturer</td>
                      </tr>
                      <tr>
                        <td>Clinical Instructor</td>
                        <td>$clinicalInstructionFaculty</td>
                      </tr>
                      <tr>
                        <td>Adjunct Faculty</td>
                        <td>$adjunctFaculty</td>
                      </tr>
                      <tr>
                        <td>Other Faculty</td>
                        <td>$otherFaculty</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart6' width='300' height='300'></canvas>
              </div>
            </div>
          </div>
          <script>
            var ctx = document.getElementById('chart3');
            var chart3 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Professor Tenure', 'Associate Professor /w Tenure', 'Professor', 'Associate Professor', 'Assistant Professor', 'Tenure-Track Faculty',],
                datasets: [{
                  label: 'School Population',
                  data: [$professorTenur, $associateProfessor, $professor, $associatePRofessor, $assistantProfessor, $tenurTrackFaculty],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart3.toBase64Image(), name: 'faculty-regular-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart4');
            var chart4 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Research Professor', 'Associate Research Professor', 'Assistant Research Professor'],
                datasets: [{
                  label: 'School Population',
                  data: [$researchProfessor, $associateResearchProfessor, $assistantResearchProfessor],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart4.toBase64Image(), name: 'faculty-research-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart5');
            var chart5 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Clinical Professor', 'Associate Clinical Professor', 'Assistant Clinical Professor'],
                datasets: [{
                  label: 'Population',
                  data: [$clinicalProfessor, $clinicalAssociateProfessor, $clinicalAssitantProfessor],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart5.toBase64Image(), name: 'faculty-clinical-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart6');
            var chart6 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Instructor/Lecturer', 'Clinical Instructor', 'Adjunct Faculty','Other Faculty'],
                datasets: [{
                  label: 'Population',
                  data: [$instructorLecturer, $clinicalInstructionFaculty, $adjunctFaculty,$otherFaculty],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart6.toBase64Image(), name: 'faculty-other-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });
          </script>
        ";

      }else{

        echo "<h5>There is no data.</h5>";

      }

    }

    public function chartFacultyAll()
    {

      $getFacultyData = $this->connection->prepare("SELECT * FROM `IR_AC_FacultyPop` WHERE OUTCOMES_AY = ?");
      $getFacultyData->bindParam(1,$this->year,PDO::PARAM_STR);
      $getFacultyData->execute();
      $rowsGetFacultyData = $getFacultyData->rowCount();

      if ($rowsGetFacultyData > 0){

        while($data = $getFacultyData->fetch()){

          $professorTenur += $data["TTF_FTE_PROF_TNR"];
          $assistantProfessorTenur += $data["TTF_FTE_ASSOC_PROF_TNR"];
          $professor += $data["TTF_FTE_PROF"];
          $associateProfessor += $data["TTF_FTE_ASSOC_PROF"];
          $assistantProfessor += $data["TTF_FTE_ASSIST_PROF"];
          $tenurTrackFaculty += $data["TTF_FTE_ALL"];
          $researchProfessor += $data["RSRCH_FTE_PROF"];
          $associateResearchProfessor += $data["RSRCH_FTE_ASSOC_PROF"];
          $assistantResearchProfessor += $data["RSRCH_FTE_ASSIST_PROF"];
          $researchFaculty += $data["RSRCH_FTE_ALL"];
          $clinicalProfessor += $data["CIF_FTE_CLNCL_PROF"];
          $clinicalAssociateProfessor += $data["CIF_FTE_CLNCL_ASSOC_PROF"];
          $clinicalAssitantProfessor += $data["CIF_FTE_CLNCL_ASSIST_PROF"];
          $instructorLecturer += $data["CIF_FTE_INSTR_LCTR"];
          $clinicalInstructionFaculty += $data["CIF_FTE_ALL"];
          $adjunctFaculty += $data["OTHRFAC_PT_ADJUNCT"];
          $otherFaculty += $data["OTHRFAC_PT_OTHER"];
          $otherFacultyTotal += $data["OTHRFAC_ALL"];
          $studentFacultyRatio += $data["STUDENT_FACULTY_RATIO"];

        }

        echo "
          <div class='container-fluid'>
            <h2 class='text-center'>USCAAU Data (Regular Professor Positions)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th># of Regular Professors</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Professor Tenure</td>
                        <td>$professorTenur</td>
                      </tr>
                      <tr>
                        <td>Associate Professor /w Tenure</td>
                        <td>$assistantProfessorTenur</td>
                      </tr>
                      <tr>
                        <td>Professor</td>
                        <td>$professor</td>
                      </tr>
                      <tr>
                        <td>Associate Professor</td>
                        <td>$associateProfessor</td>
                      </tr>
                      <tr>
                        <td>Assistant Professor</td>
                        <td>$assistantProfessor</td>
                      </tr>
                      <tr>
                        <td>Tenure-Track Faculty</td>
                        <td>$tenurTrackFaculty</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='text-center'>
                  <canvas id='chart16' width='300' height='300'></canvas>
                </div>
              </div>
            </div>
            <h2 class='text-center'>USCAAU Data (Research Professor Positions)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th># of Research Professors</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Research Professor</td>
                        <td>$researchProfessor</td>
                      </tr>
                      <tr>
                        <td>Associate Research Professor</td>
                        <td>$associateResearchProfessor</td>
                      </tr>
                      <tr>
                        <td>Assistant Research Professor</td>
                        <td>$assistantResearchProfessor</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='text-center'>
                  <canvas id='chart17' width='300' height='300'></canvas>
                </div>
              </div>
            </div>
            <h2 class='text-center'>USCAAU Data (Clinical Professor Positions)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th># of Clinical Professors</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Clinical Professor</td>
                        <td>$clinicalProfessor</td>
                      </tr>
                      <tr>
                        <td>Associate Clinical Professor</td>
                        <td>$clinicalAssociateProfessor</td>
                      </tr>
                      <tr>
                        <td>Assistant Clinical Professor</td>
                        <td>$clinicalAssitantProfessor</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='text-center'>
                  <canvas id='chart18' width='300' height='300'></canvas>
                </div>
              </div>
            </div>
            <h2 class='text-center'>USCAAU Data (Other Professor Positions)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th># of Other Professors</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Instructor/Lecturer</td>
                        <td>$instructorLecturer</td>
                      </tr>
                      <tr>
                        <td>Clinical Instructor</td>
                        <td>$clinicalInstructionFaculty</td>
                      </tr>
                      <tr>
                        <td>Adjunct Faculty</td>
                        <td>$adjunctFaculty</td>
                      </tr>
                      <tr>
                        <td>Other Faculty</td>
                        <td>$otherFaculty</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='text-center'>
                  <canvas id='chart19' width='300' height='300'></canvas>
                </div>
              </div>
            </div>
          </div>
          <script>
            var ctx = document.getElementById('chart16');
            var chart16 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Professor Tenure', 'Associate Professor /w Tenure', 'Professor', 'Associate Professor', 'Assistant Professor', 'Tenure-Track Faculty',],
                datasets: [{
                  label: 'School Population',
                  data: [$professorTenur, $associateProfessor, $professor, $associatePRofessor, $assistantProfessor, $tenurTrackFaculty],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart3.toBase64Image(), name: 'faculty-regular-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart17');
            var chart17 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Research Professor', 'Associate Research Professor', 'Assistant Research Professor'],
                datasets: [{
                  label: 'School Population',
                  data: [$researchProfessor, $associateResearchProfessor, $assistantResearchProfessor],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart4.toBase64Image(), name: 'faculty-research-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart18');
            var chart18 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Clinical Professor', 'Associate Clinical Professor', 'Assistant Clinical Professor'],
                datasets: [{
                  label: 'Population',
                  data: [$clinicalProfessor, $clinicalAssociateProfessor, $clinicalAssitantProfessor],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart5.toBase64Image(), name: 'faculty-clinical-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart19');
            var chart19 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Instructor/Lecturer', 'Clinical Instructor', 'Adjunct Faculty','Other Faculty'],
                datasets: [{
                  label: 'Population',
                  data: [$instructorLecturer, $clinicalInstructionFaculty, $adjunctFaculty,$otherFaculty],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ],
                  borderWidth: 1
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart6.toBase64Image(), name: 'faculty-other-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });
          </script>
        ";

      }else{

        echo "<h5>There is no data.</h5>";

      }

    }

    public function chartDiversityStudent()
    {

      $getDiversityData = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityStudent` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getDiversityData->bindParam(1,$this->year,PDO::PARAM_STR);
      $getDiversityData->bindParam(2,$this->college,PDO::PARAM_STR);
      $getDiversityData->execute();
      $rowsGetDiversityData = $getDiversityData->rowCount();

      if ($rowsGetDiversityData > 0){

        $data = $getDiversityData->fetch();

        //first chart
        $underGradFemale = $data["UGRAD_FEMALE"];
        $underGradMale = $data["UGRAD_MALE"];

        //second chart
        $underGradAlaskaNative = $data["UGRAD_AMERIND_ALASKNAT"];
        $underGradAsian = $data["UGRAD_ASIAN"];
        $underGradBlack = $data["UGRAD_BLACK"];
        $underGradHispanic = $data["UGRAD_HISPANIC"];

        //third chart
        $gradAlaskaNativePacific = $data["GRAD_HI_PAC_ISL"];
        $gradAliens = $data["GRAD_NONRESIDENT_ALIEN"];
        $gradDoubleRace = $data["GRAD_TWO_OR_MORE"];
        $gradUnknown = $data["GRAD_UNKNOWN_RACE_ETHNCTY"];
        $gradWhite = $data["GRAD_WHITE"];

        echo "
          <div class='container-fluid'>
            <h2 class='text-center'>".$this->college." Data (Undergrad Gender)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Undergrad Gender</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Female</td>
                        <td>$underGradFemale</td>
                      </tr>
                      <tr>
                        <td>Male</td>
                        <td>$underGradMale</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart7' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>".$this->college." Data (Undergrad Race)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Undergrad Race</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Alaskian/Native</td>
                        <td>$underGradAlaskaNative</td>
                      </tr>
                      <tr>
                        <td>Asian</td>
                        <td>$underGradAsian</td>
                      </tr>
                      <tr>
                        <td>Black</td>
                        <td>$underGradBlack</td>
                      </tr>
                      <tr>
                        <td>Hispanic</td>
                        <td>$underGradHispanic</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart8' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>".$this->college." Data (Grad Race)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Grad Race</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Graduate Alaskian/Native/Pacific</td>
                        <td>$gradAlaskaNativePacific</td>
                      </tr>
                      <tr>
                        <td>Graduate Alien</td>
                        <td>$gradAliens</td>
                      </tr>
                      <tr>
                        <td>Graduate Two Or More Races</td>
                        <td>$gradDoubleRace</td>
                      </tr>
                      <tr>
                        <td>Graduate Unknown</td>
                        <td>$gradUnknown</td>
                      </tr>
                      <tr>
                        <td>Graduate White</td>
                        <td>$gradWhite</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart9' width='300' height='300'></canvas>
              </div>
            </div>
          </div>
          <script>
            var ctx = document.getElementById('chart7');
            var chart7 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Male', 'Female'],
                datasets: [{
                  label: 'Gender',
                  data: [$underGradFemale,$underGradMale],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart7.toBase64Image(), name: 'student-diversity-gender-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart8');
            var chart8 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Undergrad Alaskian/Native', 'Undergrad Asian', 'Undergrad Black', 'Undergrad Hispanic'],
                datasets: [{
                  label: 'Gender',
                  data: [$underGradAlaskaNative,$underGradAsian,$underGradBlack,$underGradHispanic],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart8.toBase64Image(), name: 'student-diversity-race-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart9');
            var chart9 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Graduate Alaskian/Native/Pacific', 'Graduate Alien', 'Graduate Two Or More Races', 'Graduate Unknown','Graduate White'],
                datasets: [{
                  label: 'Gender',
                  data: [$gradAlaskaNativePacific, $gradAliens, $gradDoubleRace, $gradUnknown, $gradWhite],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart9.toBase64Image(), name: 'student-diversity-race-grad-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });



          </script>
        ";

      }else{

        echo "<h5>There is no data.</h5>";

      }

    }

    public function chartDiversityStudentAll()
    {

      $getDiversityData = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityStudent` WHERE OUTCOMES_AY = ?");
      $getDiversityData->bindParam(1,$this->year,PDO::PARAM_STR);
      $getDiversityData->execute();
      $rowsGetDiversityData = $getDiversityData->rowCount();

      if ($rowsGetDiversityData > 0){

        while($data = $getDiversityData->fetch()){

          //first chart
          $underGradFemale += $data["UGRAD_FEMALE"];
          $underGradMale += $data["UGRAD_MALE"];

          //second chart
          $underGradAlaskaNative += $data["UGRAD_AMERIND_ALASKNAT"];
          $underGradAsian += $data["UGRAD_ASIAN"];
          $underGradBlack += $data["UGRAD_BLACK"];
          $underGradHispanic += $data["UGRAD_HISPANIC"];

          //third chart
          $gradAlaskaNativePacific += $data["GRAD_HI_PAC_ISL"];
          $gradAliens += $data["GRAD_NONRESIDENT_ALIEN"];
          $gradDoubleRace += $data["GRAD_TWO_OR_MORE"];
          $gradUnknown += $data["GRAD_UNKNOWN_RACE_ETHNCTY"];
          $gradWhite += $data["GRAD_WHITE"];

        }

        echo "
          <div class='container-fluid'>
            <h2 class='text-center'>USCAAU Data (Undergrad Gender)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Undergrad Gender</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Female</td>
                        <td>$underGradFemale</td>
                      </tr>
                      <tr>
                        <td>Male</td>
                        <td>$underGradMale</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart25' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>".$this->college." Data (Undergrad Race)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Undergrad Race</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Alaskian/Native</td>
                        <td>$underGradAlaskaNative</td>
                      </tr>
                      <tr>
                        <td>Asian</td>
                        <td>$underGradAsian</td>
                      </tr>
                      <tr>
                        <td>Black</td>
                        <td>$underGradBlack</td>
                      </tr>
                      <tr>
                        <td>Hispanic</td>
                        <td>$underGradHispanic</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart26' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>".$this->college." Data (Grad Race)</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Grad Race</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Graduate Alaskian/Native/Pacific</td>
                        <td>$gradAlaskaNativePacific</td>
                      </tr>
                      <tr>
                        <td>Graduate Alien</td>
                        <td>$gradAliens</td>
                      </tr>
                      <tr>
                        <td>Graduate Two Or More Races</td>
                        <td>$gradDoubleRace</td>
                      </tr>
                      <tr>
                        <td>Graduate Unknown</td>
                        <td>$gradUnknown</td>
                      </tr>
                      <tr>
                        <td>Graduate White</td>
                        <td>$gradWhite</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart27' width='300' height='300'></canvas>
              </div>
            </div>
          </div>
          <script>
            var ctx = document.getElementById('chart25');
            var chart25 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Male', 'Female'],
                datasets: [{
                  label: 'Gender',
                  data: [$underGradFemale,$underGradMale],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart7.toBase64Image(), name: 'student-diversity-gender-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart26');
            var chart26 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Undergrad Alaskian/Native', 'Undergrad Asian', 'Undergrad Black', 'Undergrad Hispanic'],
                datasets: [{
                  label: 'Gender',
                  data: [$underGradAlaskaNative,$underGradAsian,$underGradBlack,$underGradHispanic],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart8.toBase64Image(), name: 'student-diversity-race-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart27');
            var chart27 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Graduate Alaskian/Native/Pacific', 'Graduate Alien', 'Graduate Two Or More Races', 'Graduate Unknown','Graduate White'],
                datasets: [{
                  label: 'Gender',
                  data: [$gradAlaskaNativePacific, $gradAliens, $gradDoubleRace, $gradUnknown, $gradWhite],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart9.toBase64Image(), name: 'student-diversity-race-grad-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

          </script>
        ";

      }else{

        echo "<h5>There is no data.</h5>";

      }

    }

    public function chartDiversityPersonnel()
    {

      $getDiversityData = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityPersonnel` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getDiversityData->bindParam(1,$this->year,PDO::PARAM_STR);
      $getDiversityData->bindParam(2,$this->college,PDO::PARAM_STR);
      $getDiversityData->execute();
      $rowsGetDiversityData = $getDiversityData->rowCount();

      if ($rowsGetDiversityData > 0){

        while($data = $getDiversityData->fetch()){

          //chart 1
          $facultyMale = $data["FAC_MALE"];
          $facultyFemale = $data["FAC_FEMALE"];

          //chart 2
          $facultyAlaskaNative = $data["FAC_AMERIND_ALASKNAT"];
          $facultyAsian = $data["FAC_ASIAN"];
          $facultyBlack = $data["FAC_BLACK"];
          $facultyHispanic = $data["FAC_HISPANIC"];
          $facultyHawaiiPacific = $data["FAC_HI_PAC_ISL"];
          $facultyAlien = $data["FAC_NONRESIDENT_ALIEN"];
          $facultyDoubleRace = $data["FAC_TWO_OR_MORE"];
          $facultyUnknown = $data["FAC_UNKNOWN_RACE_ETHNCTY"];
          $facultyWhite = $data["FAC_WHITE"];

          //chart 3
          $staffFemale = $data["STAFF_FEMALE"];
          $staffMale = $data["STAFF_MALE"];

          //chart4
          $staffAlaskaNative = $data["STAFF_AMERIND_ALASKNAT"];
          $staffAsian = $data["STAFF_ASIAN"];
          $staffBlack = $data["STAFF_BLACK"];
          $staffHispanic = $data["STAFF_HISPANIC"];
          $staffHawaiiPacific = $data["STAFF_HI_PAC_ISL"];
          $staffAlien = $data["STAFF_NONRESIDENT_ALIEN"];
          $staffDoubleRace = $data["STAFF_TWO_OR_MORE"];
          $staffUnknown = $data["STAFF_UNKNOWN_RACE_ETHNCTY"];
          $staffWhite = $data["STAFF_WHITE"];

        }

        echo "
          <div class='container-fluid'>
            <h2 class='text-center'>".$this->college." Faculty Gender Data</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Faculty Gender</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Female</td>
                        <td>$facultyFemale</td>
                      </tr>
                      <tr>
                        <td>Male</td>
                        <td>$facultyMale</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart10' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>".$this->college." Faculty Race Data</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Faculty Race</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Faculty Alaskian/Native</td>
                        <td>$facultyAlaskaNative</td>
                      </tr>
                      <tr>
                        <td>Faculty Alien</td>
                        <td>$facultyAlien</td>
                      </tr>
                      <tr>
                        <td>Faculty Asian</td>
                        <td>$facultyAsian</td>
                      </tr>
                      <tr>
                        <td>Faculty Black</td>
                        <td>$facultyBlack</td>
                      </tr>
                      <tr>
                        <td>Faculty Hispanic</td>
                        <td>$facultyHispanic</td>
                      </tr>
                      <tr>
                        <td>Faculty/Hawaii/Pacific</td>
                        <td>$facultyHawaiiPacific</td>
                      </tr>
                      <tr>
                        <td>Faculty Two Or More Races</td>
                        <td>$facultyDoubleRace</td>
                      </tr>
                      <tr>
                        <td>Faculty Unknown</td>
                        <td>$facultyUnknown</td>
                      </tr>
                      <tr>
                        <td>Faculty White</td>
                        <td>$facultyWhite</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart11' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>".$this->college." Staff Gender</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Faculty Gender</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Female</td>
                        <td>$staffFemale</td>
                      </tr>
                      <tr>
                        <td>Male</td>
                        <td>$staffMale</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart12' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>".$this->college." Staff Race</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Faculty Race</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Staff Alaskian/Native</td>
                        <td>$staffAlaskaNative</td>
                      </tr>
                      <tr>
                        <td>Staff Alien</td>
                        <td>$staffAlien</td>
                      </tr>
                      <tr>
                        <td>Staff Asian</td>
                        <td>$staffAsian</td>
                      </tr>
                      <tr>
                        <td>Staff Black</td>
                        <td>$facultyBlack</td>
                      </tr>
                      <tr>
                        <td>Staff Hispanic</td>
                        <td>$staffHispanic</td>
                      </tr>
                      <tr>
                        <td>Staff Hawaii/Pacific</td>
                        <td>$staffHawaiiPacific</td>
                      </tr>
                      <tr>
                        <td>Staff Two Or More Races</td>
                        <td>$staffDoubleRace</td>
                      </tr>
                      <tr>
                        <td>Staff Unknown</td>
                        <td>$staffUnknown</td>
                      </tr>
                      <tr>
                        <td>Staff White</td>
                        <td>$staffWhite</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart13' width='300' height='300'></canvas>
              </div>
            </div>
          </div>
          <script>
            var ctx = document.getElementById('chart10');
            var chart10 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Male', 'Female'],
                datasets: [{
                  label: 'Gender',
                  data: [$facultyMale, $facultyFemale],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart10.toBase64Image(), name: 'faculty-diversity-gender-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart11');
            var chart11 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Faculty Alaskian/Native', 'Faculty Alien', 'Faculty Asian', 'Faculty Black', 'Faculty Hispanic', 'Faculty/Hawaii/Pacific', 'Faculty Two Or More Races', 'Faculty Unknown','Faculty White'],
                datasets: [{
                  label: 'Race',
                  data: [$facultyAlaskaNative, $facultyAlien, $facultyAsian, $facultyBlack, $facultyHispanic, $facultyHawaiiPacific, $facultyDoubleRace, $facultyUnknown, $facultyWhite],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart11.toBase64Image(), name: 'faculty-diversity-race-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart12');
            var chart12 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Male', 'Female'],
                datasets: [{
                  label: 'Gender',
                  data: [$staffMale, $staffFemale],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart12.toBase64Image(), name: 'staff-diversity-gender-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart13');
            var chart13 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Staff Alaskian/Native', 'Staff Alien', 'Staff Asian', 'Staff Black', 'Staff Hispanic', 'Staff/Hawaii/Pacific', 'Staff Two Or More Races', 'Staff Unknown','Staff White'],
                datasets: [{
                  label: 'Race',
                  data: [$staffAlaskaNative, $staffAlien, $staffAsian, $staffBlack, $staffHispanic, $staffHawaiiPacific, $staffDoubleRace, $staffUnknown, $staffWhite],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart13.toBase64Image(), name: 'staff-diversity-race-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

          </script>
        ";

      }else{

        echo "<h5>There is no data.</h5>";

      }

    }

    public function chartDiversityPersonnelAll()
    {

      $getDiversityData = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityPersonnel` WHERE OUTCOMES_AY = ?");
      $getDiversityData->bindParam(1,$this->year,PDO::PARAM_STR);
      $getDiversityData->execute();
      $rowsGetDiversityData = $getDiversityData->rowCount();

      if ($rowsGetDiversityData > 0){

        while($data = $getDiversityData->fetch()){

          //chart 1
          $facultyMale += $data["FAC_MALE"];
          $facultyFemale += $data["FAC_FEMALE"];

          //chart 2
          $facultyAlaskaNative += $data["FAC_AMERIND_ALASKNAT"];
          $facultyAsian += $data["FAC_ASIAN"];
          $facultyBlack += $data["FAC_BLACK"];
          $facultyHispanic += $data["FAC_HISPANIC"];
          $facultyHawaiiPacific += $data["FAC_HI_PAC_ISL"];
          $facultyAlien += $data["FAC_NONRESIDENT_ALIEN"];
          $facultyDoubleRace += $data["FAC_TWO_OR_MORE"];
          $facultyUnknown += $data["FAC_UNKNOWN_RACE_ETHNCTY"];
          $facultyWhite += $data["FAC_WHITE"];

          //chart 3
          $staffFemale += $data["STAFF_FEMALE"];
          $staffMale += $data["STAFF_MALE"];

          //chart4
          $staffAlaskaNative += $data["STAFF_AMERIND_ALASKNAT"];
          $staffAsian += $data["STAFF_ASIAN"];
          $staffBlack += $data["STAFF_BLACK"];
          $staffHispanic += $data["STAFF_HISPANIC"];
          $staffHawaiiPacific += $data["STAFF_HI_PAC_ISL"];
          $staffAlien += $data["STAFF_NONRESIDENT_ALIEN"];
          $staffDoubleRace += $data["STAFF_TWO_OR_MORE"];
          $staffUnknown += $data["STAFF_UNKNOWN_RACE_ETHNCTY"];
          $staffWhite += $data["STAFF_WHITE"];

        }

        echo "
          <div class='container-fluid'>
            <h2 class='text-center'>USCAAU Faculty Gender Data</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Faculty Gender</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Female</td>
                        <td>$facultyFemale</td>
                      </tr>
                      <tr>
                        <td>Male</td>
                        <td>$facultyMale</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart31' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>USCAAU Faculty Race Data</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Faculty Race</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Faculty Alaskian/Native</td>
                        <td>$facultyAlaskaNative</td>
                      </tr>
                      <tr>
                        <td>Faculty Alien</td>
                        <td>$facultyAlien</td>
                      </tr>
                      <tr>
                        <td>Faculty Asian</td>
                        <td>$facultyAsian</td>
                      </tr>
                      <tr>
                        <td>Faculty Black</td>
                        <td>$facultyBlack</td>
                      </tr>
                      <tr>
                        <td>Faculty Hispanic</td>
                        <td>$facultyHispanic</td>
                      </tr>
                      <tr>
                        <td>Faculty/Hawaii/Pacific</td>
                        <td>$facultyHawaiiPacific</td>
                      </tr>
                      <tr>
                        <td>Faculty Two Or More Races</td>
                        <td>$facultyDoubleRace</td>
                      </tr>
                      <tr>
                        <td>Faculty Unknown</td>
                        <td>$facultyUnknown</td>
                      </tr>
                      <tr>
                        <td>Faculty White</td>
                        <td>$facultyWhite</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart32' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>USCAAU Staff Gender</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Faculty Gender</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Female</td>
                        <td>$staffFemale</td>
                      </tr>
                      <tr>
                        <td>Male</td>
                        <td>$staffMale</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart33' width='300' height='300'></canvas>
              </div>
            </div>
            <h2 class='text-center'>USCAAU Staff Race</h2>
            <div class='row'>
              <div class='col-md-6'>
                <div class='table-responsive'>
                  <table class='table table-condensed'>
                    <thead>
                      <tr>
                        <th>Data Type</th>
                        <th>Faculty Race</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Staff Alaskian/Native</td>
                        <td>$staffAlaskaNative</td>
                      </tr>
                      <tr>
                        <td>Staff Alien</td>
                        <td>$staffAlien</td>
                      </tr>
                      <tr>
                        <td>Staff Asian</td>
                        <td>$staffAsian</td>
                      </tr>
                      <tr>
                        <td>Staff Black</td>
                        <td>$facultyBlack</td>
                      </tr>
                      <tr>
                        <td>Staff Hispanic</td>
                        <td>$staffHispanic</td>
                      </tr>
                      <tr>
                        <td>Staff Hawaii/Pacific</td>
                        <td>$staffHawaiiPacific</td>
                      </tr>
                      <tr>
                        <td>Staff Two Or More Races</td>
                        <td>$staffDoubleRace</td>
                      </tr>
                      <tr>
                        <td>Staff Unknown</td>
                        <td>$staffUnknown</td>
                      </tr>
                      <tr>
                        <td>Staff White</td>
                        <td>$staffWhite</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='col-md-6'>
                <canvas id='chart34' width='300' height='300'></canvas>
              </div>
            </div>
          </div>
          <script>
            var ctx = document.getElementById('chart31');
            var chart31 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Male', 'Female'],
                datasets: [{
                  label: 'Gender',
                  data: [$facultyMale, $facultyFemale],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart10.toBase64Image(), name: 'faculty-diversity-gender-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart32');
            var chart32 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Faculty Alaskian/Native', 'Faculty Alien', 'Faculty Asian', 'Faculty Black', 'Faculty Hispanic', 'Faculty/Hawaii/Pacific', 'Faculty Two Or More Races', 'Faculty Unknown','Faculty White'],
                datasets: [{
                  label: 'Race',
                  data: [$facultyAlaskaNative, $facultyAlien, $facultyAsian, $facultyBlack, $facultyHispanic, $facultyHawaiiPacific, $facultyDoubleRace, $facultyUnknown, $facultyWhite],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart11.toBase64Image(), name: 'faculty-diversity-race-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart33');
            var chart33 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Male', 'Female'],
                datasets: [{
                  label: 'Gender',
                  data: [$staffMale, $staffFemale],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart12.toBase64Image(), name: 'staff-diversity-gender-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

            var ctx = document.getElementById('chart34');
            var chart34 = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Staff Alaskian/Native', 'Staff Alien', 'Staff Asian', 'Staff Black', 'Staff Hispanic', 'Staff/Hawaii/Pacific', 'Staff Two Or More Races', 'Staff Unknown','Staff White'],
                datasets: [{
                  label: 'Race',
                  data: [$staffAlaskaNative, $staffAlien, $staffAsian, $staffBlack, $staffHispanic, $staffHawaiiPacific, $staffDoubleRace, $staffUnknown, $staffWhite],
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                  ]
                }]
              },
              options: {
                responsive: false,
                legend: {
                  display: false
                },
                animation: {
                  onComplete: function(){
                    $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart13.toBase64Image(), name: 'staff-diversity-race-".$this->college."', functionNum: '5'});
                  }
                }
              }
            });

          </script>
        ";

      }else{

        echo "<h5>There is no data.</h5>";

      }

    }

    public function exportToPng($base64Image,$pngName)
    {

      $data = explode(",", $base64Image);
      $fileHandler = fopen("../../User/charts/".$pngName.".png","wb");
      fwrite($fileHandler, base64_decode($data[1]));
      fclose($fileHandler);

    }

  }
?>
