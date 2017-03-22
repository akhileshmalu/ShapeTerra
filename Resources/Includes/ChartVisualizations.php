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

      require_once("Initialize.php");
      $this->initalize = new Initialize();
      $this->initalize->checkSessionStatus();

      $this->connection = $this->initalize->connection;

      $this->ouid = $_SESSION['login_ouid'];
      $this->year = $_SESSION['bpayname'];

      if ($this->ouid == 4) {

        $this->college = $_SESSION['bpouabbrev'];

      }else{

        $this->college = $_SESSION['login_ouabbrev'];

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

    public function chartEnrollementStudentByYear($selectedYear)
    {

      $getAcademicEnrollements = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getAcademicEnrollements->bindParam(1,$selectedYear,PDO::PARAM_STR);
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
                      backgroundColor:[
                        'rgba(116, 0, 11, 0.5)',
                        'rgba(43, 0, 4, 0.5)',
                        'rgba(33, 120, 108, 0.5)',
                        'rgba(0, 75, 65, 0.5)',
                        'rgba(17, 100, 0, 0.5)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(116, 0, 11, 0.5)',
                        'rgba(43, 0, 4, 0.5)',
                        'rgba(33, 120, 108, 0.5)',
                        'rgba(0, 75, 65, 0.5)',
                        'rgba(17, 100, 0, 0.5)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
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
          <h2 class='text-center'>".$this->college." Graduate Gender Data</h2>
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
                  <h5 class='text-center'>2014 Graduate Gender</h5>
                  <canvas id='gradGender2014' width='200' height='200'></canvas>
                </td>
                <td>
                  <h5 class='text-center'>2015 Graduate Gender</h5>
                  <canvas id='gradGender2015' width='200' height='200'></canvas>
                </td>
                <td>
                  <h5 class='text-center'>2016 Graduate Gender</h5>
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
                      <td>Graduate American Indian/Alaskian Native</td>
                      <td>$gradAlaskaNative20142015</td>
                      <td>$gradAlaskaNative20152016</td>
                      <td>$gradAlaskaNative20162017</td>
                    </tr>
                    <tr>
                      <td>Graduate Asian</td>
                      <td>$gradAsian20142015</td>
                      <td>$gradAsian20152016</td>
                      <td>$underGradAsian20162017</td>
                    </tr>
                    <tr>
                      <td>Graduate Black</td>
                      <td>$gradBlack20142015</td>
                      <td>$gradBlack20152016</td>
                      <td>$gradBlack20162017</td>
                    </tr>
                    <tr>
                      <td>Graduate Hispanic</td>
                      <td>$gradHispanic20142015</td>
                      <td>$gradHispanic20152016</td>
                      <td>$gradHispanic20162017</td>
                    </tr>
                    <tr>
                      <td>Graduate Native Hawaiian or Other Pacific Islander</td>
                      <td>$gradHawaiiPacificIsland20142015</td>
                      <td>$gradHawaiiPacificIsland20152016</td>
                      <td>$gradHawaiiPacificIsland20162017</td>
                    </tr>
                    <tr>
                      <td>Graduate Alien</td>
                      <td>$gradAlien20142015</td>
                      <td>$gradAlien20142015</td>
                      <td>$gradAlien20142015</td>
                    </tr>
                    <tr>
                      <td>Graduate Two Or More Races</td>
                      <td>$gradTwoOrMore20142015</td>
                      <td>$gradTwoOrMore20152016</td>
                      <td>$gradTwoOrMore20162017</td>
                    </tr>
                    <tr>
                      <td>Graduate Unkown Race</td>
                      <td>$gradUnkown20142015</td>
                      <td>$gradUnkown20142015</td>
                      <td>$gradUnkown20142015</td>
                    </tr>
                    <tr>
                      <td>Graduate White</td>
                      <td>$gradWhite20142015</td>
                      <td>$gradWhite20152016</td>
                      <td>$gradWhite20162017</td>
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

    public function chartDiversityStudentByYear($selectedYear)
    {

      $getDiversityData = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityStudent` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getDiversityData->bindParam(1,$selectedYear,PDO::PARAM_STR);
      $getDiversityData->bindParam(2,$this->college,PDO::PARAM_STR);
      $getDiversityData->execute();
      $rowsGetDiversityData = $getDiversityData->rowCount();

      if ($rowsGetDiversityData > 0){

        $data = $getDiversityData->fetch();

        $underGradFemale = $data["UGRAD_FEMALE"];
        $underGradMale = $data["UGRAD_MALE"];

        $underGradAlaskaNative = $data["UGRAD_AMERIND_ALASKNAT"];
        $underGradAsian = $data["UGRAD_ASIAN"];
        $underGradBlack = $data["UGRAD_BLACK"];
        $underGradHispanic = $data["UGRAD_HISPANIC"];
        $underGradHawaiiPacificIsland = $data["UGRAD_HI_PAC_ISL"];
        $underGradAlien = $data["UGRAD_NONRESIDENT_ALIEN"];
        $underGradTwoOrMore = $data["UGRAD_TWO_OR_MORE"];
        $underGradUnkown = $data["UGRAD_UNKNOWN_RACE_ETHNCTY"];
        $underGradWhite = $data["UGRAD_WHITE"];

        $gradFemale = $data["GRAD_FEMALE"];
        $gradMale = $data["GRAD_MALE"];

        $gradAlaskaNative = $data["GRAD_AMERIND_ALASKNAT"];
        $gradAsian = $data["GRAD_ASIAN"];
        $gradBlack = $data["GRAD_BLACK"];
        $gradHispanic = $data["GRAD_HISPANIC"];
        $gradHawaiiPacificIsland = $data["GRAD_HI_PAC_ISL"];
        $gradAliens = $data["GRAD_NONRESIDENT_ALIEN"];
        $gradDoubleRace = $data["GRAD_TWO_OR_MORE"];
        $gradUnknown = $data["GRAD_UNKNOWN_RACE_ETHNCTY"];
        $gradWhite = $data["GRAD_WHITE"];

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
                      <th>Data Type</th>
                      <th>#</th>
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
            <div class='col-md-8'>
              <canvas id='underDiversityGenderByYear' width='200' height='200'></canvas>
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
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Undergrad American Indian/Alaskian Native</td>
                      <td>$underGradAlaskaNative</td>
                    </tr>
                    <tr>
                      <td>Undergrad Asian</td>
                      <td>$underGradAsian</td>
                    </tr>
                    <tr>
                      <td>Undergrad Black</td>
                      <td>$underGradBlack</td>
                    </tr>
                    <tr>
                      <td>Undergrad Hispanic</td>
                      <td>$underGradHispanic</td>
                    </tr>
                    <tr>
                      <td>Undergrad Native Hawaiian or Other Pacific Islander</td>
                      <td>$underGradHawaiiPacificIsland</td>
                    </tr>
                    <tr>
                      <td>Undergrad Alien</td>
                      <td>$underGradAlien</td>
                    </tr>
                    <tr>
                      <td>Undergrad Two Or More Races</td>
                      <td>$underGradTwoOrMore</td>
                    </tr>
                    <tr>
                      <td>Undergrad Unkown Race</td>
                      <td>$underGradUnkown</td>
                    </tr>
                    <tr>
                      <td>Undergrad White</td>
                      <td>$underGradWhite</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-6'>
              <canvas id='underDiversityRaceByYear' height='220'></canvas>
            </div>
          </div>
          <h2 class='text-center'>".$this->college." Graduate Gender Data</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th>Data Type</th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Female</td>
                      <td>$gradFemale</td>
                    </tr>
                    <tr>
                      <td>Male</td>
                      <td>$gradMale</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
            <table>
              <tr>
                <td>
                  <h5 class='text-center'>Graduate Gender</h5>
                  <canvas id='gradDiversityGenderByYear' width='200' height='200'></canvas>
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
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Graduate American Indian/Alaskian Native</td>
                      <td>$gradAlaskaNative</td>
                    </tr>
                    <tr>
                      <td>Graduate Asian</td>
                      <td>$gradAsian</td>
                    </tr>
                    <tr>
                      <td>Graduate Black</td>
                      <td>$gradBlack</td>
                    </tr>
                    <tr>
                      <td>Graduate Hispanic</td>
                      <td>$gradHispanic</td>
                    </tr>
                    <tr>
                      <td>Graduate Native Hawaiian or Other Pacific Islander</td>
                      <td>$gradHawaiiPacificIsland</td>
                    </tr>
                    <tr>
                      <td>Graduate Alien</td>
                      <td>$gradAlien</td>
                    </tr>
                    <tr>
                      <td>Graduate Two Or More Races</td>
                      <td>$gradTwoOrMore</td>
                    </tr>
                    <tr>
                      <td>Graduate Unknown Race</td>
                      <td>$gradUnkown</td>
                    </tr>
                    <tr>
                      <td>Graduate White</td>
                      <td>$gradWhite</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
              <canvas id='gradDiversityRaceByYear' height='220'></canvas>
            </div>
          </div>
        </div>
        <script>
          var ctx = document.getElementById('underDiversityGenderByYear');
          var underDiversityGenderByYear = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$underGradFemale,$underGradMale],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: false
              }
            }
          });

          var ctx = document.getElementById('underDiversityRaceByYear');
          var underDiversityRaceByYear = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['American Indian/Alaskian Native', 'Asian','Undergrad Black','Hispanic','Native Hawaiian or Other Pacific Islander','Alien','Two Or More Races','Unknown Race','White'],
              datasets: [{
                label: 'Gender',
                data: [$underGradAlaskaNative,$underGradAsian,$underGradBlack,$underGradHispanic,$underGradHawaiiPacificIsland,$underGradAlien,$underGradTwoOrMore,$underGradUnkown,$underGradWhite],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: false
              }
            }
          });

          var ctx = document.getElementById('gradDiversityGenderByYear');
          var gradDiversityGenderByYear = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$underGradFemale,$underGradMale],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: false
              }
            }
          });

          var ctx = document.getElementById('gradDiversityRaceByYear');
          var gradDiversityRaceByYear = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['American Indian/Alaskian Native', 'Asian','Undergrad Black','Hispanic','Native Hawaiian or Other Pacific Islander','Alien','Two Or More Races','Unknown Race','White'],
              datasets: [{
                label: 'Gender',
                data: [$underGradAlaskaNative,$underGradAsian,$underGradBlack,$underGradHispanic,$underGradHawaiiPacificIsland,$underGradAlien,$underGradTwoOrMore,$underGradUnkown,$underGradWhite],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: false
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

    public function chartDiversityFacultyByYear($selectedYear)
    {

      $getDiversityData = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityPersonnel` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
      $getDiversityData->bindParam(1,$selectedYear,PDO::PARAM_STR);
      $getDiversityData->bindParam(2,$this->college,PDO::PARAM_STR);
      $getDiversityData->execute();
      $rowsGetDiversityData = $getDiversityData->rowCount();

      if ($rowsGetDiversityData > 0){

        $data = $getDiversityData->fetch();

        $female = $data["FAC_FEMALE"];
        $male = $data["FAC_MALE"];

        $alaskaNative = $data["FAC_AMERIND_ALASKNAT"];
        $asian = $data["FAC_ASIAN"];
        $black = $data["FAC_BLACK"];
        $hispanic = $data["FAC_HISPANIC"];
        $hawaiiPacificIsland = $data["FAC_HI_PAC_ISL"];
        $alien = $data["FAC_NONRESIDENT_ALIEN"];
        $twoOrMore = $data["FAC_TWO_OR_MORE"];
        $unkown = $data["FAC_UNKNOWN_RACE_ETHNCTY"];
        $white = $data["FAC_WHITE"];

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
                      <th>Data Type</th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Female</td>
                      <td>$female</td>
                    </tr>
                    <tr>
                      <td>Male</td>
                      <td>$male</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
              <table>
                <tr>
                  <td>
                    <h5 class='text-center'>Undergraduate Gender</h5>
                    <canvas id='facultyDiversityGenderByYear' width='200' height='200'></canvas>
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
                      <th>Data Type</th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Undergrad American Indian/Alaskian Native</td>
                      <td>$alaskaNative</td>
                    </tr>
                    <tr>
                      <td>Undergrad Asian</td>
                      <td>$asian</td>
                    </tr>
                    <tr>
                      <td>Undergrad Black</td>
                      <td>$black</td>
                    </tr>
                    <tr>
                      <td>Undergrad Hispanic</td>
                      <td>$hispanic</td>
                    </tr>
                    <tr>
                      <td>Undergrad Native Hawaiian or Other Pacific Islander</td>
                      <td>$hawaiiPacificIsland</td>
                    </tr>
                    <tr>
                      <td>Undergrad Alien</td>
                      <td>$alien</td>
                    </tr>
                    <tr>
                      <td>Undergrad Two Or More Races</td>
                      <td>$twoOrMore</td>
                    </tr>
                    <tr>
                      <td>Undergrad Unkown Race</td>
                      <td>$unknown</td>
                    </tr>
                    <tr>
                      <td>Undergrad White</td>
                      <td>$white</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-6'>
              <canvas id='facultyDiversityRaceByYear' height='220'></canvas>
            </div>
          </div>
        </div>
        <script>

          var ctx = document.getElementById('underDiversityGenderByYear');
          var underDiversityGenderByYear = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$female,$male],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: false
              }
            }
          });

          var ctx = document.getElementById('underDiversityRaceByYear');
          var underDiversityRaceByYear = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['American Indian/Alaskian Native', 'Asian','Undergrad Black','Hispanic','Native Hawaiian or Other Pacific Islander','Alien','Two Or More Races','Unknown Race','White'],
              datasets: [{
                label: 'Gender',
                data: [$alaskaNative,$asian,$black,$hispanic,$hawaiiPacificIsland,$alien,$twoOrMore,$unknown,$white],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: false
              }
            }
          });

        </script>
        ";

    }

    public function chartStudentOutcomes()
    {

      $currentYear = "2015";

      $getStudentOutcomesRetention2015 = $this->connection->prepare("SELECT * FROM `IR_AC_Retention` WHERE FTFT_COHORT = ? AND OU_ABBREV = ?");
      $getStudentOutcomesRetention2015->bindParam(1,$currentYear,PDO::PARAM_STR);
      $getStudentOutcomesRetention2015->bindParam(2,$this->college,PDO::PARAM_STR);
      $getStudentOutcomesRetention2015->execute();
      $rowsGetStudentOutcomesRetention2015 = $getStudentOutcomesRetention2015->rowCount();

      if ($rowsGetStudentOutcomesRetention2015){

        while($data = $getStudentOutcomesRetention2015->fetch()){

          $retentionFirstYear2015 = $data["RETENTION_FIRST_YR"];
          $retentionSecondYear2015 = $data["RETENTION_SECOND_YR"];

        }

      }

      $currentYear = "2014";

      $getStudentOutcomesRetention2014 = $this->connection->prepare("SELECT * FROM `IR_AC_Retention` WHERE FTFT_COHORT = ? AND OU_ABBREV = ?");
      $getStudentOutcomesRetention2014->bindParam(1,$currentYear,PDO::PARAM_STR);
      $getStudentOutcomesRetention2014->bindParam(2,$this->college,PDO::PARAM_STR);
      $getStudentOutcomesRetention2014->execute();
      $rowsGetStudentOutcomesRetention2014 = $getStudentOutcomesRetention2014->rowCount();

      if ($rowsGetStudentOutcomesRetention2014){

        while($data = $getStudentOutcomesRetention2014->fetch()){

          $retentionFirstYear2014 = $data["RETENTION_FIRST_YR"];
          $retentionSecondYear2014 = $data["RETENTION_SECOND_YR"];

        }

      }

      $currentYear = "2013";

      $getStudentOutcomesRetention2013 = $this->connection->prepare("SELECT * FROM `IR_AC_Retention` WHERE FTFT_COHORT = ? AND OU_ABBREV = ?");
      $getStudentOutcomesRetention2013->bindParam(1,$currentYear,PDO::PARAM_STR);
      $getStudentOutcomesRetention2013->bindParam(2,$this->college,PDO::PARAM_STR);
      $getStudentOutcomesRetention2013->execute();
      $rowsGetStudentOutcomesRetention2013 = $getStudentOutcomesRetention2013->rowCount();

      if ($rowsGetStudentOutcomesRetention2013){

        while($data = $getStudentOutcomesRetention2013->fetch()){

          $retentionFirstYear2013 = $data["RETENTION_FIRST_YR"];
          $retentionSecondYear2013 = $data["RETENTION_SECOND_YR"];

        }

      }

      $currentYear = "2012";

      $getStudentOutcomesRetention2012 = $this->connection->prepare("SELECT * FROM `IR_AC_Retention` WHERE FTFT_COHORT = ? AND OU_ABBREV = ?");
      $getStudentOutcomesRetention2012->bindParam(1,$currentYear,PDO::PARAM_STR);
      $getStudentOutcomesRetention2012->bindParam(2,$this->college,PDO::PARAM_STR);
      $getStudentOutcomesRetention2012->execute();
      $rowsGetStudentOutcomesRetention2012 = $getStudentOutcomesRetention2012->rowCount();

      if ($rowsGetStudentOutcomesRetention2012){

        while($data = $getStudentOutcomesRetention2012->fetch()){

          $retentionFirstYear2012 = $data["RETENTION_FIRST_YR"];
          $retentionSecondYear2012 = $data["RETENTION_SECOND_YR"];

        }

      }

      $currentYear = "2010";

      $getStudentOutcomesGradRate2010 = $this->connection->prepare("SELECT * FROM `IR_AC_GraduationRate` WHERE FTFT_COHORT = ? AND OU_ABBREV = ?");
      $getStudentOutcomesRetention2010->bindParam(1,$currentYear,PDO::PARAM_STR);
      $getStudentOutcomesRetention2010->bindParam(2,$this->college,PDO::PARAM_STR);
      $getStudentOutcomesRetention2010->execute();
      $rowsGetStudentOutcomesRetention2010 = $getStudentOutcomesRetention2012->rowCount();

      if ($rowsGetStudentOutcomesRetention2010){

        while($data = $getStudentOutcomesRetention2010->fetch()){

          $graduationRateFirstYear2010 = $data["RETENTION_FIRST_YR"];
          $graduationRateSecondYear2010 = $data["RETENTION_SECOND_YR"];

        }

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
                      <th>2012</th>
                      <th>2013</th>
                      <th>2014</th>
                      <th>2015</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>First Year</td>
                      <td>$retentionFirstYear2012</td>
                      <td>$retentionFirstYear2013</td>
                      <td>$retentionFirstYear2014</td>
                      <td>$retentionFirstYear2015</td>
                    </tr>
                    <tr>
                      <td>Second Year</td>
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
                    <canvas id='retentionRatesFirstYear' width='200' height='200'></canvas>
                  </td>
                  <td>
                    <canvas id='retentionRatesSecondYear' width='200' height='200'></canvas>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <script>
          var ctx = document.getElementById('retentionRatesFirstYear');
          var retentionRatesFirstYear = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['2012','2013','2014','2015'],
              datasets: [{
                label: 'First Year',
                data: [$retentionFirstYear2012,$retentionFirstYear2013,$retentionFirstYear2014,$retentionFirstYear2015],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: false
              }
            }
          });

          var ctx = document.getElementById('retentionRatesSecondYear');
          var retentionRatesSecondYear = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['2012','2013','2014','2015'],
              datasets: [{
                label: 'Second Year',
                data: [$retentionSecondYear2012,$retentionSecondYear2013,$retentionSecondYear2014,$retentionSecondYear2015],
                backgroundColor: [
                  'rgba(116, 0, 11, 0.5)',
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)',
                  'rgba(0, 75, 65, 0.5)',
                  'rgba(17, 100, 0, 0.5)'
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: false
              }
            }
          });
        </script>
      ";

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
