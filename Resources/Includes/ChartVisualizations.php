<?php

/*TODO restrict by user's college*/

error_reporting(1);
@ini_set('display_errors', 1);
session_start();
ob_start();

if (isset($_POST['functionNum']))
    $function = $_POST['functionNum'];

if (isset($_GET['functionNum']))
    $function = $_GET['functionNum'];


if (!empty($function)) {

    $ChartVisualizations = new ChartVisualizations();

    switch ($function) {
        case 1:
            $ChartVisualizations->chartEnrollementStudentByYear($_GET["yearDescription"], $_GET["ouchoice"]);
            break;
        case 2:
            $ChartVisualizations->chartDiversityStudentByYear($_GET["yearDescription"], $_GET["ouchoice"]);
            break;
        case 3:
            $ChartVisualizations->chartDiversityFacultyByYear($_GET["yearDescription"], $_GET["ouchoice"]);
            break;
        case 5:
            $ChartVisualizations->exportToPng($_POST["imagebase"], $_POST["name"]);
            break;
        default:
            break;
    }

}

Class ChartVisualizations
{

    public $college, $year, $ouid, $colorArray;
    private $conection;

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

        } else {

            $this->college = $_SESSION['login_ouabbrev'];

        }

    }

    public function chartEnrollementStudent()
    {

        $currentYear = "AY2016-2017";

        $getAcademicEnrollements20162017 = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getAcademicEnrollements20162017->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getAcademicEnrollements20162017->bindParam(2, $this->college, PDO::PARAM_STR);
        $getAcademicEnrollements20162017->execute();
        $rowsAcademicEnrollements20162017 = $getAcademicEnrollements20162017->rowCount();

        if ($rowsAcademicEnrollements20162017 > 0) {

            while ($data = $getAcademicEnrollements20162017->fetch()) {

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
        $getAcademicEnrollements20152016->bindParam(1, $ayYearBackOne, PDO::PARAM_STR);
        $getAcademicEnrollements20152016->bindParam(2, $this->college, PDO::PARAM_STR);
        $getAcademicEnrollements20152016->execute();
        $rowsAcademicEnrollements20152016 = $getAcademicEnrollements20152016->rowCount();

        if ($rowsAcademicEnrollements20152016 > 0) {

            while ($data = $getAcademicEnrollements20152016->fetch()) {

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
        $getAcademicEnrollements20142015->bindParam(1, $ayYearBackTwo, PDO::PARAM_STR);
        $getAcademicEnrollements20142015->bindParam(2, $this->college, PDO::PARAM_STR);
        $getAcademicEnrollements20142015->execute();
        $rowsAcademicEnrollements20142015 = $getAcademicEnrollements20142015->rowCount();

        if ($rowsAcademicEnrollements20142015 > 0) {

            while ($data = $getAcademicEnrollements20142015->fetch()) {

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
        $getAcademicEnrollements20142015->bindParam(1, $ayYearBackTwo, PDO::PARAM_STR);
        $getAcademicEnrollements20142015->bindParam(2, $this->college, PDO::PARAM_STR);
        $getAcademicEnrollements20142015->execute();
        $rowsAcademicEnrollements20142015 = $getAcademicEnrollements20142015->rowCount();

        if ($rowsAcademicEnrollements20142015 > 0) {

            while ($data = $getAcademicEnrollements20142015->fetch()) {

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
            <h2 class='text-center'>" . $this->college . " Enrollments Data Undergraduate</h2>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                    <tr>
                      <th><b>Undergraduate</b></th>
                      <th><b>$total2016Undergraduate</b></th>
                      <th><b>$total2015Undergraduate</b></th>
                      <th><b>$total2014Undergraduate</b></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Freshman</td>
                      <td>$freshman20162017</td>
                      <td>$freshman20152016</td>
                      <td>$freshman20142015</td>
                    </tr>
                    <tr>
                      <td>Sophmore</td>
                      <td>$sophmore20162017</td>
                      <td>$sophmore20152016</td>
                      <td>$sophmore20142015</td>
                    </tr>
                    <tr>
                      <td>Junior</td>
                      <td>$junior20162017</td>
                      <td>$junior20152016</td>
                      <td>$junior20142015</td>
                    </tr>
                    <tr>
                      <td>Senior</td>
                      <td>$seniors20162017</td>
                      <td>$seniors20152016</td>
                      <td>$seniors20142015</td>
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
            <h2 class='text-center'>" . $this->college . " Enrollments Data Graduate</h2>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                    <tr>
                      <th><b>Graduate</b></th>
                      <th><b>$total2016Graduate</b></th>
                      <th><b>$total2015Graduate</b></th>
                      <th><b>$total2014Graduate</b></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Masters</td>
                      <td>$masters20162017</td>
                      <td>$masters20152016</td>
                      <td>$masters20142015</td>
                    </tr>
                    <tr>
                      <td>Doctoral</td>
                      <td>$doctorial20162017</td>
                      <td>$doctorial20152016</td>
                      <td>$doctorial20142015</td>
                    </tr>
                    <tr>
                      <td>Graduate Certificate</td>
                      <td>$cert20162017</td>
                      <td>$cert20152016</td>
                      <td>$cert20142015</td>
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
            <h2 class='text-center'>" . $this->college . " Enrollments Data All</h2>
            <div class='col-md-5'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                    <th><b>Undergraduate</b></th>
                    <th><b>$total2014Undergraduate</b></th>
                    <th><b>$total2015Undergraduate</b></th>
                    <th><b>$total2016Undergraduate</b></th>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Freshman</td>
                      <td>$freshman20162017</td>
                      <td>$freshman20152016</td>
                      <td>$freshman20142015</td>
                    </tr>
                    <tr>
                      <td>Sophmore</td>
                      <td>$sophmore20162017</td>
                      <td>$sophmore20152016</td>
                      <td>$sophmore20142015</td>
                    </tr>
                    <tr>
                      <td>Junior</td>
                      <td>$junior20162017</td>
                      <td>$junior20152016</td>
                      <td>$junior20142015</td>
                    </tr>
                    <tr>
                      <td>Senior</td>
                      <td>$seniors20162017</td>
                      <td>$seniors20152016</td>
                      <td>$seniors20142015</td>
                    </tr>
                    <tr>
                      <th><b>Graduate</b></th>
                      <th><b>$total2014Graduate</b></th>
                      <th><b>$total2015Graduate</b></th>
                      <th><b>$total2016Graduate</b></th>
                    </tr>
                    <tr>
                      <td>Masters</td>
                      <td>$masters20162017</td>
                      <td>$masters20152016</td>
                      <td>$masters20142015</td>
                    </tr>
                    <tr>
                      <td>Doctorial</td>
                      <td>$doctorial20162017</td>
                      <td>$doctorial20152016</td>
                      <td>$doctorial20142015</td>
                    </tr>
                    <tr>
                      <td>Graduate Certificate</td>
                      <td>$cert20162017</td>
                      <td>$cert20152016</td>
                      <td>$cert20142015</td>
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
          var testPost;
          var red = 'rgba(189, 59, 71, 0.8)';
          var blue = 'rgba(87, 147, 243, 0.8)';
          var purple = 'rgba(114, 92, 164, 0.8)';
          var pink = 'rgba(221, 77, 121, 0.8)';
          var green = 'rgba(212, 223, 90, 0.8)';
          var ctx = document.getElementById('chartEnrollementStudentAll');
          var chartEnrollementStudentAll = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
              labels: ['2014', '2015', '2016'],
              datasets: [{
                data: [$freshman20142015,$freshman20152016,$freshman20162017],
                backgroundColor: red,
                label: 'Freshman'
              },{
                data: [$sophmore20142015,$sophmore20152016,$sophmore20162017],
                backgroundColor: blue,
                label: 'Sophmore'
              },{
                data: [$junior20142015,$junior20152016,$junior20162017],
                backgroundColor: purple,
                label: 'Junior'
              },{
                data: [$seniors20142015,$seniors20152016,$seniors20162017],
                backgroundColor: green,
                label: 'Senior'
              },{
                data: [$masters20142015,$masters20152016,$masters20162017],
                backgroundColor: 'rgba(33, 120, 108, 0.5)',
                label: 'Masters'
              },{
                data: [$doctorial20142015,$doctorial20152016,$doctorial20162017],
                backgroundColor: pink,
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
                $.post('ChartVisualizations.php',{imagebase: chartEnrollementStudentAll.toBase64Image(), name: 'student-enrollements-all-" . $this->college . "', functionNum: '5'});
              }
            },
            scaleLabel:{
                display:false
            },
            scales: {
              xAxes: [{
                ticks: {
                    beginAtZero:true
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
                }
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
        var chartEnrollementStudentUnder = new Chart(ctx, {
          type: 'horizontalBar',
          data: {
            labels: ['2014', '2015', '2016'],
            datasets: [{
              data: [$freshman20142015,$freshman20152016,$freshman20162017],
              backgroundColor: red,
              label: 'Freshman'
            },{
              data: [$sophmore20142015,$sophmore20152016,$sophmore20162017],
              backgroundColor: blue,
              label: 'Sophmore'
            },{
              data: [$junior20142015,$junior20152016,$junior20162017],
              backgroundColor: purple,
              label: 'Junior'
            },{
              data: [$seniors20142015,$seniors20152016,$seniors20162017],
              backgroundColor: green,
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
              $.post('ChartVisualizations.php',{imagebase: chartEnrollementStudentUnder.toBase64Image(), name: 'student-enrollements-under-" . $this->college . "', functionNum: '5'});
            }
          },
          scaleLabel:{
              display:false
          },
          scales: {
            xAxes: [{
              ticks: {
                  beginAtZero:true
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
      var chartEnrollementStudentGrad = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
          labels: ['2014', '2015', '2016'],
          datasets: [{
            data: [$masters20142015,$masters20152016,$masters20162017],
            backgroundColor: red,
            label: 'Masters'
          },{
            data: [$doctorial20142015,$doctorial20152016,$doctorial20162017],
            backgroundColor: blue,
            label: 'Doctorial'
          },{
            data: [$cert20142015,$cert20152016,$cert20162017],
            backgroundColor: purple,
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
            $.post('ChartVisualizations.php',{imagebase: chartEnrollementStudentGrad.toBase64Image(), name: 'student-enrollements-grad-" . $this->college . "', functionNum: '5'});
          }
        },
        scaleLabel:{
            display:false
        },
        scales: {
          xAxes: [{
            ticks: {
                beginAtZero:true
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

    public function chartEnrollementStudentByYear($selectedYear, $ouAbbrev)
    {

        $getAcademicEnrollements = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getAcademicEnrollements->bindParam(1, $selectedYear, PDO::PARAM_STR);
        $getAcademicEnrollements->bindParam(2, $ouAbbrev, PDO::PARAM_STR);
        $getAcademicEnrollements->execute();
        $rowsGetAcademicEncrollements = $getAcademicEnrollements->rowCount();

        if ($rowsGetAcademicEncrollements > 0) {

            while ($data = $getAcademicEnrollements->fetch()) {

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
              <h2 class='text-center'>" . $this->college . " Data</h2>
              <div class='container-fluid'>
                <div class='row'>
                  <div class='col-md-6'>
                    <div class='table-responsive'>
                      <table class='table table-condensed'>
                        <thead>
                          <tr>
                            <th></th>
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
              var red = 'rgba(189, 59, 71, 0.8)';
          var blue = 'rgba(87, 147, 243, 0.8)';
          var purple = 'rgba(114, 92, 164, 0.8)';
          var pink = 'rgba(221, 77, 121, 0.8)';
          var green = 'rgba(212, 223, 90, 0.8)';
                var ctx = document.getElementById('chart1');
                var myChart = new Chart(ctx, {
                  type: 'doughnut',
                  data: {
                    labels: ['Freshman', 'Sophmore', 'Junior', 'Senior', 'Masters', 'Doctors','Medicine','Law','Pharama','Certification'],
                    datasets: [{
                      label: 'School Population',
                      data: [$freshman, $sophmore, $junior, $seniors, $masters, $doctorial, $medicine, $law, $phram, $cert],
                      backgroundColor:[
                        red,
                        blue,
                        purple,
                        green,
                        pink,
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
                      display: true
                    },
                    animation: {
                      onComplete: function(){

                      }
                    }
                  }
                });
              </script>
            ";

            }

        } else {

            echo "<h5>There is no data.</h5>";

        }

    }

    public function chartDiversityStudent()
    {

        $currentYear = "AY2016-2017";

        $getDiversityData20162017 = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityStudent` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getDiversityData20162017->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getDiversityData20162017->bindParam(2, $this->college, PDO::PARAM_STR);
        $getDiversityData20162017->execute();
        $rowsGetDiversityData20162017 = $getDiversityData20162017->rowCount();

        if ($rowsGetDiversityData20162017 > 0) {

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
        $getDiversityData20152016->bindParam(1, $ayYearBackOne, PDO::PARAM_STR);
        $getDiversityData20152016->bindParam(2, $this->college, PDO::PARAM_STR);
        $getDiversityData20152016->execute();
        $rowsGetDiversityData20152016 = $getDiversityData20152016->rowCount();

        if ($rowsGetDiversityData20152016 > 0) {

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
        $getDiversityData20142015->bindParam(1, $ayYearBackTwo, PDO::PARAM_STR);
        $getDiversityData20142015->bindParam(2, $this->college, PDO::PARAM_STR);
        $getDiversityData20142015->execute();
        $rowsGetDiversityData20142015 = $getDiversityData20142015->rowCount();

        if ($rowsGetDiversityData20142015 > 0) {

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
          <h2 class='text-center'>" . $this->college . " Undergraduate Gender Data</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Female</td>
                      <td>$underGradFemale20162017</td>
                      <td>$underGradFemale20152016</td>
                      <td>$underGradFemale20162017</td>
                    </tr>
                    <tr>
                      <td>Male</td>
                      <td>$underGradMale20162017</td>
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
                    <h5 class='text-center' style='padding-bottom:20px;'>2016</h5>
                    <canvas id='underDiversityGender2016' width='200' height='188'></canvas>
                  </td>
                  <td>
                    <h5 class='text-center'>2015</h5>
                    <canvas id='underDiversityGender2015' width='200' height='220'></canvas>
                  </td>
                  <td>
                    <h5 class='text-center' style='padding-bottom:20px;'>2014</h5>
                    <canvas id='underDiversityGender2014' width='200' height='188'></canvas>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <h2 class='text-center'>" . $this->college . " Data Undergraduate Race</h2>
          <div class='row'>
            <div class='col-md-6'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>American Indian/Alaskan Native</td>
                      <td>$underGradAlaskaNative20162017</td>
                      <td>$underGradAlaskaNative20152016</td>
                      <td>$underGradAlaskaNative20142015</td>
                    </tr>
                    <tr>
                      <td>Asian</td>
                      <td>$underGradAsian20162017</td>
                      <td>$underGradAsian20152016</td>
                      <td>$underGradAsian20142015</td>
                    </tr>
                    <tr>
                      <td>Black</td>
                      <td>$underGradBlack20162017</td>
                      <td>$underGradBlack20152016</td>
                      <td>$underGradBlack20142015</td>
                    </tr>
                    <tr>
                      <td>Hispanic</td>
                      <td>$underGradHispanic20162017</td>
                      <td>$underGradHispanic20152016</td>
                      <td>$underGradHispanic20142015</td>
                    </tr>
                    <tr>
                      <td>Native Hawaiian or Other Pacific Islander</td>
                      <td>$underGradHawaiiPacificIsland20162017</td>
                      <td>$underGradHawaiiPacificIsland20152016</td>
                      <td>$underGradHawaiiPacificIsland20142015</td>
                    </tr>
                    <tr>
                      <td>Nonresident Alien</td>
                      <td>$underGradAlien20162017</td>
                      <td>$underGradAlien20152016</td>
                      <td>$underGradAlien20142015</td>
                    </tr>
                    <tr>
                      <td>Two Or More Races</td>
                      <td>$underGradTwoOrMore20162017</td>
                      <td>$underGradTwoOrMore20152016</td>
                      <td>$underGradTwoOrMore20142015</td>
                    </tr>
                    <tr>
                      <td>Unkown Race</td>
                      <td>$underGradUnkown20162017</td>
                      <td>$underGradUnkown20152016</td>
                      <td>$underGradUnkown20142015</td>
                    </tr>
                    <tr>
                      <td>White</td>
                      <td>$underGradWhite20162017</td>
                      <td>$underGradWhite20152016</td>
                      <td>$underGradWhite20142015</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-6'>
              <canvas id='underDiversityRace' width='300' height='220'></canvas>
            </div>
          </div>
          <h2 class='text-center'>" . $this->college . " Graduate Gender Data</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Female</td>
                      <td>$gradFemale20162017</td>
                      <td>$gradFemale20152016</td>
                      <td>$gradFemale20142015</td>
                    </tr>
                    <tr>
                      <td>Male</td>
                      <td>$gradMale20162017</td>
                      <td>$gradMale20152016</td>
                      <td>$gradMale20142015</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
            <table>
              <tr>
                <td>
                  <h5 class='text-center' style='padding-bottom:20px;'>2016</h5>
                  <canvas id='gradGender2016' width='200' height='188'></canvas>
                </td>
                <td>
                  <h5 class='text-center'>2015</h5>
                  <canvas id='gradGender2015' width='200' height='220'></canvas>
                </td>
                <td>
                  <h5 class='text-center' style='padding-bottom:20px;'>2014</h5>
                  <canvas id='gradGender2014' width='200' height='188'></canvas>
                </td>
              </tr>
            </table>
            </div>
          </div>
          <h2 class='text-center'>" . $this->college . " Data Graduate Race</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>American Indian/Alaskan Native</td>
                      <td>$gradAlaskaNative20162017</td>
                      <td>$gradAlaskaNative20152016</td>
                      <td>$gradAlaskaNative20142015</td>
                    </tr>
                    <tr>
                      <td>Asian</td>
                      <td>$gradAsian20162017</td>
                      <td>$gradAsian20152016</td>
                      <td>$gradAsian20142015</td>
                    </tr>
                    <tr>
                      <td>Black</td>
                      <td>$gradBlack20162017</td>
                      <td>$gradBlack20152016</td>
                      <td>$gradBlack20142015</td>
                    </tr>
                    <tr>
                      <td>Hispanic</td>
                      <td>$gradHispanic20162017</td>
                      <td>$gradHispanic20152016</td>
                      <td>$gradHispanic20142015</td>
                    </tr>
                    <tr>
                      <td>Native Hawaiian or Other Pacific Islander</td>
                      <td>$gradHawaiiPacificIsland20162017</td>
                      <td>$gradHawaiiPacificIsland20152016</td>
                      <td>$gradHawaiiPacificIsland20142015</td>
                    </tr>
                    <tr>
                      <td>Nonresident Alien</td>
                      <td>$gradAliens20162017</td>
                      <td>$gradAliens20152016</td>
                      <td>$gradAliens20142015</td>
                    </tr>
                    <tr>
                      <td>Two Or More Races</td>
                      <td>$gradDoubleRace20162017</td>
                      <td>$gradDoubleRace20152016</td>
                      <td>$gradDoubleRace20142015</td>
                    </tr>
                    <tr>
                      <td>Unkown Race</td>
                      <td>$gradUnknown20162017</td>
                      <td>$gradUnknown20152016</td>
                      <td>$gradUnknown20142015</td>
                    </tr>
                    <tr>
                      <td>White</td>
                      <td>$gradWhite20162017</td>
                      <td>$gradWhite20152016</td>
                      <td>$gradWhite20142015</td>
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
          var red = 'rgba(189, 59, 71, 0.8)';
          var blue = 'rgba(87, 147, 243, 0.8)';
          var purple = 'rgba(114, 92, 164, 0.8)';
          var pink = 'rgba(221, 77, 121, 0.8)';
          var green = 'rgba(212, 223, 90, 0.8)';
          var ctx = document.getElementById('underDiversityGender2014');
          var underDiversityGender2014 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$underGradMale20142015,$underGradFemale20142015],
                backgroundColor: [
                  blue,
                  red
                ],
                borderColor: [
                  blue,
                  red
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
                  $.post('ChartVisualizations.php',{imagebase: underDiversityGender2014.toBase64Image(), name: 'student-diversity-gender-under-2014-" . $this->college . "', functionNum: '5'});
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
                data: [$underGradMale20152016,$underGradFemale20152016],
                backgroundColor: [
                  blue,
                  red
                ],
                borderColor: [
                  blue,
                  red
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
                  $.post('ChartVisualizations.php',{imagebase: underDiversityGender2015.toBase64Image(), name: 'student-diversity-gender-under-2015-" . $this->college . "', functionNum: '5'});
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
                data: [$underGradMale20162017,$underGradFemale20162017],
                backgroundColor: [
                  blue,
                  red
                ],
                borderColor: [
                  blue,
                  red
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
                  $.post('ChartVisualizations.php',{imagebase: underDiversityGender2016.toBase64Image(), name: 'student-diversity-gender-under-2016-" . $this->college . "', functionNum: '5'});
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
                backgroundColor: red,
                label: 'American Indian/Alaska Native'
              },{
                data: [$underGradAsian20142015,$underGradAsian20152016,$underGradAsian20162017],
                backgroundColor: purple,
                label: 'Asian'
              },{
                data: [$underGradBlack20142015,$underGradBlack20152016,$underGradBlack20162017],
                backgroundColor: green,
                label: 'Black or African American'
              },{
                data: [$underGradHispanic20142015,$underGradHispanic20152016,$underGradHispanic20162017],
                backgroundColor: pink,
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
                backgroundColor: blue,
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
                $.post('ChartVisualizations.php',{imagebase: underDiversityRace.toBase64Image(), name: 'student-diversity-race-under-" . $this->college . "', functionNum: '5'});
              }
            },
            scaleLabel:{
                display:false
            },
            scales: {
              xAxes: [{
                ticks: {
                    beginAtZero:true
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
                data: [$gradMale20142015,$gradFemale20142015],
                backgroundColor: [
                  blue,
                  red
                ],
                borderColor: [
                  blue,
                  red
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
                  $.post('ChartVisualizations.php',{imagebase: gradGender2014.toBase64Image(), name: 'student-diversity-gender-grad-2014-" . $this->college . "', functionNum: '5'});
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
                data: [$gradMale20152016,$gradFemale20152016],
                backgroundColor: [
                  blue,
                  red
                ],
                borderColor: [
                  blue,
                  red
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
                  $.post('ChartVisualizations.php',{imagebase: gradGender2015.toBase64Image(), name: 'student-diversity-gender-grad-2015-" . $this->college . "', functionNum: '5'});
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
                data: [$gradMale20162017,$gradFemale20162017],
                backgroundColor: [
                  blue,
                  red
                ],
                borderColor: [
                  blue,
                  red
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
                  $.post('ChartVisualizations.php',{imagebase: gradGender2016.toBase64Image(), name: 'student-diversity-gender-grad-2016-" . $this->college . "', functionNum: '5'});
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
                backgroundColor: red,
                label: 'American Indian/Alaska Native'
              },{
                data: [$gradAsian20142015,$gradAsian20152016,$gradAsian20162017],
                backgroundColor: green,
                label: 'Asian'
              },{
                data: [$gradBlack20142015,$gradBlack20152016,$gradBlack20162017],
                backgroundColor: purple,
                label: 'Black or African American'
              },{
                data: [$gradHispanic20142015,$gradHispanic20152016,$gradHispanic20162017],
                backgroundColor: 'rgba(33, 120, 108, 0.5)',
                label: 'Hispanic or Latino'
              },{
                data: [$gradHawaiiPacificIsland20142015,$gradHawaiiPacificIsland20152016,$gradHawaiiPacificIsland20162017],
                backgroundColor: pink,
                label: 'Native Hawaiian or Other Pacific Islander'
              },{
                data: [$gradAliens20142015,$gradAliens20152016,$gradAliens20162017],
                backgroundColor: blue,
                label: 'Nonresident Alien'
              },{
                data: [$gradTwoOrMore20142015,$gradTwoOrMore20152016,$gradTwoOrMore20162017],
                backgroundColor: 'rgba(185, 50, 63, 0.5)',
                label: 'Two Or More Races'
              },{
                data: [$gradUnkown20142015,$gradUnkown20152016,$gradUnkown20162017],
                backgroundColor: blue,
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
                $.post('ChartVisualizations.php',{imagebase: gradDiversityRace.toBase64Image(), name: 'student-diversity-race-under-" . $this->college . "', functionNum: '5'});
              }
            },
            scaleLabel:{
                display:false
            },
            scales: {
              xAxes: [{
                ticks: {
                    beginAtZero:true
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

    public function chartDiversityStudentByYear($selectedYear, $ouAbbrev)
    {

        $getDiversityData = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityStudent` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getDiversityData->bindParam(1, $selectedYear, PDO::PARAM_STR);
        $getDiversityData->bindParam(2, $ouAbbrev, PDO::PARAM_STR);
        $getDiversityData->execute();
        $rowsGetDiversityData = $getDiversityData->rowCount();

        if ($rowsGetDiversityData > 0) {

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
          <h2 class='text-center'>" . $this->college . " Undergraduate Gender Data</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
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
          <h2 class='text-center'>" . $this->college . " Data Undergraduate Race</h2>
          <div class='row'>
            <div class='col-md-6'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>American Indian/Alaskan Native</td>
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
                    <tr>
                      <td>Native Hawaiian or Other Pacific Islander</td>
                      <td>$underGradHawaiiPacificIsland</td>
                    </tr>
                    <tr>
                      <td>Nonresident Alien</td>
                      <td>$underGradAlien</td>
                    </tr>
                    <tr>
                      <td>Two Or More Races</td>
                      <td>$underGradTwoOrMore</td>
                    </tr>
                    <tr>
                      <td>Unkown Race</td>
                      <td>$underGradUnkown</td>
                    </tr>
                    <tr>
                      <td>White</td>
                      <td>$underGradWhite</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <canvas id='underDiversityRaceByYear' width='800' height='500'></canvas>
          </div>
          <h2 class='text-center'>" . $this->college . " Graduate Gender Data</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
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
          <h2 class='text-center'>" . $this->college . " Data Graduate Race</h2>
          <div class='row'>
            <div class='col-md-6'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>American Indian/Alaskan Native</td>
                      <td>$gradAlaskaNative</td>
                    </tr>
                    <tr>
                      <td>Asian</td>
                      <td>$gradAsian</td>
                    </tr>
                    <tr>
                      <td>Black</td>
                      <td>$gradBlack</td>
                    </tr>
                    <tr>
                      <td>Hispanic</td>
                      <td>$gradHispanic</td>
                    </tr>
                    <tr>
                      <td>Native Hawaiian or Other Pacific Islander</td>
                      <td>$gradHawaiiPacificIsland</td>
                    </tr>
                    <tr>
                      <td>Nonresident Alien</td>
                      <td>$gradAlien</td>
                    </tr>
                    <tr>
                      <td>Two Or More Races</td>
                      <td>$gradTwoOrMore</td>
                    </tr>
                    <tr>
                      <td>Unknown Race</td>
                      <td>$gradUnkown</td>
                    </tr>
                    <tr>
                      <td>White</td>
                      <td>$gradWhite</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <canvas id='gradDiversityRaceByYear' width='800' height='500'></canvas>
        </div>
        <script>
          var ctx = document.getElementById('underDiversityGenderByYear');
          var underDiversityGenderByYear = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$underGradMale,$underGradFemale],
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
                data: [$gradMale,$gradFemale],
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
                display: true
              }
            }
          });

          var ctx = document.getElementById('gradDiversityRaceByYear');
          var gradDiversityRaceByYear = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['American Indian/Alaskian Native', 'Asian','Undergrad Black','Hispanic','Native Hawaiian or Other Pacific Islander','Alien','Two Or More Races','Unknown Race','White'],
              datasets: [{
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
        $getDiversityData20162017->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getDiversityData20162017->bindParam(2, $this->college, PDO::PARAM_STR);
        $getDiversityData20162017->execute();
        $rowsGetDiversityData20162017 = $getDiversityData20162017->rowCount();

        if ($rowsGetDiversityData20162017 > 0) {

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
        $getDiversityData20152016->bindParam(1, $ayYearBackOne, PDO::PARAM_STR);
        $getDiversityData20152016->bindParam(2, $this->college, PDO::PARAM_STR);
        $getDiversityData20152016->execute();
        $rowsGetDiversityData20152016 = $getDiversityData20152016->rowCount();

        if ($rowsGetDiversityData20152016 > 0) {

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
        $getDiversityData20142015->bindParam(1, $ayYearBackTwo, PDO::PARAM_STR);
        $getDiversityData20142015->bindParam(2, $this->college, PDO::PARAM_STR);
        $getDiversityData20142015->execute();
        $rowsGetDiversityData20142015 = $getDiversityData20142015->rowCount();

        if ($rowsGetDiversityData20142015 > 0) {

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
          <h2 class='text-center'>" . $this->college . " Faculty Gender Data</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Female</td>
                      <td>$female20162017</td>
                      <td>$female20152016</td>
                      <td>$female20142015</td>
                    </tr>
                    <tr>
                      <td>Male</td>
                      <td>$male20162017</td>
                      <td>$male20152016</td>
                      <td>$male20142015</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
              <table>
                <tr>
                  <td>
                    <h5 class='text-center' style='padding-bottom:20px;'>2016</h5>
                    <canvas id='facultyDiversityGender2016' width='200' height='188'></canvas>
                  </td>
                  <td>
                    <h5 class='text-center'>2015</h5>
                    <canvas id='facultyDiversityGender2015' width='200' height='220'></canvas>
                  </td>
                  <td>
                    <h5 class='text-center' style='padding-bottom:20px;'>2014</h5>
                    <canvas id='facultyDiversityGender2014' width='200' height='188'></canvas>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <h2 class='text-center'>" . $this->college . " Faculty Race Data</h2>
          <div class='row'>
            <div class='col-md-6'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>American Indian/Alaskan Native</td>
                      <td>$alaskaNative20162017</td>
                      <td>$alaskaNative20152016</td>
                      <td>$alaskaNative20142015</td>
                    </tr>
                    <tr>
                      <td>Asian</td>
                      <td>$asian20162017</td>
                      <td>$asian20152016</td>
                      <td>$asian20142015</td>
                    </tr>
                    <tr>
                      <td>Black</td>
                      <td>$black20162017</td>
                      <td>$black20152016</td>
                      <td>$black20142015</td>
                    </tr>
                    <tr>
                      <td>Hispanic</td>
                      <td>$hispanic20162017</td>
                      <td>$hispanic20152016</td>
                      <td>$hispanic20142015</td>
                    </tr>
                    <tr>
                      <td>Native Hawaiian or Other Pacific Islander</td>
                      <td>$hawaiiPacificIsland20162017</td>
                      <td>$hawaiiPacificIsland20152016</td>
                      <td>$hawaiiPacificIsland20142015</td>
                    </tr>
                    <tr>
                      <td>Nonresident Alien</td>
                      <td>$alien20162017</td>
                      <td>$alien20152016</td>
                      <td>$alien20142015</td>
                    </tr>
                    <tr>
                      <td>Two Or More Races</td>
                      <td>$twoOrMore20162017</td>
                      <td>$twoOrMore20152016</td>
                      <td>$twoOrMore20142015</td>
                    </tr>
                    <tr>
                      <td>Unkown Race</td>
                      <td>$unkown20162017</td>
                      <td>$unkown20152016</td>
                      <td>$unkown20142015</td>
                    </tr>
                    <tr>
                      <td>White</td>
                      <td>$white20162017</td>
                      <td>$white20152016</td>
                      <td>$white20142015</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-6'>
              <canvas id='facultyDiversityRace' height='220' width='300'></canvas>
            </div>
          </div>
        </div>
        <script>
          var red = 'rgba(189, 59, 71, 0.8)';
          var blue = 'rgba(87, 147, 243, 0.8)';
          var purple = 'rgba(114, 92, 164, 0.8)';
          var pink = 'rgba(221, 77, 121, 0.8)';
          var green = 'rgba(212, 223, 90, 0.8)';
          var ctx = document.getElementById('facultyDiversityGender2014');
          var facultyDiversityGender2014 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$male20142015,$female20142015],
                backgroundColor: [
                  blue,
                  red
                ],
                borderColor: [
                  blue,
                  red
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
                  $.post('ChartVisualizations.php',{imagebase: facultyDiversityGender2014.toBase64Image(), name: 'faculty-diversity-gender-2014-" . $this->college . "', functionNum: '5'});
                }
              }
            }
          });

          var ctx = document.getElementById('facultyDiversityGender2015');
          var facultyDiversityGender2015 = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                label: 'Gender',
                data: [$male20152016,$female20152016],
                backgroundColor: [
                  blue,
                  red
                ],
                borderColor: [
                  blue,
                  red
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
                  $.post('ChartVisualizations.php',{imagebase: facultyDiversityGender2015.toBase64Image(), name: 'faculty-diversity-gender-2015-" . $this->college . "', functionNum: '5'});
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
                data: [$male20162017,$female20162017],
                backgroundColor: [
                  blue,
                  red
                ],
                borderColor: [
                  blue,
                  red
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
                  $.post('ChartVisualizations.php',{imagebase: facultyDiversityGender2016.toBase64Image(), name: 'faculty-diversity-gender-2016-" . $this->college . "', functionNum: '5'});
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
                backgroundColor: red,
                label: 'American Indian/Alaska Native'
              },{
                data: [$asian20142015,$asian20152016,$asian20162017],
                backgroundColor: blue,
                label: 'Asian'
              },{
                data: [$black20142015,$black20152016,$black20162017],
                backgroundColor: green,
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
                backgroundColor: purple,
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
                backgroundColor: pink,
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
                $.post('ChartVisualizations.php',{imagebase: facultyDiversityRace.toBase64Image(), name: 'faculty-diversity-race-" . $this->college . "', functionNum: '5'});
              }
            },
            scaleLabel:{
                display:false
            },
            scales: {
              xAxes: [{
                ticks: {
                    beginAtZero:true
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

    public function chartDiversityFacultyByYear($selectedYear, $ouAbbrev)
    {

        $getDiversityData = $this->connection->prepare("SELECT * FROM `IR_AC_DiversityPersonnel` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getDiversityData->bindParam(1, $selectedYear, PDO::PARAM_STR);
        $getDiversityData->bindParam(2, $ouAbbrev, PDO::PARAM_STR);
        $getDiversityData->execute();
        $rowsGetDiversityData = $getDiversityData->rowCount();

        if ($rowsGetDiversityData > 0) {

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
          <h2 class='text-center'>" . $this->college . " Faculty Gender Data</h2>
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
          <h2 class='text-center'>" . $this->college . " Faculty Race Data</h2>
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
          </div>
          <canvas id='facultyDiversityRaceByYear' width='800' height='500'></canvas>
        </div>
        <script>
          var ctx = document.getElementById('facultyDiversityGenderByYear');
          var facultyDiversityGenderByYear = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Male', 'Female'],
              datasets: [{
                data: [$male,$female],
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
                display: true
              }
            }
          });

          var ctx = document.getElementById('facultyDiversityRaceByYear');
          var facultyDiversityRaceByYear = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['American Native', 'Asian','Undergrad Black','Hispanic','Native Hawaiian or Other Pacific Islander','Alien','Two Or More Races','Unknown Race','White'],
              datasets: [{
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
        $getStudentOutcomesRetention2015->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getStudentOutcomesRetention2015->bindParam(2, $this->college, PDO::PARAM_STR);
        $getStudentOutcomesRetention2015->execute();
        $rowsGetStudentOutcomesRetention2015 = $getStudentOutcomesRetention2015->rowCount();

        if ($rowsGetStudentOutcomesRetention2015) {

            while ($data = $getStudentOutcomesRetention2015->fetch()) {

                $retentionFirstYear2015 = $data["RETENTION_FIRST_YR"];
                $retentionSecondYear2015 = $data["RETENTION_SECOND_YR"];

            }

        }

        $currentYear = "2014";

        $getStudentOutcomesRetention2014 = $this->connection->prepare("SELECT * FROM `IR_AC_Retention` WHERE FTFT_COHORT = ? AND OU_ABBREV = ?");
        $getStudentOutcomesRetention2014->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getStudentOutcomesRetention2014->bindParam(2, $this->college, PDO::PARAM_STR);
        $getStudentOutcomesRetention2014->execute();
        $rowsGetStudentOutcomesRetention2014 = $getStudentOutcomesRetention2014->rowCount();

        if ($rowsGetStudentOutcomesRetention2014) {

            while ($data = $getStudentOutcomesRetention2014->fetch()) {

                $retentionFirstYear2014 = $data["RETENTION_FIRST_YR"];
                $retentionSecondYear2014 = $data["RETENTION_SECOND_YR"];

            }

        }

        $currentYear = "2013";

        $getStudentOutcomesRetention2013 = $this->connection->prepare("SELECT * FROM `IR_AC_Retention` WHERE FTFT_COHORT = ? AND OU_ABBREV = ?");
        $getStudentOutcomesRetention2013->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getStudentOutcomesRetention2013->bindParam(2, $this->college, PDO::PARAM_STR);
        $getStudentOutcomesRetention2013->execute();
        $rowsGetStudentOutcomesRetention2013 = $getStudentOutcomesRetention2013->rowCount();

        if ($rowsGetStudentOutcomesRetention2013) {

            while ($data = $getStudentOutcomesRetention2013->fetch()) {

                $retentionFirstYear2013 = $data["RETENTION_FIRST_YR"];
                $retentionSecondYear2013 = $data["RETENTION_SECOND_YR"];

            }

        }

        $currentYear = "2012";

        $getStudentOutcomesRetention2012 = $this->connection->prepare("SELECT * FROM `IR_AC_Retention` WHERE FTFT_COHORT = ? AND OU_ABBREV = ?");
        $getStudentOutcomesRetention2012->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getStudentOutcomesRetention2012->bindParam(2, $this->college, PDO::PARAM_STR);
        $getStudentOutcomesRetention2012->execute();
        $rowsGetStudentOutcomesRetention2012 = $getStudentOutcomesRetention2012->rowCount();

        if ($rowsGetStudentOutcomesRetention2012) {

            while ($data = $getStudentOutcomesRetention2012->fetch()) {

                $retentionFirstYear2012 = $data["RETENTION_FIRST_YR"];
                $retentionSecondYear2012 = $data["RETENTION_SECOND_YR"];

            }

        }

        $currentYear = "2010";

        $getStudentOutcomesGradRate2010 = $this->connection->prepare("SELECT * FROM `IR_AC_GraduationRate` WHERE FTFT_COHORT = ? AND OU_ABBREV = ?");
        $getStudentOutcomesGradRate2010->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getStudentOutcomesGradRate2010->bindParam(2, $this->college, PDO::PARAM_STR);
        $getStudentOutcomesGradRate2010->execute();
        $rowsGetStudentOutcomesGradRate2010 = $getStudentOutcomesGradRate2010->rowCount();

        if ($rowsGetStudentOutcomesGradRate2010) {

            while ($data = $getStudentOutcomesGradRate2010->fetch()) {

                $graduationRateFourYear2010 = $data["GRADUATION_RATE_4YR"];
                $graduationRateFiveYear2010 = $data["GRADUATION_RATE_5YR"];
                $graduationRateSixYear2010 = $data["GRADUATION_RATE_6YR"];

            }

        }

        $currentYear = "2009";

        $getStudentOutcomesGradRate2009 = $this->connection->prepare("SELECT * FROM `IR_AC_GraduationRate` WHERE FTFT_COHORT = ? AND OU_ABBREV = ?");
        $getStudentOutcomesGradRate2009->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getStudentOutcomesGradRate2009->bindParam(2, $this->college, PDO::PARAM_STR);
        $getStudentOutcomesGradRate2009->execute();
        $rowsGetStudentOutcomesGradRate2009 = $getStudentOutcomesGradRate2009->rowCount();

        if ($rowsGetStudentOutcomesGradRate2009) {

            while ($data = $getStudentOutcomesGradRate2009->fetch()) {

                $graduationRateFourYear2009 = $data["GRADUATION_RATE_4YR"];
                $graduationRateFiveYear2009 = $data["GRADUATION_RATE_5YR"];
                $graduationRateSixYear2009 = $data["GRADUATION_RATE_6YR"];

            }

        }

        $currentYear = "2008";

        $getStudentOutcomesGradRate2008 = $this->connection->prepare("SELECT * FROM `IR_AC_GraduationRate` WHERE FTFT_COHORT = ? AND OU_ABBREV = ?");
        $getStudentOutcomesGradRate2008->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getStudentOutcomesGradRate2008->bindParam(2, $this->college, PDO::PARAM_STR);
        $getStudentOutcomesGradRate2008->execute();
        $rowsGetStudentOutcomesGradRate2008 = $getStudentOutcomesGradRate2008->rowCount();

        if ($rowsGetStudentOutcomesGradRate2008) {

            while ($data = $getStudentOutcomesGradRate2008->fetch()) {

                $graduationRateFourYear2008 = $data["GRADUATION_RATE_4YR"];
                $graduationRateFiveYear2008 = $data["GRADUATION_RATE_5YR"];
                $graduationRateSixYear2008 = $data["GRADUATION_RATE_6YR"];

            }

        }

        $currentYear = "AY2015-2016";

        $getDegreesAwarded20152016 = $this->connection->prepare("SELECT * FROM `IR_AC_DegreesAwarded` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getDegreesAwarded20152016->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getDegreesAwarded20152016->bindParam(2, $this->college, PDO::PARAM_STR);
        $getDegreesAwarded20152016->execute();
        $rowsGetDegreesAwarded20152016 = $getDegreesAwarded20152016->rowCount();

        if ($rowsGetDegreesAwarded20152016) {

            while ($data = $getDegreesAwarded20152016->fetch()) {

                $degreesAwardedBachelorsYear20152016 = $data["DEGREES_AWRD_BACHELORS"];
                $degreesAwardedMastersYear20152016 = $data["DEGREES_AWRD_MASTERS"];
                $degreesAwardedDoctoralYear20152016 = $data["DEGREES_AWRD_DOCTORAL"];
                $degreesAwardedCertYear20152016 = $data["DEGREES_AWRD_GRAD_CERT"];

            }

        }

        $currentYear = "AY2014-2015";

        $getDegreesAwarded20142015 = $this->connection->prepare("SELECT * FROM `IR_AC_DegreesAwarded` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getDegreesAwarded20142015->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getDegreesAwarded20142015->bindParam(2, $this->college, PDO::PARAM_STR);
        $getDegreesAwarded20142015->execute();
        $rowsGetDegreesAwarded20142015 = $getDegreesAwarded20142015->rowCount();

        if ($rowsGetDegreesAwarded20142015) {

            while ($data = $getDegreesAwarded20142015->fetch()) {

                $degreesAwardedBachelorsYear20142015 = $data["DEGREES_AWRD_BACHELORS"];
                $degreesAwardedMastersYear20142015 = $data["DEGREES_AWRD_MASTERS"];
                $degreesAwardedDoctoralYear20142015 = $data["DEGREES_AWRD_DOCTORAL"];
                $degreesAwardedCertYear20142015 = $data["DEGREES_AWRD_GRAD_CERT"];

            }

        }

        $currentYear = "AY2013-2014";

        $getDegreesAwarded20132014 = $this->connection->prepare("SELECT * FROM `IR_AC_DegreesAwarded` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getDegreesAwarded20132014->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getDegreesAwarded20132014->bindParam(2, $this->college, PDO::PARAM_STR);
        $getDegreesAwarded20132014->execute();
        $rowsGetDegreesAwarded20132014 = $getDegreesAwarded20132014->rowCount();

        if ($rowsGetDegreesAwarded20132014) {

            while ($data = $getDegreesAwarded20132014->fetch()) {

                $degreesAwardedBachelorsYear20132014 = $data["DEGREES_AWRD_BACHELORS"];
                $degreesAwardedMastersYear20132014 = $data["DEGREES_AWRD_MASTERS"];
                $degreesAwardedDoctoralYear20132014 = $data["DEGREES_AWRD_DOCTORAL"];
                $degreesAwardedCertYear20132014 = $data["DEGREES_AWRD_GRAD_CERT"];

            }

        }

        echo "
        <div class='container-fluid'>
          <h2 class='text-center'>" . $this->college . " Student Retention Rates Data</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2015</th>
                      <th>2014</th>
                      <th>2013</th>
                      <th>2012</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>First Year</td>
                      <td>$retentionFirstYear2015</td>
                      <td>$retentionFirstYear2014</td>
                      <td>$retentionFirstYear2013</td>
                      <td>$retentionFirstYear2012</td>
                    </tr>
                    <tr>
                      <td>Second Year</td>
                      <td>N/A</td>
                      <td>$retentionSecondYear2014</td>
                      <td>$retentionSecondYear2013</td>
                      <td>$retentionSecondYear2012</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
              <table>
                <tr>
                  <td>
                    <h5 class='text-center'>First Year</h5>
                    <canvas id='retentionRatesFirstYear' height='200'></canvas>
                  </td>
                  <td>
                    <h5 class='text-center'>Second Year</h5>
                    <canvas id='retentionRatesSecondYear' height='200'></canvas>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <h2 class='text-center'>" . $this->college . " Student Graduation Rates Data</h2>
          <div class='row'>
            <div class='col-md-4'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2010</th>
                      <th>2009</th>
                      <th>2008</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>4 Year</td>
                      <td>$graduationRateFourYear2008</td>
                      <td>$graduationRateFourYear2009</td>
                      <td>$graduationRateFourYear2010</td>
                    </tr>
                    <tr>
                      <td>5 Year</td>
                      <td>$graduationRateFiveYear2008</td>
                      <td>$graduationRateFiveYear2009</td>
                      <td>$graduationRateFiveYear2010</td>
                    </tr>
                    <tr>
                      <td>6 Year</td>
                      <td>$graduationRateSixYear2008</td>
                      <td>$graduationRateSixYear2009</td>
                      <td>$graduationRateSixYear2010</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-8'>
              <table>
                <tr>
                  <td>
                    <h5 class='text-center'>2008</h5>
                    <canvas id='graduationRate2008' height='300'></canvas>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <h2 class='text-center'>" . $this->college . " Student Degrees Awarded Data</h2>
          <div class='row'>
            <div class='col-md-6'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2015-2016</th>
                      <th>2014-2015</th>
                      <th>2013-2014</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Bachelors</td>
                      <td>$degreesAwardedBachelorsYear20132014</td>
                      <td>$degreesAwardedBachelorsYear20142015</td>
                      <td>$degreesAwardedBachelorsYear20152016</td>
                    </tr>
                    <tr>
                      <td>Masters</td>
                      <td>$degreesAwardedMastersYear20132014</td>
                      <td>$degreesAwardedMastersYear20142015</td>
                      <td>$degreesAwardedMastersYear20152016</td>
                    </tr>
                    <tr>
                      <td>Doctoral</td>
                      <td>$degreesAwardedDoctoralYear20132014</td>
                      <td>$degreesAwardedDoctoralYear20142015</td>
                      <td>$degreesAwardedDoctoralYear20152016</td>
                    </tr>
                    <tr>
                      <td>Graduate Certificate</td>
                      <td>$degreesAwardedCertYear20132014</td>
                      <td>$degreesAwardedCertYear20142015</td>
                      <td>$degreesAwardedCertYear20152016</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class='col-md-6'>
              <canvas id='degreesAwarded' height='200'></canvas>
            </div>
          </div>
        </div>
        <script>
          var red = 'rgba(189, 59, 71, 0.8)';
          var blue = 'rgba(87, 147, 243, 0.8)';
          var purple = 'rgba(114, 92, 164, 0.8)';
          var pink = 'rgba(221, 77, 121, 0.8)';
          var green = 'rgba(212, 223, 90, 0.8)';
          var ctx = document.getElementById('retentionRatesFirstYear');
          var retentionRatesFirstYear = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['2012','2013','2014','2015'],
              datasets: [{
                label: 'First Year',
                data: [$retentionFirstYear2012,$retentionFirstYear2013,$retentionFirstYear2014,$retentionFirstYear2015],
                backgroundColor: [
                  red,
                  blue,
                  green,
                  pink,
                  purple
                ]
              }]
            },
            options: {
              responsive: false,
              legend: {
                display: false
              },animation: {
                onComplete: function(){
                  $.post('ChartVisualizations.php',{imagebase: retentionRatesFirstYear.toBase64Image(), name: 'student-retention-first-under-" . $this->college . "', functionNum: '5'});
                }
              },
              scales: {
                        yAxes: [
                            {
                                ticks: {
                                    min: 0.4,
                                    stepSize: 0.05,
                                    max: 1
                                }
                            }
                        ]
                    }
            }
          });

          var ctx = document.getElementById('retentionRatesSecondYear');
          var retentionRatesSecondYear = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['2012','2013','2014'],
              datasets: [{
                label: 'Second Year',
                data: [$retentionSecondYear2012,$retentionSecondYear2013,$retentionSecondYear2014],
                backgroundColor: [
                  red,
                  blue,
                  green,
                  pink,
                  purple
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
                  $.post('ChartVisualizations.php',{imagebase: retentionRatesSecondYear.toBase64Image(), name: 'student-retention-second-under-" . $this->college . "', functionNum: '5'});
                }
              },
              scales: {
                yAxes: [
                    {
                        ticks: {
                            min: 0.4,
                            stepSize: 0.05,
                            max: 1
                        }
                    }
                ]
            }
            }
          });

          var ctx = document.getElementById('graduationRate2008');
          var graduationRate2008 = new Chart(ctx, {
            type: 'line',
            data: {
              labels: ['2010','2009','2008'],
              datasets: [
              {
                label: '4 Year',
                data: [$graduationRateFourYear2010,$graduationRateFourYear2009,$graduationRateFourYear2008],
                backgroundColor: [
                  red,
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)'
                ]
              },
              {
                label: '5 Year',
                data: [$graduationRateFiveYear2010,$graduationRateFiveYear2009,$graduationRateFiveYear2008],
                backgroundColor: [
                  blue,
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)'
                ]
              },
              {
                label: '6 Year',
                data: [$graduationRateSixYear2010,$graduationRateSixYear2009,$graduationRateSixYear2008],
                backgroundColor: [
                  purple,
                  'rgba(43, 0, 4, 0.5)',
                  'rgba(33, 120, 108, 0.5)'
                ]
              }

            ]
            },
            options: {
              responsive: false,
              legend: {
                display: true
              }
            }
          });

          // var ctx = document.getElementById('graduationRate2010');
          // var graduationRate2010 = new Chart(ctx, {
          //   type: 'bar',
          //   data: {
          //     labels: ['4 Year','5 Year','6 Year'],
          //     datasets: [{
          //       data: [$graduationRateFourYear2010,$graduationRateFiveYear2010,$graduationRateSixYear2010],
          //       backgroundColor: [
          //         'rgba(116, 0, 11, 0.5)',
          //         'rgba(43, 0, 4, 0.5)',
          //         'rgba(33, 120, 108, 0.5)',
          //       ]
          //     }]
          //   },
          //   options: {
          //     responsive: false,
          //     legend: {
          //       display: false
          //     }
          //   }
          // });

          // var ctx = document.getElementById('graduationRate2009');
          // var graduationRate2009 = new Chart(ctx, {
          //   type: 'bar',
          //   data: {
          //     labels: ['4 Year','5 Year','6 Year'],
          //     datasets: [{
          //       label: 'Second Year',
          //       data: [$graduationRateFourYear2009,$graduationRateFiveYear2009,$graduationRateSixYear2009],
          //       backgroundColor: [
          //         'rgba(116, 0, 11, 0.5)',
          //         'rgba(43, 0, 4, 0.5)',
          //         'rgba(33, 120, 108, 0.5)'
          //       ]
          //     }]
          //   },
          //   options: {
          //     responsive: false,
          //     legend: {
          //       display: false
          //     }
          //   }
          // });

          // var ctx = document.getElementById('graduationRate2008');
          // var graduationRate2008 = new Chart(ctx, {
          //   type: 'bar',
          //   data: {
          //     labels: ['4 Year','5 Year','6 Year'],
          //     datasets: [{
          //       label: 'Second Year',
          //       data: [$graduationRateFourYear2008,$graduationRateFiveYear2008,$graduationRateSixYear2008],
          //       backgroundColor: [
          //         'rgba(116, 0, 11, 0.5)',
          //         'rgba(43, 0, 4, 0.5)',
          //         'rgba(33, 120, 108, 0.5)'
          //       ]
          //     }]
          //   },
          //   options: {
          //     responsive: false,
          //     legend: {
          //       display: false
          //     }
          //   }
          // });

          var ctx = document.getElementById('degreesAwarded');
          var degreesAwarded = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
              labels: ['2015-2016', '2014-2015', '2013-2014'],
              datasets: [{
                data: [$degreesAwardedBachelorsYear20152016,$degreesAwardedBachelorsYear20142015,$degreesAwardedBachelorsYear20132014],
                backgroundColor: red,
                label: 'Bachelors'
              },{
                data: [$degreesAwardedMastersYear20152016,$degreesAwardedMastersYear20142015,$degreesAwardedMastersYear20132014],
                backgroundColor: blue,
                label: 'Masters'
              },{
                data: [$degreesAwardedDoctoralYear20152016,$degreesAwardedDoctoralYear20142015,$degreesAwardedDoctoralYear20132014],
                backgroundColor: green,
                label: 'Doctoral'
              },{
                data: [$degreesAwardedCertYear20152016,$degreesAwardedCertYear20142015,$degreesAwardedCertYear20132014],
                backgroundColor: purple,
                label: 'Graduate Certification'
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
                $.post('ChartVisualizations.php',{imagebase: degreesAwarded.toBase64Image(), name: 'student-degreesAwarded-" . $this->college . "', functionNum: '5'});
              }
            },
            scaleLabel:{
                display:false
            },
            scales: {
              xAxes: [{
                ticks: {
                    beginAtZero:true
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

    public function tableFacultyStudentRatio()
    {

        $currentYear = "AY2016-2017";

        $getRatio20162017 = $this->connection->prepare("SELECT * FROM `IR_AC_FacultyStudentRatio` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getRatio20162017->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getRatio20162017->bindParam(2, $this->college, PDO::PARAM_STR);
        $getRatio20162017->execute();
        $rowsGetRatio20162017 = $getRatio20162017->rowCount();

        if ($rowsGetRatio20162017 > 0) {

            $data = $getRatio20162017->fetch();

            $ratio20162017 = $data["RATIO_FAC_STUDENT"];


        }

        $ayYearBackOne = "AY2015-2016";

        $getRatio20152016 = $this->connection->prepare("SELECT * FROM `IR_AC_FacultyStudentRatio` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getRatio20152016->bindParam(1, $ayYearBackOne, PDO::PARAM_STR);
        $getRatio20152016->bindParam(2, $this->college, PDO::PARAM_STR);
        $getRatio20152016->execute();
        $rowsGetRatio20152016 = $getRatio20152016->rowCount();

        if ($rowsGetRatio20152016 > 0) {

            $data = $getRatio20152016->fetch();

            $ratio20152016 = $data["RATIO_FAC_STUDENT"];


        }

        $ayYearBackTwo = "AY2014-2015";

        $getRatio20142015 = $this->connection->prepare("SELECT * FROM `IR_AC_FacultyStudentRatio` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getRatio20142015->bindParam(1, $ayYearBackTwo, PDO::PARAM_STR);
        $getRatio20142015->bindParam(2, $this->college, PDO::PARAM_STR);
        $getRatio20142015->execute();
        $rowsGetRatio20142015 = $getRatio20142015->rowCount();

        if ($rowsGetRatio20142015 > 0) {

            $data = $getRatio20142015->fetch();

            $ratio20142015 = $data["RATIO_FAC_STUDENT"];


        }

        echo "
        <div class='container-fluid'>
          <h2 class='text-center'>" . $this->college . " Faculty-to-Student Ratio</h2>
          <div class='row'>
            <div class='col-md-8'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Ratio</td>
                      <td>$ratio20162017</td>
                      <td>$ratio20152016</td>
                      <td>$ratio20142015</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      ";

    }

    public function tableFacultyPop()
    {

        $currentYear = "AY2016-2017";

        $getFacPop20162017 = $this->connection->prepare("SELECT * FROM `IR_AC_FacultyPop` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getFacPop20162017->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getFacPop20162017->bindParam(2, $this->college, PDO::PARAM_STR);
        $getFacPop20162017->execute();
        $rowsGetFacPop20162017 = $getFacPop20162017->rowCount();

        if ($rowsGetFacPop20162017 > 0) {

            $data = $getFacPop20162017->fetch();

            $profTnr20162017 = $data["TTF_FTE_PROF_TNR"];
            $assocProfTnr20162017 = $data["TTF_FTE_ASSOC_PROF_TNR"];
            $prof20162017 = $data["TTF_FTE_PROF"];
            $assocProf20162017 = $data["TTF_FTE_ASSOC_PROF"];
            $asstProf20162017 = $data["TTF_FTE_ASSIST_PROF"];
            $librTnr20162017 = $data["TTF_FTE_LIBR_TNR"];
            $libr20162017 = $data["TTF_FTE_PROF"];
            $asstLibr20162017 = $data["TTF_FTE_ASSIST_LIBR"];

            $rsrchProf20162017 = $data["RSRCH_FTE_PROF"];
            $assocRsrchProf20162017 = $data["RSRCH_FTE_ASSOC_PROF"];
            $asstRsrchProf20162017 = $data["RSRCH_FTE_ASSIST_PROF"];

            $clnclProf20162017 = $data["CIF_FTE_CLNCL_PROF"];
            $assocClnclProf20162017 = $data["CIF_FTE_CLNCL_ASSOC_PROF"];
            $asstClnclProf20162017 = $data["CIF_FTE_CLNCL_ASSIST_PROF"];
            $instructor20162017 = $data["CIF_FTE_INSTR_LCTR"];

            $other20162017 = $data["OTHRFAC_PT_ADJUNCT"];


        }

        $totalTnr20162017 = $profTnr20162017 + $assocProfTnr20162017 + $prof20162017 + $assocProf20162017 + $asstProf20162017 + $librTnr20162017 + $libr20162017 + $asstLibr20162017;

        $totalRsrch20162017 = $rsrchProf20162017 + $assocRsrchProf20162017 + $asstRsrchProf20162017;

        $totalClncl20162017 = $clnclProf20162017 + $assocClnclProf20162017 + $asstClnclProf20162017 + $instructor20162017;



        $ayYearBackOne = "AY2015-2016";

        $getFacPop20152016 = $this->connection->prepare("SELECT * FROM `IR_AC_FacultyPop` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getFacPop20152016->bindParam(1, $ayYearBackOne, PDO::PARAM_STR);
        $getFacPop20152016->bindParam(2, $this->college, PDO::PARAM_STR);
        $getFacPop20152016->execute();
        $rowsGetFacPop20152016 = $getFacPop20152016->rowCount();

        if ($rowsGetFacPop20152016 > 0) {

            $data = $getFacPop20152016->fetch();

            $profTnr20152016 = $data["TTF_FTE_PROF_TNR"];
            $assocProfTnr20152016 = $data["TTF_FTE_ASSOC_PROF_TNR"];
            $prof20152016 = $data["TTF_FTE_PROF"];
            $assocProf20152016 = $data["TTF_FTE_ASSOC_PROF"];
            $asstProf20152016 = $data["TTF_FTE_ASSIST_PROF"];
            $librTnr20152016 = $data["TTF_FTE_LIBR_TNR"];
            $libr20152016 = $data["TTF_FTE_PROF"];
            $asstLibr20152016 = $data["TTF_FTE_ASSIST_LIBR"];

            $rsrchProf20152016 = $data["RSRCH_FTE_PROF"];
            $assocRsrchProf20152016 = $data["RSRCH_FTE_ASSOC_PROF"];
            $asstRsrchProf20152016 = $data["RSRCH_FTE_ASSIST_PROF"];

            $clnclProf20152016 = $data["CIF_FTE_CLNCL_PROF"];
            $assocClnclProf20152016 = $data["CIF_FTE_CLNCL_ASSOC_PROF"];
            $asstClnclProf20152016 = $data["CIF_FTE_CLNCL_ASSIST_PROF"];
            $instructor20152016 = $data["CIF_FTE_INSTR_LCTR"];

            $other20152016 = $data["OTHRFAC_PT_ADJUNCT"];


        }

        $totalTnr20152016 = $profTnr20152016 + $assocProfTnr20152016 + $prof20152016 + $assocProf20152016 + $asstProf20152016 + $librTnr20152016 + $libr20152016 + $asstLibr20152016;

        $totalRsrch20152016 = $rsrchProf20152016 + $assocRsrchProf20152016 + $asstRsrchProf20152016;

        $totalClncl20152016 = $clnclProf20152016 + $assocClnclProf20152016 + $asstClnclProf20152016 + $instructor20152016;



        $ayYearBackTwo = "AY2014-2015";

        $getFacPop20142015 = $this->connection->prepare("SELECT * FROM `IR_AC_FacultyPop` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getFacPop20142015->bindParam(1, $ayYearBackOne, PDO::PARAM_STR);
        $getFacPop20142015->bindParam(2, $this->college, PDO::PARAM_STR);
        $getFacPop20142015->execute();
        $rowsGetFacPop20142015 = $getFacPop20142015->rowCount();

        if ($rowsGetFacPop20142015 > 0) {

            $data = $getFacPop20142015->fetch();

            $profTnr20142015 = $data["TTF_FTE_PROF_TNR"];
            $assocProfTnr20142015 = $data["TTF_FTE_ASSOC_PROF_TNR"];
            $prof20142015 = $data["TTF_FTE_PROF"];
            $assocProf20142015 = $data["TTF_FTE_ASSOC_PROF"];
            $asstProf20142015 = $data["TTF_FTE_ASSIST_PROF"];
            $librTnr20142015 = $data["TTF_FTE_LIBR_TNR"];
            $libr20142015 = $data["TTF_FTE_PROF"];
            $asstLibr20142015 = $data["TTF_FTE_ASSIST_LIBR"];

            $rsrchProf20142015 = $data["RSRCH_FTE_PROF"];
            $assocRsrchProf20142015 = $data["RSRCH_FTE_ASSOC_PROF"];
            $asstRsrchProf20142015 = $data["RSRCH_FTE_ASSIST_PROF"];

            $clnclProf20142015 = $data["CIF_FTE_CLNCL_PROF"];
            $assocClnclProf20142015 = $data["CIF_FTE_CLNCL_ASSOC_PROF"];
            $asstClnclProf20142015 = $data["CIF_FTE_CLNCL_ASSIST_PROF"];
            $instructor20142015 = $data["CIF_FTE_INSTR_LCTR"];

            $other20142015 = $data["OTHRFAC_PT_ADJUNCT"];


        }

        $totalTnr20142015 = $profTnr20142015 + $assocProfTnr20142015 + $prof20142015 + $assocProf20142015 + $asstProf20142015 + $librTnr20142015 + $libr20142015 + $asstLibr20142015;

        $totalRsrch20142015 = $rsrchProf20142015 + $assocRsrchProf20142015 + $asstRsrchProf20142015;

        $totalClncl20142015 = $clnclProf20142015 + $assocClnclProf20142015 + $asstClnclProf20142015 + $instructor20142015;

        echo "
        <div class='container-fluid'>
          <h2 class='text-center'>" . $this->college . " Faculty Population by Track and Title</h2>
          <div class='row'>
            <div class='col-md-8'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                  </thead>
                  <tbody>
                    <thead>
                      <tr>
                        <th>Tenure-track Faculty</th>
                        <th>$totalTnr20162017</th>
                        <th>$totalTnr20152016</th>
                        <th>$totalTnr20142015</th>
                      </tr>
                    </thead>
                    <tr>
                      <td>Professor, with Tenure</td>
                      <td>$profTnr20162017</td>
                      <td>$profTnr20152016</td>
                      <td>$profTnr20142015</td>
                    </tr>
                    <tr>
                      <td>Associate Professor, with Tenure</td>
                      <td>$assocProfTnr20162017</td>
                      <td>$assocProfTnr20152016</td>
                      <td>$assocProfTnr20142015</td>
                    </tr>
                    <tr>
                      <td>Professor</td>
                      <td>$prof20162017</td>
                      <td>$prof20152016</td>
                      <td>$prof20142015</td>
                    </tr>
                    <tr>
                      <td>Associate Professor</td>
                      <td>$assocProf20162017</td>
                      <td>$assocProf20152016</td>
                      <td>$assocProf20142015</td>
                    </tr>
                    <tr>
                      <td>Assistant Professor</td>
                      <td>$asstProf20162017</td>
                      <td>$asstProf20152016</td>
                      <td>$asstProf20142015</td>
                    </tr>
                    <tr>
                      <td>Librarian, with Tenure</td>
                      <td>$librTnr20162017</td>
                      <td>$librTnr20152016</td>
                      <td>$librTnr20142015</td>
                    </tr>
                    <tr>
                      <td>Librarian</td>
                      <td>$libr20162017</td>
                      <td>$libr20152016</td>
                      <td>$libr20142015</td>
                    </tr>
                    <tr>
                      <td>Assistant Librarian</td>
                      <td>$asstLibr20162017</td>
                      <td>$asstLibr20152016</td>
                      <td>$asstLibr20142015</td>
                    </tr>
                    <thead>
                      <tr>
                        <th>Research Faculty</th>
                        <th>$totalRsrch20162017</th>
                        <th>$totalRsrch20152016</th>
                        <th>$totalRsrch20142015</th>
                      </tr>
                    </thead>
                    <tr>
                      <td>Research Professor</td>
                      <td>$rsrchProf20162017</td>
                      <td>$rsrchProf20152016</td>
                      <td>$rsrchProf20142015</td>
                    </tr>
                    <tr>
                      <td>Research Associate Professor</td>
                      <td>$assocRsrchProf20162017</td>
                      <td>$assocRsrchProf20152016</td>
                      <td>$assocRsrchProf20142015</td>
                    </tr>
                    <tr>
                      <td>Research Assistant Professor</td>
                      <td>$asstRsrchProf20162017</td>
                      <td>$asstRsrchProf20152016</td>
                      <td>$asstRsrchProf20142015</td>
                    </tr>
                    <thead>
                      <tr>
                        <th>Clinical/intructional Faculty</th>
                        <th>$totalClncl20162017</th>
                        <th>$totalClncl20152016</th>
                        <th>$totalClncl20142015</th>
                      </tr>
                    </thead>
                    <tr>
                      <td>Clincial Professor</td>
                      <td>$clnclProf20162017</td>
                      <td>$clnclProf20152016</td>
                      <td>$clnclProf20142015</td>
                    </tr>
                    <tr>
                      <td>Clincial Associate Professor</td>
                      <td>$assocClnclProf20162017</td>
                      <td>$assocClnclProf20152016</td>
                      <td>$assocClnclProf20142015</td>
                    </tr>
                    <tr>
                      <td>Clinical Assistant Professor</td>
                      <td>$asstClnclProf20162017</td>
                      <td>$asstClnclProf20152016</td>
                      <td>$asstClnclProf20142015</td>
                    </tr>
                    <tr>
                      <td>Instructor/Lecturer Assistant Professor</td>
                      <td>$instructor20162017</td>
                      <td>$instructor20152016</td>
                      <td>$instructor20142015</td>
                    </tr>
                    <thead>
                      <tr>
                        <th>Adjunct Faculty</th>
                        <th>$other20162017</th>
                        <th>$other20152016</th>
                        <th>$other20142015</th>
                      </tr>
                    </thead>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      ";

    }

    public function tableFacultyActions()
    {

        $currentYear = "AY2015-2016";

        $getAction20162017 = $this->connection->prepare("SELECT * FROM `PROV_AC_FacultyAction` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getAction20162017->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getAction20162017->bindParam(2, $this->college, PDO::PARAM_STR);
        $getAction20162017->execute();
        $rowsGetAction20162017 = $getAction20162017->rowCount();

        if ($rowsGetAction20162017 > 0) {

            $data = $getAction20162017->fetch();

            $depart20162017 = $data["FAC_DEPART"];
            $hired20162017 = $data["FAC_HIRED"];
            $vac20162017 = $data["FAC_VACANCIES"];
            $package20162017 = $data["FAC_RETEN_PCKG"];



        }

        $ayYearBackOne = "AY2014-2015";

        $getAction20152016 = $this->connection->prepare("SELECT * FROM `PROV_AC_FacultyAction` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getAction20152016->bindParam(1, $ayYearBackOne, PDO::PARAM_STR);
        $getAction20152016->bindParam(2, $this->college, PDO::PARAM_STR);
        $getAction20152016->execute();
        $rowsGetAction20152016 = $getAction20152016->rowCount();

        if ($rowsGetAction20152016 > 0) {

            $data = $getAction20152016->fetch();

            $depart20152016 = $data["FAC_DEPART"];
            $hired20152016 = $data["FAC_HIRED"];
            $vac20152016 = $data["FAC_VACANCIES"];
            $package20152016 = $data["FAC_RETEN_PCKG"];


        }

        $ayYearBackTwo = "AY2013-2014";

        $getAction20142015 = $this->connection->prepare("SELECT * FROM `PROV_AC_FacultyAction` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getAction20142015->bindParam(1, $ayYearBackTwo, PDO::PARAM_STR);
        $getAction20142015->bindParam(2, $this->college, PDO::PARAM_STR);
        $getAction20142015->execute();
        $rowsGetAction20142015 = $getAction20142015->rowCount();

        if ($rowsGetAction20142015 > 0) {

            $data = $getAction20142015->fetch();

            $depart20142015 = $data["FAC_DEPART"];
            $hired20142015 = $data["FAC_HIRED"];
            $vac20142015 = $data["FAC_VACANCIES"];
            $package20142015 = $data["FAC_RETEN_PCKG"];


        }

        echo "
        <div class='container-fluid'>
          <h2 class='text-center'>" . $this->college . " Faculty Actions</h2>
          <div class='row'>
            <div class='col-md-8'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Departures</td>
                      <td>$depart20162017</td>
                      <td>$depart20152016</td>
                    </tr>
                    <tr>
                      <td>Hired</td>
                      <td>$hired20162017</td>
                      <td>$hired20152016</td>
                    </tr>
                    <tr>
                      <td>Vacancies</td>
                      <td>$vac20162017</td>
                      <td>$vac20152016</td>
                    </tr>
                    <tr>
                      <td>Retention Package</td>
                      <td>$package20162017</td>
                      <td>$package20152016</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      ";

    }

    public function tableEnrollementbyTime()
    {

        $currentYear = "AY2016-2017";

        $getEnrollTime20162017 = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getEnrollTime20162017->bindParam(1, $currentYear, PDO::PARAM_STR);
        $getEnrollTime20162017->bindParam(2, $this->college, PDO::PARAM_STR);
        $getEnrollTime20162017->execute();
        $rowsGetEnrollTime20162017 = $getEnrollTime20162017->rowCount();

        if ($rowsGetEnrollTime20162017 > 0) {

            $data = $getEnrollTime20162017->fetch();

            $uFull20162017 = $data["ENROLL_UGRAD_FULLTIME"];
            $uPart20162017 = $data["ENROLL_UGRAD_PARTTIME"];
            $gFull20162017 = $data["ENROLL_GRADPRFSNL_FULLTIME"];
            $gPart20162017 = $data["ENROLL_GRADPRFSNL_PARTTIME"];



        }

        $uTotal20162017 = $uFull20162017 + $uPart20162017;
        $gTotal20162017 = $gFull20162017 + $gPart20162017;
        $tFull20162017 = $uFull20162017 + $gFull20162017;
        $tPart20162017 = $uPart20162017 + $gPart20162017;
        $total20162017 = $tFull20162017 + $tPart20162017;


        $ayYearBackOne = "AY2015-2016";

        $getEnrollTime20152016 = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getEnrollTime20152016->bindParam(1, $ayYearBackOne, PDO::PARAM_STR);
        $getEnrollTime20152016->bindParam(2, $this->college, PDO::PARAM_STR);
        $getEnrollTime20152016->execute();
        $rowsGetEnrollTime20152016 = $getEnrollTime20152016->rowCount();

        if ($rowsGetEnrollTime20152016 > 0) {

            $data = $getEnrollTime20152016->fetch();

            $uFull20152016 = $data["ENROLL_UGRAD_FULLTIME"];
            $uPart20152016 = $data["ENROLL_UGRAD_PARTTIME"];
            $gFull20152016 = $data["ENROLL_GRADPRFSNL_FULLTIME"];
            $gPart20152016 = $data["ENROLL_GRADPRFSNL_PARTTIME"];


        }

        $uTotal20152016 = $uFull20152016 + $uPart20152016;
        $gTotal20152016 = $gFull20152016 + $gPart20152016;
        $tFull20152016 = $uFull20152016 + $gFull20152016;
        $tPart20152016 = $uPart20152016 + $gPart20152016;
        $total20152016 = $tFull20152016 + $tPart20152016;


        $ayYearBackTwo = "AY2014-2015";

        $getEnrollTime20142015 = $this->connection->prepare("SELECT * FROM `IR_AC_Enrollments` WHERE OUTCOMES_AY = ? AND OU_ABBREV = ?");
        $getEnrollTime20142015->bindParam(1, $ayYearBackTwo, PDO::PARAM_STR);
        $getEnrollTime20142015->bindParam(2, $this->college, PDO::PARAM_STR);
        $getEnrollTime20142015->execute();
        $rowsGetEnrollTime20142015 = $getEnrollTime20142015->rowCount();

        if ($rowsGetEnrollTime20142015 > 0) {

            $data = $getEnrollTime20142015->fetch();

            $uFull20142015 = $data["ENROLL_UGRAD_FULLTIME"];
            $uPart20142015 = $data["ENROLL_UGRAD_PARTTIME"];
            $gFull20142015 = $data["ENROLL_GRADPRFSNL_FULLTIME"];
            $gPart20142015 = $data["ENROLL_GRADPRFSNL_PARTTIME"];


        }

        $uTotal20142015 = $uFull20142015 + $uPart20142015;
        $gTotal20142015 = $gFull20142015 + $gPart20142015;
        $tFull20142015 = $uFull20142015 + $gFull20142015;
        $tPart20142015 = $uPart20142015 + $gPart20142015;
        $total20142015 = $tFull20142015 + $tPart20142015;

        echo "
        <div class='container-fluid'>
          <h2 class='text-center'>" . $this->college . " Faculty Actions</h2>
          <div class='row'>
            <div class='col-md-8'>
              <div class='table-responsive'>
                <table class='table table-condensed'>
                  <thead>
                    <tr>
                      <th></th>
                      <th>2016</th>
                      <th>2015</th>
                      <th>2014</th>
                    </tr>
                  </thead>
                  <tbody>
                    <thead>
                      <tr>
                        <th>Undergraduate</th>
                        <th>$uTotal20162017</th>
                        <th>$uTotal20152016</th>
                        <th>$uTotal20142015</th>
                      </tr>
                    </thead>
                    <tr>
                      <td>Full-time</td>
                      <td>$uFull20162017</td>
                      <td>$uFull20152016</td>
                      <td>$uFull20152016</td>
                    </tr>
                    <tr>
                      <td>Part-time</td>
                      <td>$uPart20162017</td>
                      <td>$uPart20152016</td>
                      <td>$uPart20152016</td>
                    </tr>
                    <thead>
                      <tr>
                        <th>Graduate/Professional</th>
                        <th>$gTotal20162017</th>
                        <th>$gTotal20152016</th>
                        <th>$gTotal20142015</th>
                      </tr>
                    </thead>
                    <tr>
                      <td>Full-time</td>
                      <td>$gFull20162017</td>
                      <td>$gFull20152016</td>
                      <td>$gFull20152016</td>
                    </tr>
                    <tr>
                      <td>Part-time</td>
                      <td>$gPart20162017</td>
                      <td>$gPart20152016</td>
                      <td>$gPart20152016</td>
                    </tr>
                    <thead>
                      <tr>
                        <th>Total - All Levels</th>
                        <th>$total20162017</th>
                        <th>$total20152016</th>
                        <th>$total20142015</th>
                      </tr>
                    </thead>
                    <tr>
                      <td>Full-time</td>
                      <td>$tFull20162017</td>
                      <td>$tFull20152016</td>
                      <td>$tFull20152016</td>
                    </tr>
                    <tr>
                      <td>Part-time</td>
                      <td>$tPart20162017</td>
                      <td>$tPart20152016</td>
                      <td>$tPart20152016</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      ";

    }

    public function exportToPng($base64Image, $pngName)
    {

        // /unlink("../../uploads/charts/" . $pngName . ".png");
        $data = explode(",", $base64Image);
        $fileHandler = fopen("../../uploads/charts/" . $pngName . ".png", "wb");
        fwrite($fileHandler, base64_decode($data[1]));
        fclose($fileHandler);

    }

}

?>
