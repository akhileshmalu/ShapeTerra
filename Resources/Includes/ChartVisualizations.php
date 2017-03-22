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
    case 5:
      $ChartVisualizations->exportToPng($_POST["imagebase"],$_POST["name"]);
      break;
    default:
      break;
  }

  Class ChartVisualizations{

    private $conection;
    public $college, $year, $ouid, $colorArray;

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

    public function chartEnrollementStudent()
    {

      $currentYear = "AY2016-2017";

      $getAcademicEnrollements20162017 = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getAcademicEnrollements20162017->bindParam(1,$currentYear,PDO::PARAM_STR);
      $getAcademicEnrollements20162017->bindParam(2,$this->college,PDO::PARAM_STR);
      $getAcademicEnrollements20162017->execute();
      $rowsAcademicEnrollements20162017 = $getAcademicEnrollements20162017->rowCount();

      if ($rowsAcademicEnrollements20162017 > 0){

        while ($data = $getAcademicEnrollements20162017->fetch()){

          $freshman20162017 = $data["ENROLL_HC_FRESH"];
          $sophmore20162017 = $data["ENROLL_HC_SOPH"];
          $junior20162017 = $data["ENROLL_HC_JUNR"];
          $seniors20162017 = $data["ENROLL_HC_SENR"];
          $masters20162017 = $data["ENROLL_HC_MASTERS"];
          $doctorial20162017 = $data["ENROLL_HC_DOCTORAL"];
          $medicine20162017 = $data["ENROLL_HC_MEDICINE"];
          $law20162017 = $data["ENROLL_HC_LAW"];
          $pharm20162017 = $data["ENROLL_HC_PHARMD"];
          $cert20162017 = $data["ENROLL_HC_GRAD_CERT"];

        }

      }

      $ayYearBackOne = "AY2015-2016";

      $getAcademicEnrollements20152016 = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getAcademicEnrollements20152016->bindParam(1,$ayYearBackOne,PDO::PARAM_STR);
      $getAcademicEnrollements20152016->bindParam(2,$this->college,PDO::PARAM_STR);
      $getAcademicEnrollements20152016->execute();
      $rowsAcademicEnrollements20152016 = $getAcademicEnrollements20152016->rowCount();

      if ($rowsAcademicEnrollements20152016 > 0){

        while ($data = $getAcademicEnrollements20152016->fetch()){

          $freshman20152016 = $data["ENROLL_HC_FRESH"];
          $sophmore20152016 = $data["ENROLL_HC_SOPH"];
          $junior20152016 = $data["ENROLL_HC_JUNR"];
          $seniors20152016 = $data["ENROLL_HC_SENR"];
          $masters20152016 = $data["ENROLL_HC_MASTERS"];
          $doctorial20152016 = $data["ENROLL_HC_DOCTORAL"];
          $medicine20152016 = $data["ENROLL_HC_MEDICINE"];
          $law20152016 = $data["ENROLL_HC_LAW"];
          $pharm20152016 = $data["ENROLL_HC_PHARMD"];
          $cert20152016 = $data["ENROLL_HC_GRAD_CERT"];

        }

      }

      $ayYearBackTwo = "AY2014-2015";

      $getAcademicEnrollements20142015 = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getAcademicEnrollements20142015->bindParam(1,$ayYearBackTwo,PDO::PARAM_STR);
      $getAcademicEnrollements20142015->bindParam(2,$this->college,PDO::PARAM_STR);
      $getAcademicEnrollements20142015->execute();
      $rowsAcademicEnrollements20142015 = $getAcademicEnrollements20142015->rowCount();

      if ($rowsAcademicEnrollements20142015 > 0){

        while ($data = $getAcademicEnrollements20142015->fetch()){

          $freshman20142015 = $data["ENROLL_HC_FRESH"];
          $sophmore20142015 = $data["ENROLL_HC_SOPH"];
          $junior20142015 = $data["ENROLL_HC_JUNR"];
          $seniors20142015 = $data["ENROLL_HC_SENR"];
          $masters20142015 = $data["ENROLL_HC_MASTERS"];
          $doctorial20142015 = $data["ENROLL_HC_DOCTORAL"];
          $medicine20142015 = $data["ENROLL_HC_MEDICINE"];
          $law20142015 = $data["ENROLL_HC_LAW"];
          $pharm20142015 = $data["ENROLL_HC_PHARMD"];
          $cert20142015 = $data["ENROLL_HC_GRAD_CERT"];

        }

      }

      $ayYearBackTwo = "AY2014-2015";

      $getAcademicEnrollements20142015 = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getAcademicEnrollements20142015->bindParam(1,$ayYearBackTwo,PDO::PARAM_STR);
      $getAcademicEnrollements20142015->bindParam(2,$this->college,PDO::PARAM_STR);
      $getAcademicEnrollements20142015->execute();
      $rowsAcademicEnrollements20142015 = $getAcademicEnrollements20142015->rowCount();

      if ($rowsAcademicEnrollements20142015 > 0){

        while ($data = $getAcademicEnrollements20142015->fetch()){

          $freshman20142015 = $data["ENROLL_HC_FRESH"];
          $sophmore20142015 = $data["ENROLL_HC_SOPH"];
          $junior20142015 = $data["ENROLL_HC_JUNR"];
          $seniors20142015 = $data["ENROLL_HC_SENR"];
          $masters20142015 = $data["ENROLL_HC_MASTERS"];
          $doctorial20142015 = $data["ENROLL_HC_DOCTORAL"];
          $medicine20142015 = $data["ENROLL_HC_MEDICINE"];
          $law20142015 = $data["ENROLL_HC_LAW"];
          $pharm20142015 = $data["ENROLL_HC_PHARMD"];
          $cert20142015 = $data["ENROLL_HC_GRAD_CERT"];

        }

      }

      $total2016Undergraduate = $freshman20162017 + $sophmore20162017 + $junior20162017 + $seniors20162017;
      $total2015Undergraduate = $freshman20152016 + $sophmore20152016 + $junior20152016 + $seniors20152016;
      $total2014Undergraduate = $freshman20142015 + $sophmore20142015 + $junior20142015 + $seniors20142015;

      $total2016Graduate = $masters20162017 + $doctorial20162017 + $medicine20162017 + $law20162017 + $pharm20162017 + $cert20162017;
      $total2015Graduate = $masters20152016 + $doctorial20152016 + $medicine20152016 + $law20152016 + $pharm20152016 + $cert20152016;
      $total2014Graduate = $masters20142015 + $doctorial20142015 + $medicine20142015 + $law20142015 + $pharm20142015 + $cert20142015;

      echo "
        <div class='container-fluid'>
          <div class='row'>
            <h2 class='text-center'>".$this->college." Enrollments Data Undergraduate</h2>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th>Data Year</th>
                      <th>2014</th>
                      <th>2015</th>
                      <th>2016</th>
                    </tr>
                    <tr>
                      <th><b>Undergraduate</b></th>
                      <th><b>$total2014Undergraduate</b></th>
                      <th><b>$total2015Undergraduate</b></th>
                      <th><b>$total2016Undergraduate</b></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Freshman</td>
                      <td>$freshman20142015</td>
                      <td>$freshman20152016</td>
                      <td>$freshman20162017</td>
                    </tr>
                    <tr>
                      <td>Sophmore</td>
                      <td>$sophmore20142015</td>
                      <td>$sophmore20152016</td>
                      <td>$sophmore20162017</td>
                    </tr>
                    <tr>
                      <td>Junior</td>
                      <td>$junior20142015</td>
                      <td>$junior20152016</td>
                      <td>$junior20162017</td>
                    </tr>
                    <tr>
                      <td>Senior</td>
                      <td>$seniors20142015</td>
                      <td>$seniors20152016</td>
                      <td>$seniors20162017</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
              <canvas id='chartEnrollementStudentUnder' height='220'></canvas>
            </div>
          </div>
          <div class='row'>
            <h2 class='text-center'>".$this->college." Enrollments Data Graduate</h2>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th>Data Year</th>
                      <th>2014</th>
                      <th>2015</th>
                      <th>2016</th>
                    </tr>
                    <tr>
                      <th><b>Graduate</b></th>
                      <th><b>$total2014Graduate</b></th>
                      <th><b>$total2015Graduate</b></th>
                      <th><b>$total2016Graduate</b></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Masters</td>
                      <td>$masters20142015</td>
                      <td>$masters20152016</td>
                      <td>$masters20162017</td>
                    </tr>
                    <tr>
                      <td>Doctorial</td>
                      <td>$doctorial20142015</td>
                      <td>$doctorial20152016</td>
                      <td>$doctorial20162017</td>
                    </tr>
                    <tr>
                      <td>Graduate Certificate</td>
                      <td>$cert20142015</td>
                      <td>$cert20152016</td>
                      <td>$cert20162017</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
              <canvas id='chartEnrollementStudentGrad' height='220'></canvas>
            </div>
          </div>
          <div class='row'>
            <h2 class='text-center'>".$this->college." Enrollments Data All</h2>
            <div class='col-md-5'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th>Data Year</th>
                      <th>2014</th>
                      <th>2015</th>
                      <th>2016</th>
                    </tr>
                    <th><b>Undergraduate</b></th>
                    <th><b>$total2014Undergraduate</b></th>
                    <th><b>$total2015Undergraduate</b></th>
                    <th><b>$total2016Undergraduate</b></th>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Freshman</td>
                      <td>$freshman20142015</td>
                      <td>$freshman20152016</td>
                      <td>$freshman20162017</td>
                    </tr>
                    <tr>
                      <td>Sophmore</td>
                      <td>$sophmore20142015</td>
                      <td>$sophmore20152016</td>
                      <td>$sophmore20162017</td>
                    </tr>
                    <tr>
                      <td>Junior</td>
                      <td>$junior20142015</td>
                      <td>$junior20152016</td>
                      <td>$junior20162017</td>
                    </tr>
                    <tr>
                      <td>Senior</td>
                      <td>$seniors20142015</td>
                      <td>$seniors20152016</td>
                      <td>$seniors20162017</td>
                    </tr>
                    <tr>
                      <th><b>Graduate</b></th>
                      <th><b>$total2014Graduate</b></th>
                      <th><b>$total2015Graduate</b></th>
                      <th><b>$total2016Graduate</b></th>
                    </tr>
                    <tr>
                      <td>Masters</td>
                      <td>$masters20142015</td>
                      <td>$masters20152016</td>
                      <td>$masters20162017</td>
                    </tr>
                    <tr>
                      <td>Doctorial</td>
                      <td>$doctorial20142015</td>
                      <td>$doctorial20152016</td>
                      <td>$doctorial20162017</td>
                    </tr>
                    <tr>
                      <td>Graduate Certificate</td>
                      <td>$cert20142015</td>
                      <td>$cert20152016</td>
                      <td>$cert20162017</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-7'>
              <canvas id='chartEnrollementStudentAll' height='220'></canvas>
            </div>
          </div>
        </div>
        <script>
          var ctx = document.getElementById('chartEnrollementStudentAll');
          var chartEnrollementStudentAll = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
              labels: ['2014', '2015', '2016'],
              datasets: [{
                data: [$freshman20142015,$freshman20152016,$freshman20162017],
                backgroundColor: 'rgba(116, 0, 11, 0.5)',
                label: 'Freshman'
              },{
                data: [$sophmore20142015,$sophmore20152016,$sophmore20162017],
                backgroundColor: 'rgba(120, 50, 0, 0.5)',
                label: 'Sophmore'
              },{
                data: [$junior20142015,$junior20152016,$junior20162017],
                backgroundColor: 'rgba(17, 100, 0, 0.5)',
                label: 'Junior'
              },{
                data: [$seniors20142015,$seniors20152016,$seniors20162017],
                backgroundColor: 'rgba(0, 75, 65, 0.5)',
                label: 'Senior'
              },{
                data: [$masters20142015,$masters20152016,$masters20162017],
                backgroundColor: 'rgba(33, 120, 108, 0.5)',
                label: 'Masters'
              },{
                data: [$doctorial20142015,$doctorial20152016,$doctorial20162017],
                backgroundColor: 'rgba(43, 0, 4, 0.5)',
                label: 'Doctorial'
              },{
                data: [$cert20142015,$cert20152016,$cert20162017],
                backgroundColor: 'rgba(6, 37, 0, 0.5)',
                label: 'Graduate Certificate'
              }],
              borderWidth: 1
          },
          options: {
            responsive: true,
            legend: {
              display: true
            },
            animation: {
              onComplete: function(){
                $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: myChart.toBase64Image(), name: 'student-enrollements-all-".$this->college."', functionNum: '5'});
              }
            },
            scaleLabel:{
                display:false
            },
            scales: {
              xAxes: [{
                ticks: {
                    beginAtZero:true,
                    fontSize:11
                },
                scaleLabel:{
                    display:false
                },
                gridLines: {
                },
                stacked: true
              }],
              yAxes: [{
                stacked: true,
                gridLines: {
                    display:false,
                    color: '#fff',
                    zeroLineColor: '#fff',
                    zeroLineWidth: 0
                },
                ticks: {
                  fontSize:11
                },
              }]
            },tooltips: {
              enabled: true
            },
            hover :{
                animationDuration:0
            }
          }
        });

        var ctx = document.getElementById('chartEnrollementStudentUnder');
        var myChart = new Chart(ctx, {
          type: 'horizontalBar',
          data: {
            labels: ['2014', '2015', '2016'],
            datasets: [{
              data: [$freshman20142015,$freshman20152016,$freshman20162017],
              backgroundColor: 'rgba(116, 0, 11, 0.5)',
              label: 'Freshman'
            },{
              data: [$sophmore20142015,$sophmore20152016,$sophmore20162017],
              backgroundColor: 'rgba(120, 50, 0, 0.5)',
              label: 'Sophmore'
            },{
              data: [$junior20142015,$junior20152016,$junior20162017],
              backgroundColor: 'rgba(17, 100, 0, 0.5)',
              label: 'Junior'
            },{
              data: [$seniors20142015,$seniors20152016,$seniors20162017],
              backgroundColor: 'rgba(0, 75, 65, 0.5)',
              label: 'Senior'
            }],
            borderWidth: 1
        },
        options: {
          responsive: true,
          legend: {
            display: true
          },
          animation: {
            onComplete: function(){
              $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: myChart.toBase64Image(), name: 'student-enrollements-under-".$this->college."', functionNum: '5'});
            }
          },
          scaleLabel:{
              display:false
          },
          scales: {
            xAxes: [{
              ticks: {
                  beginAtZero:true,
                  fontSize:11
              },
              scaleLabel:{
                  display:false
              },
              gridLines: {
              },
              stacked: true
            }],
            yAxes: [{
              stacked: true,
              gridLines: {
                  display:false,
                  color: '#fff',
                  zeroLineColor: '#fff',
                  zeroLineWidth: 0
              },
              ticks: {
                fontSize:11
              },
            }]
          },tooltips: {
            enabled: true
          },
          hover :{
              animationDuration:0
          }
        }
      });

      var ctx = document.getElementById('chartEnrollementStudentGrad');
      var myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
          labels: ['2014', '2015', '2016'],
          datasets: [{
            data: [$masters20142015,$masters20152016,$masters20162017],
            backgroundColor: 'rgba(116, 0, 11, 0.5)',
            label: 'Masters'
          },{
            data: [$doctorial20142015,$doctorial20152016,$doctorial20162017],
            backgroundColor: 'rgba(120, 50, 0, 0.5)',
            label: 'Doctorial'
          },{
            data: [$cert20142015,$cert20152016,$cert20162017],
            backgroundColor: 'rgba(17, 100, 0, 0.5)',
            label: 'Graduate Certificate'
          }],
          borderWidth: 1
      },
      options: {
        responsive: true,
        legend: {
          display: true
        },
        animation: {
          onComplete: function(){
            $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: myChart.toBase64Image(), name: 'student-enrollements-under-".$this->college."', functionNum: '5'});
          }
        },
        scaleLabel:{
            display:false
        },
        scales: {
          xAxes: [{
            ticks: {
                beginAtZero:true,
                fontSize:11
            },
            scaleLabel:{
                display:false
            },
            gridLines: {
            },
            stacked: true
          }],
          yAxes: [{
            stacked: true,
            gridLines: {
                display:false,
                color: '#fff',
                zeroLineColor: '#fff',
                zeroLineWidth: 0
            },
            ticks: {
              fontSize:11
            },
          }]
        },tooltips: {
          enabled: true
        },
        hover :{
            animationDuration:0
        }
        }
      });
        </script>
        ";

    }

    public function chartDiversityStudent()
    {

      $currentYear = "AY2016-2017";

      $getDiversityData20162017 = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityStudent` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getDiversityData20162017->bindParam(1,$currentYear,PDO::PARAM_STR);
      $getDiversityData20162017->bindParam(2,$this->college,PDO::PARAM_STR);
      $getDiversityData20162017->execute();
      $rowsGetDiversityData20162017 = $getDiversityData20162017->rowCount();

      if ($rowsGetDiversityData20162017 > 0){

        $data = $getDiversityData20162017->fetch();

        $underGradFemale20162017 = $data["UGRAD_FEMALE"];
        $underGradMale20162017 = $data["UGRAD_MALE"];

        $underGradAlaskaNative20162017 = $data["UGRAD_AMERIND_ALASKNAT"];
        $underGradAsian20162017 = $data["UGRAD_ASIAN"];
        $underGradBlack20162017 = $data["UGRAD_BLACK"];
        $underGradHispanic20162017 = $data["UGRAD_HISPANIC"];
        $underGradHawaiiPacificIsland20162017 = $data["UGRAD_HI_PAC_ISL"];
        $underGradAlien20162017 = $data["UGRAD_NONRESIDENT_ALIEN"];
        $underGradTwoOrMore20162017 = $data["UGRAD_TWO_OR_MORE"];
        $underGradUnkown20162017 = $data["UGRAD_UNKNOWN_RACE_ETHNCTY"];
        $underGradWhite20162017 = $data["UGRAD_WHITE"];

        $gradFemale20162017 = $data["GRAD_FEMALE"];
        $gradMale20162017 = $data["GRAD_MALE"];

        $gradAlaskaNative20162017 = $data["GRAD_AMERIND_ALASKNAT"];
        $gradAsian20162017 = $data["GRAD_ASIAN"];
        $gradBlack20162017 = $data["GRAD_BLACK"];
        $gradHispanic20162017 = $data["GRAD_HISPANIC"];
        $gradHawaiiPacificIsland20162017 = $data["GRAD_HI_PAC_ISL"];
        $gradAliens20162017 = $data["GRAD_NONRESIDENT_ALIEN"];
        $gradDoubleRace20162017 = $data["GRAD_TWO_OR_MORE"];
        $gradUnknown20162017 = $data["GRAD_UNKNOWN_RACE_ETHNCTY"];
        $gradWhite20162017 = $data["GRAD_WHITE"];

      }

      $ayYearBackOne = "AY2015-2016";

      $getDiversityData20152016 = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityStudent` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getDiversityData20152016->bindParam(1,$ayYearBackOne,PDO::PARAM_STR);
      $getDiversityData20152016->bindParam(2,$this->college,PDO::PARAM_STR);
      $getDiversityData20152016->execute();
      $rowsGetDiversityData20152016 = $getDiversityData20152016->rowCount();

      if ($rowsGetDiversityData20152016 > 0){

        $data = $getDiversityData20152016->fetch();

        $underGradFemale20152016 = $data["UGRAD_FEMALE"];
        $underGradMale20152016 = $data["UGRAD_MALE"];

        $underGradAlaskaNative20152016 = $data["UGRAD_AMERIND_ALASKNAT"];
        $underGradAsian20152016 = $data["UGRAD_ASIAN"];
        $underGradBlack20152016 = $data["UGRAD_BLACK"];
        $underGradHispanic20152016 = $data["UGRAD_HISPANIC"];
        $underGradHawaiiPacificIsland20152016 = $data["UGRAD_HI_PAC_ISL"];
        $underGradAlien20152016 = $data["UGRAD_NONRESIDENT_ALIEN"];
        $underGradTwoOrMore20152016 = $data["UGRAD_TWO_OR_MORE"];
        $underGradUnkown20152016 = $data["UGRAD_UNKNOWN_RACE_ETHNCTY"];
        $underGradWhite20152016 = $data["UGRAD_WHITE"];

        $gradFemale20152016 = $data["GRAD_FEMALE"];
        $gradMale20152016 = $data["GRAD_MALE"];

        $gradAlaskaNative20152016 = $data["GRAD_AMERIND_ALASKNAT"];
        $gradAsian20152016 = $data["GRAD_ASIAN"];
        $gradBlack20152016 = $data["GRAD_BLACK"];
        $gradHispanic20152016 = $data["GRAD_HISPANIC"];
        $gradHawaiiPacificIsland20152016 = $data["GRAD_HI_PAC_ISL"];
        $gradAliens20152016 = $data["GRAD_NONRESIDENT_ALIEN"];
        $gradDoubleRace20152016 = $data["GRAD_TWO_OR_MORE"];
        $gradUnknown20152016 = $data["GRAD_UNKNOWN_RACE_ETHNCTY"];
        $gradWhite20152016 = $data["GRAD_WHITE"];

      }

      $ayYearBackTwo = "AY2014-2015";

      $getDiversityData20142015 = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityStudent` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getDiversityData20142015->bindParam(1,$ayYearBackTwo,PDO::PARAM_STR);
      $getDiversityData20142015->bindParam(2,$this->college,PDO::PARAM_STR);
      $getDiversityData20142015->execute();
      $rowsGetDiversityData20142015 = $getDiversityData20142015->rowCount();

      if ($rowsGetDiversityData20142015 > 0){

        $data = $getDiversityData20142015->fetch();

        $underGradFemale20142015 = $data["UGRAD_FEMALE"];
        $underGradMale20142015 = $data["UGRAD_MALE"];

        $underGradAlaskaNative20142015 = $data["UGRAD_AMERIND_ALASKNAT"];
        $underGradAsian20142015 = $data["UGRAD_ASIAN"];
        $underGradBlack20142015 = $data["UGRAD_BLACK"];
        $underGradHispanic20142015 = $data["UGRAD_HISPANIC"];
        $underGradHawaiiPacificIsland20142015 = $data["UGRAD_HI_PAC_ISL"];
        $underGradAlien20142015 = $data["UGRAD_NONRESIDENT_ALIEN"];
        $underGradTwoOrMore20142015 = $data["UGRAD_TWO_OR_MORE"];
        $underGradUnkown20142015 = $data["UGRAD_UNKNOWN_RACE_ETHNCTY"];
        $underGradWhite20142015 = $data["UGRAD_WHITE"];

        $gradFemale20142015 = $data["GRAD_FEMALE"];
        $gradMale20142015 = $data["GRAD_MALE"];

        $gradAlaskaNative20142015 = $data["GRAD_AMERIND_ALASKNAT"];
        $gradAsian20142015 = $data["GRAD_ASIAN"];
        $gradBlack20142015 = $data["GRAD_BLACK"];
        $gradHispanic20142015 = $data["GRAD_HISPANIC"];
        $gradHawaiiPacificIsland20142015 = $data["GRAD_HI_PAC_ISL"];
        $gradAliens20142015 = $data["GRAD_NONRESIDENT_ALIEN"];
        $gradDoubleRace20142015 = $data["GRAD_TWO_OR_MORE"];
        $gradUnknown20142015 = $data["GRAD_UNKNOWN_RACE_ETHNCTY"];
        $gradWhite20142015 = $data["GRAD_WHITE"];

      }

      echo "
        <div class='container-fluid'>
          <h2 class='text-center'>".$this->college." Undergraduate Gender Data</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th>Data Year</th>
                      <th>2014</th>
                      <th>2015</th>
                      <th>2016</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Female</td>
                      <td>$underGradFemale20142015</td>
                      <td>$underGradFemale20152016</td>
                      <td>$underGradFemale20162017</td>
                    </tr>
                    <tr>
                      <td>Male</td>
                      <td>$underGradMale20142015</td>
                      <td>$underGradMale20152016</td>
                      <td>$underGradMale20162017</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
              <table>
                <tr>
                  <td>
                    <h5 class='text-center'>2014 Undergraduate Gender</h5>
                    <canvas id='underDiversityGender2014' width='200' height='200'></canvas>
                  </td>
                  <td>
                    <h5 class='text-center'>2015 Undergraduate Gender</h5>
                    <canvas id='underDiversityGender2015' width='200' height='200'></canvas>
                  </td>
                  <td>
                    <h5 class='text-center'>2016 Undergraduate Gender</h5>
                    <canvas id='underDiversityGender2016' width='200' height='200'></canvas>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <h2 class='text-center'>".$this->college." Data Undergraduate Race</h2>
          <div class='row'>
            <div class='col-md-6'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th>Data Year</th>
                      <th>2014</th>
                      <th>2015</th>
                      <th>2016</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Undergrad American Indian/Alaskian Native</td>
                      <td>$underGradAlaskaNative20142015</td>
                      <td>$underGradAlaskaNative20152016</td>
                      <td>$underGradAlaskaNative20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Asian</td>
                      <td>$underGradAsian20142015</td>
                      <td>$underGradAsian20152016</td>
                      <td>$underGradAsian20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Black</td>
                      <td>$underGradBlack20142015</td>
                      <td>$underGradBlack20152016</td>
                      <td>$underGradBlack20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Hispanic</td>
                      <td>$underGradHispanic20142015</td>
                      <td>$underGradHispanic20152016</td>
                      <td>$underGradHispanic20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Native Hawaiian or Other Pacific Islander</td>
                      <td>$underGradHawaiiPacificIsland20142015</td>
                      <td>$underGradHawaiiPacificIsland20152016</td>
                      <td>$underGradHawaiiPacificIsland20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Alien</td>
                      <td>$underGradAlien20142015</td>
                      <td>$underGradAlien20142015</td>
                      <td>$underGradAlien20142015</td>
                    </tr>
                    <tr>
                      <td>Undergrad Two Or More Races</td>
                      <td>$underGradTwoOrMore20142015</td>
                      <td>$underGradTwoOrMore20152016</td>
                      <td>$underGradTwoOrMore20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Unkown Race</td>
                      <td>$underGradUnkown20142015</td>
                      <td>$underGradUnkown20142015</td>
                      <td>$underGradUnkown20142015</td>
                    </tr>
                    <tr>
                      <td>Undergrad White</td>
                      <td>$underGradWhite20142015</td>
                      <td>$underGradWhite20152016</td>
                      <td>$underGradWhite20162017</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-6'>
              <canvas id='underDiversityRace' height='220'></canvas>
            </div>
          </div>
          <h2 class='text-center'>".$this->college." Grad Gender Data</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th>Data Year</th>
                      <th>2014</th>
                      <th>2015</th>
                      <th>2016</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Female</td>
                      <td>$gradFemale20142015</td>
                      <td>$gradFemale20152016</td>
                      <td>$gradFemale20162017</td>
                    </tr>
                    <tr>
                      <td>Male</td>
                      <td>$gradMale20142015</td>
                      <td>$gradMale20152016</td>
                      <td>$gradMale20162017</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
            <table>
              <tr>
                <td>
                  <h5 class='text-center'>2014 Undegrad Gender</h5>
                  <canvas id='gradGender2014' width='200' height='200'></canvas>
                </td>
                <td>
                  <h5 class='text-center'>2015 Undegrad Gender</h5>
                  <canvas id='gradGender2015' width='200' height='200'></canvas>
                </td>
                <td>
                  <h5 class='text-center'>2016 Undegrad Gender</h5>
                  <canvas id='gradGender2016' width='200' height='200'></canvas>
                </td>
              </tr>
            </table>
            </div>
          </div>
          <h2 class='text-center'>".$this->college." Data Graduate Race</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th>Data Year</th>
                      <th>2014</th>
                      <th>2015</th>
                      <th>2016</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Undergrad American Indian/Alaskian Native</td>
                      <td>$underGradAlaskaNative20142015</td>
                      <td>$underGradAlaskaNative20152016</td>
                      <td>$underGradAlaskaNative20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Asian</td>
                      <td>$underGradAsian20142015</td>
                      <td>$underGradAsian20152016</td>
                      <td>$underGradAsian20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Black</td>
                      <td>$underGradBlack20142015</td>
                      <td>$underGradBlack20152016</td>
                      <td>$underGradBlack20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Hispanic</td>
                      <td>$underGradHispanic20142015</td>
                      <td>$underGradHispanic20152016</td>
                      <td>$underGradHispanic20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Native Hawaiian or Other Pacific Islander</td>
                      <td>$underGradHawaiiPacificIsland20142015</td>
                      <td>$underGradHawaiiPacificIsland20152016</td>
                      <td>$underGradHawaiiPacificIsland20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Alien</td>
                      <td>$underGradAlien20142015</td>
                      <td>$underGradAlien20142015</td>
                      <td>$underGradAlien20142015</td>
                    </tr>
                    <tr>
                      <td>Undergrad Two Or More Races</td>
                      <td>$underGradTwoOrMore20142015</td>
                      <td>$underGradTwoOrMore20152016</td>
                      <td>$underGradTwoOrMore20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Unkown Race</td>
                      <td>$underGradUnkown20142015</td>
                      <td>$underGradUnkown20142015</td>
                      <td>$underGradUnkown20142015</td>
                    </tr>
                    <tr>
                      <td>Undergrad White</td>
                      <td>$underGradWhite20142015</td>
                      <td>$underGradWhite20152016</td>
                      <td>$underGradWhite20162017</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
              <canvas id='gradDiversityRace' height='220'></canvas>
            </div>
          </div>
        </div>
        <script>
          var ctx = document.getElementById('underDiversityGender2014');
          var underDiversityGender2014 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$underGradFemale20142015,$underGradMale20142015],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(120, 50, 0, 0.5)'
                ],
                borderColor: [
                  'rgba(116, 0, 11, 1)',
                  'rgba(120, 50, 0, 1)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: true
              },
              animation: {
                onComplete: function(){
                  $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart7.toBase64Image(), name: 'student-diversity-gender-under-2014-".$this->college."', functionNum: '5'});
                }
              }
            }
          });

          var ctx = document.getElementById('underDiversityGender2015');
          var underDiversityGender2015 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$underGradFemale20152016,$underGradMale20152016],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(120, 50, 0, 0.5)'
                ],
                borderColor: [
                  'rgba(116, 0, 11, 1)',
                  'rgba(120, 50, 0, 1)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: true
              },
              animation: {
                onComplete: function(){
                  $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart7.toBase64Image(), name: 'student-diversity-gender-under-2015-".$this->college."', functionNum: '5'});
                }
              }
            }
          });

          var ctx = document.getElementById('underDiversityGender2016');
          var underDiversityGender2016 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$underGradFemale20162017,$underGradMale20162017],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(120, 50, 0, 0.5)'
                ],
                borderColor: [
                  'rgba(116, 0, 11, 1)',
                  'rgba(120, 50, 0, 1)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: true
              },
              animation: {
                onComplete: function(){
                  $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart7.toBase64Image(), name: 'student-diversity-gender-under-2016-".$this->college."', functionNum: '5'});
                }
              }
            }
          });

          var ctx = document.getElementById('underDiversityRace');
          var underDiversityRace = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
              labels: ['2014', '2015', '2016'],
              datasets: [{
                data: [$underGradAlaskaNative20142015,$underGradAlaskaNative20152016,$underGradAlaskaNative20162017],
                backgroundColor: 'rgba(116, 0, 11, 0.5)',
                label: 'American Indian/Alaska Native'
              },{
                data: [$underGradAsian20142015,$underGradAsian20152016,$underGradAsian20162017],
                backgroundColor: 'rgba(120, 50, 0, 0.5)',
                label: 'Asian'
              },{
                data: [$underGradBlack20142015,$underGradBlack20152016,$underGradBlack20162017],
                backgroundColor: 'rgba(17, 100, 0, 0.5)',
                label: 'Black or African American'
              },{
                data: [$underGradHispanic20142015,$underGradHispanic20152016,$underGradHispanic20162017],
                backgroundColor: 'rgba(33, 120, 108, 0.5)',
                label: 'Hispanic or Latino'
              },{
                data: [$underGradHawaiiPacificIsland20142015,$underGradHawaiiPacificIsland20152016,$underGradHawaiiPacificIsland20162017],
                backgroundColor: 'rgba(43, 0, 4, 0.5)',
                label: 'Native Hawaiian or Other Pacific Islander'
              },{
                data: [$underGradAlien20142015,$underGradAlien20152016,$underGradAlien20162017],
                backgroundColor: 'rgba(6, 37, 0, 0.5)',
                label: 'Nonresident Alien'
              },{
                data: [$underGradTwoOrMore20142015,$underGradTwoOrMore20152016,$underGradTwoOrMore20162017],
                backgroundColor: 'rgba(185, 50, 63, 0.5)',
                label: 'Two Or More Races'
              },{
                data: [$underGradUnkown20142015,$underGradUnkown20152016,$underGradUnkown20162017],
                backgroundColor: 'rgba(63, 160, 43, 0.5)',
                label: 'Unknown Race/Ethnicity'
              },{
                data: [$underGradWhite20142015,$underGradWhite20152016,$underGradWhite20162017],
                backgroundColor: 'rgba(191, 110, 52, 0.5)',
                label: 'White'
              }],
              borderWidth: 1
          },
          options: {
            responsive: true,
            legend: {
              display: true
            },
            animation: {
              onComplete: function(){
                $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: myChart.toBase64Image(), name: 'student-diversity-race-under-".$this->college."', functionNum: '5'});
              }
            },
            scaleLabel:{
                display:false
            },
            scales: {
              xAxes: [{
                ticks: {
                    beginAtZero:true,
                    fontSize:11
                },
                scaleLabel:{
                    display:false
                },
                gridLines: {
                },
                stacked: true
              }],
              yAxes: [{
                stacked: true,
                gridLines: {
                    display:false,
                    color: '#fff',
                    zeroLineColor: '#fff',
                    zeroLineWidth: 0
                },
                ticks: {
                  fontSize:11
                },
              }]
            },tooltips: {
              enabled: true
            },
            hover :{
                animationDuration:0
            }
          }
          });

          var ctx = document.getElementById('gradGender2014');
          var gradGender2014 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$gradFemale20142015,$gradMale20142015],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(120, 50, 0, 0.5)'
                ],
                borderColor: [
                  'rgba(116, 0, 11, 1)',
                  'rgba(120, 50, 0, 1)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: true
              },
              animation: {
                onComplete: function(){
                  $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart7.toBase64Image(), name: 'student-diversity-gender-under-2014-".$this->college."', functionNum: '5'});
                }
              }
            }
          });

          var ctx = document.getElementById('gradGender2015');
          var gradGender2015 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$gradFemale20152016,$gradMale20152016],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(120, 50, 0, 0.5)'
                ],
                borderColor: [
                  'rgba(116, 0, 11, 1)',
                  'rgba(120, 50, 0, 1)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: true
              },
              animation: {
                onComplete: function(){
                  $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart7.toBase64Image(), name: 'student-diversity-gender-under-2015-".$this->college."', functionNum: '5'});
                }
              }
            }
          });

          var ctx = document.getElementById('gradGender2016');
          var gradGender2016 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$gradFemale20162017,$gradMale20162017],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(120, 50, 0, 0.5)'
                ],
                borderColor: [
                  'rgba(116, 0, 11, 1)',
                  'rgba(120, 50, 0, 1)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: true
              },
              animation: {
                onComplete: function(){
                  $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart7.toBase64Image(), name: 'student-diversity-gender-under-2016-".$this->college."', functionNum: '5'});
                }
              }
            }
          });

          var ctx = document.getElementById('gradDiversityRace');
          var gradDiversityRace = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
              labels: ['2014', '2015', '2016'],
              datasets: [{
                data: [$gradAlaskaNative20142015,$gradAlaskaNative20152016,$gradAlaskaNative20162017],
                backgroundColor: 'rgba(116, 0, 11, 0.5)',
                label: 'American Indian/Alaska Native'
              },{
                data: [$gradAsian20142015,$gradAsian20152016,$gradAsian20162017],
                backgroundColor: 'rgba(120, 50, 0, 0.5)',
                label: 'Asian'
              },{
                data: [$gradBlack20142015,$gradBlack20152016,$gradBlack20162017],
                backgroundColor: 'rgba(17, 100, 0, 0.5)',
                label: 'Black or African American'
              },{
                data: [$gradHispanic20142015,$gradHispanic20152016,$gradHispanic20162017],
                backgroundColor: 'rgba(33, 120, 108, 0.5)',
                label: 'Hispanic or Latino'
              },{
                data: [$gradHawaiiPacificIsland20142015,$gradHawaiiPacificIsland20152016,$gradHawaiiPacificIsland20162017],
                backgroundColor: 'rgba(43, 0, 4, 0.5)',
                label: 'Native Hawaiian or Other Pacific Islander'
              },{
                data: [$gradAlien20142015,$gradAlien20152016,$gradAlien20162017],
                backgroundColor: 'rgba(6, 37, 0, 0.5)',
                label: 'Nonresident Alien'
              },{
                data: [$gradTwoOrMore20142015,$gradTwoOrMore20152016,$gradTwoOrMore20162017],
                backgroundColor: 'rgba(185, 50, 63, 0.5)',
                label: 'Two Or More Races'
              },{
                data: [$gradUnkown20142015,$gradUnkown20152016,$gradUnkown20162017],
                backgroundColor: 'rgba(63, 160, 43, 0.5)',
                label: 'Unknown Race/Ethnicity'
              },{
                data: [$gradWhite20142015,$gradWhite20152016,$gradWhite20162017],
                backgroundColor: 'rgba(191, 110, 52, 0.5)',
                label: 'White'
              }],
              borderWidth: 1
          },
          options: {
            responsive: true,
            legend: {
              display: true
            },
            animation: {
              onComplete: function(){
                $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: myChart.toBase64Image(), name: 'student-diversity-race-under-".$this->college."', functionNum: '5'});
              }
            },
            scaleLabel:{
                display:false
            },
            scales: {
              xAxes: [{
                ticks: {
                    beginAtZero:true,
                    fontSize:11
                },
                scaleLabel:{
                    display:false
                },
                gridLines: {
                },
                stacked: true
              }],
              yAxes: [{
                stacked: true,
                gridLines: {
                    display:false,
                    color: '#fff',
                    zeroLineColor: '#fff',
                    zeroLineWidth: 0
                },
                ticks: {
                  fontSize:11
                },
              }]
            },
            hover :{
                animationDuration:0
            }
          }
          });
        </script>
      ";

    }

    public function chartDiversityFaculty()
    {

      $currentYear = "AY2016-2017";

      $getDiversityData20162017 = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityPersonnel` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getDiversityData20162017->bindParam(1,$currentYear,PDO::PARAM_STR);
      $getDiversityData20162017->bindParam(2,$this->college,PDO::PARAM_STR);
      $getDiversityData20162017->execute();
      $rowsGetDiversityData20162017 = $getDiversityData20162017->rowCount();

      if ($rowsGetDiversityData20162017 > 0){

        $data = $getDiversityData20162017->fetch();

        $female20162017 = $data["FAC_FEMALE"];
        $male20162017 = $data["FAC_MALE"];

        $alaskaNative20162017 = $data["FAC_AMERIND_ALASKNAT"];
        $asian20162017 = $data["FAC_ASIAN"];
        $black20162017 = $data["FAC_BLACK"];
        $hispanic20162017 = $data["FAC_HISPANIC"];
        $hawaiiPacificIsland20162017 = $data["FAC_HI_PAC_ISL"];
        $alien20162017 = $data["FAC_NONRESIDENT_ALIEN"];
        $twoOrMore20162017 = $data["FAC_TWO_OR_MORE"];
        $unkown20162017 = $data["FAC_UNKNOWN_RACE_ETHNCTY"];
        $white20162017 = $data["FAC_WHITE"];

      }

      $ayYearBackOne = "AY2015-2016";

      $getDiversityData20152016 = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityPersonnel` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getDiversityData20152016->bindParam(1,$ayYearBackOne,PDO::PARAM_STR);
      $getDiversityData20152016->bindParam(2,$this->college,PDO::PARAM_STR);
      $getDiversityData20152016->execute();
      $rowsGetDiversityData20152016 = $getDiversityData20152016->rowCount();

      if ($rowsGetDiversityData20152016 > 0){

        $data = $getDiversityData20152016->fetch();

        $female20152016 = $data["FAC_FEMALE"];
        $male20152016 = $data["FAC_MALE"];

        $alaskaNative20152016 = $data["FAC_AMERIND_ALASKNAT"];
        $asian20152016 = $data["FAC_ASIAN"];
        $black20152016 = $data["FAC_BLACK"];
        $hispanic20152016 = $data["FAC_HISPANIC"];
        $hawaiiPacificIsland20152016 = $data["FAC_HI_PAC_ISL"];
        $alien20152016 = $data["FAC_NONRESIDENT_ALIEN"];
        $twoOrMore20152016 = $data["FAC_TWO_OR_MORE"];
        $unkown20152016 = $data["FAC_UNKNOWN_RACE_ETHNCTY"];
        $white20152016 = $data["FAC_WHITE"];

      }

      $ayYearBackTwo = "AY2014-2015";

      $getDiversityData20142015 = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityPersonnel` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getDiversityData20142015->bindParam(1,$ayYearBackTwo,PDO::PARAM_STR);
      $getDiversityData20142015->bindParam(2,$this->college,PDO::PARAM_STR);
      $getDiversityData20142015->execute();
      $rowsGetDiversityData20142015 = $getDiversityData20142015->rowCount();

      if ($rowsGetDiversityData20142015 > 0){

        $data = $getDiversityData20142015->fetch();

        $female20142015 = $data["FAC_FEMALE"];
        $male20142015 = $data["FAC_MALE"];

        $alaskaNative20142015 = $data["FAC_AMERIND_ALASKNAT"];
        $asian20142015 = $data["FAC_ASIAN"];
        $black20142015 = $data["FAC_BLACK"];
        $hispanic20142015 = $data["FAC_HISPANIC"];
        $hawaiiPacificIsland20142015 = $data["FAC_HI_PAC_ISL"];
        $alien20142015 = $data["FAC_NONRESIDENT_ALIEN"];
        $twoOrMore20142015 = $data["FAC_TWO_OR_MORE"];
        $unkown20142015 = $data["FAC_UNKNOWN_RACE_ETHNCTY"];
        $white20142015 = $data["FAC_WHITE"];

      }

      echo "
        <div class='container-fluid'>
          <h2 class='text-center'>".$this->college." Faculty Gender Data</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th>Data Year</th>
                      <th>2014</th>
                      <th>2015</th>
                      <th>2016</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Female</td>
                      <td>$female20142015</td>
                      <td>$female20152016</td>
                      <td>$female20162017</td>
                    </tr>
                    <tr>
                      <td>Male</td>
                      <td>$male20142015</td>
                      <td>$male20152016</td>
                      <td>$male20162017</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
              <table>
                <tr>
                  <td>
                    <h5 class='text-center'>2014 Undergraduate Gender</h5>
                    <canvas id='facultyDiversityGender2014' width='200' height='200'></canvas>
                  </td>
                  <td>
                    <h5 class='text-center'>2015 Undergraduate Gender</h5>
                    <canvas id='facultyDiversityGender2015' width='200' height='200'></canvas>
                  </td>
                  <td>
                    <h5 class='text-center'>2016 Undergraduate Gender</h5>
                    <canvas id='facultyDiversityGender2016' width='200' height='200'></canvas>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <h2 class='text-center'>".$this->college." Faculty Race Data</h2>
          <div class='row'>
            <div class='col-md-6'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th>Data Year</th>
                      <th>2014</th>
                      <th>2015</th>
                      <th>2016</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Undergrad American Indian/Alaskian Native</td>
                      <td>$alaskaNative20142015</td>
                      <td>$alaskaNative20152016</td>
                      <td>$alaskaNative20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Asian</td>
                      <td>$asian20142015</td>
                      <td>$asian20152016</td>
                      <td>$asian20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Black</td>
                      <td>$black20142015</td>
                      <td>$black20152016</td>
                      <td>$black20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Hispanic</td>
                      <td>$hispanic20142015</td>
                      <td>$hispanic20152016</td>
                      <td>$hispanic20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Native Hawaiian or Other Pacific Islander</td>
                      <td>$hawaiiPacificIsland20142015</td>
                      <td>$hawaiiPacificIsland20152016</td>
                      <td>$hawaiiPacificIsland20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Alien</td>
                      <td>$alien20142015</td>
                      <td>$alien20142015</td>
                      <td>$alien20142015</td>
                    </tr>
                    <tr>
                      <td>Undergrad Two Or More Races</td>
                      <td>$twoOrMore20142015</td>
                      <td>$twoOrMore20152016</td>
                      <td>$twoOrMore20162017</td>
                    </tr>
                    <tr>
                      <td>Undergrad Unkown Race</td>
                      <td>$unkown20142015</td>
                      <td>$unkown20142015</td>
                      <td>$unkown20142015</td>
                    </tr>
                    <tr>
                      <td>Undergrad White</td>
                      <td>$white20142015</td>
                      <td>$white20152016</td>
                      <td>$white20162017</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-6'>
              <canvas id='facultyDiversityRace' height='220'></canvas>
            </div>
          </div>
        </div>
        <script>
          var ctx = document.getElementById('facultyDiversityGender2014');
          var facultyDiversityGender2014 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$female20142015,$male20142015],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(120, 50, 0, 0.5)'
                ],
                borderColor: [
                  'rgba(116, 0, 11, 1)',
                  'rgba(120, 50, 0, 1)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: true
              },
              animation: {
                onComplete: function(){
                  $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart7.toBase64Image(), name: 'student-diversity-gender-under-2014-".$this->college."', functionNum: '5'});
                }
              }
            }
          });

          var ctx = document.getElementById('facultyDiversityGender2015');
          var underDiversityGender2015 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$female20152016,$male20152016],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(120, 50, 0, 0.5)'
                ],
                borderColor: [
                  'rgba(116, 0, 11, 1)',
                  'rgba(120, 50, 0, 1)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: true
              },
              animation: {
                onComplete: function(){
                  $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart7.toBase64Image(), name: 'student-diversity-gender-under-2015-".$this->college."', functionNum: '5'});
                }
              }
            }
          });

          var ctx = document.getElementById('facultyDiversityGender2016');
          var facultyDiversityGender2016 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$female20162017,$male20162017],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(120, 50, 0, 0.5)'
                ],
                borderColor: [
                  'rgba(116, 0, 11, 1)',
                  'rgba(120, 50, 0, 1)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: true
              },
              animation: {
                onComplete: function(){
                  $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: chart7.toBase64Image(), name: 'student-diversity-gender-under-2016-".$this->college."', functionNum: '5'});
                }
              }
            }
          });

          var ctx = document.getElementById('facultyDiversityRace');
          var facultyDiversityRace = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
              labels: ['2014', '2015', '2016'],
              datasets: [{
                data: [$alaskaNative20142015,$alaskaNative20152016,$alaskaNative20162017],
                backgroundColor: 'rgba(116, 0, 11, 0.5)',
                label: 'American Indian/Alaska Native'
              },{
                data: [$asian20142015,$asian20152016,$asian20162017],
                backgroundColor: 'rgba(120, 50, 0, 0.5)',
                label: 'Asian'
              },{
                data: [$black20142015,$black20152016,$black20162017],
                backgroundColor: 'rgba(17, 100, 0, 0.5)',
                label: 'Black or African American'
              },{
                data: [$hispanic20142015,$hispanic20152016,$hispanic20162017],
                backgroundColor: 'rgba(33, 120, 108, 0.5)',
                label: 'Hispanic or Latino'
              },{
                data: [$hawaiiPacificIsland20142015,$hawaiiPacificIsland20152016,$hawaiiPacificIsland20162017],
                backgroundColor: 'rgba(43, 0, 4, 0.5)',
                label: 'Native Hawaiian or Other Pacific Islander'
              },{
                data: [$alien20142015,$alien20152016,$alien20162017],
                backgroundColor: 'rgba(6, 37, 0, 0.5)',
                label: 'Nonresident Alien'
              },{
                data: [$twoOrMore20142015,$twoOrMore20152016,$twoOrMore20162017],
                backgroundColor: 'rgba(185, 50, 63, 0.5)',
                label: 'Two Or More Races'
              },{
                data: [$unkown20142015,$unkown20152016,$unkown20162017],
                backgroundColor: 'rgba(63, 160, 43, 0.5)',
                label: 'Unknown Race/Ethnicity'
              },{
                data: [$white20142015,$white20152016,$white20162017],
                backgroundColor: 'rgba(191, 110, 52, 0.5)',
                label: 'White'
              }],
              borderWidth: 1
          },
          options: {
            responsive: true,
            legend: {
              display: true
            },
            animation: {
              onComplete: function(){
                $.post('../Resources/Includes/ChartVisualizations.php',{imagebase: myChart.toBase64Image(), name: 'student-diversity-race-under-".$this->college."', functionNum: '5'});
              }
            },
            scaleLabel:{
                display:false
            },
            scales: {
              xAxes: [{
                ticks: {
                    beginAtZero:true,
                    fontSize:11
                },
                scaleLabel:{
                    display:false
                },
                gridLines: {
                },
                stacked: true
              }],
              yAxes: [{
                stacked: true,
                gridLines: {
                    display:false,
                    color: '#fff',
                    zeroLineColor: '#fff',
                    zeroLineWidth: 0
                },
                ticks: {
                  fontSize:11
                },
              }]
            },tooltips: {
              enabled: true
            },
            hover :{
                animationDuration:0
            }
          }
          });

        </script>
      ";

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
