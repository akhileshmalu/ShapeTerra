<?php
session_start();
if(!$_SESSION['isLogged']) {
    header("location:login.php");
    die();
}
require_once("../Resources/Includes/connect.php");
$aysubmit = array();
$ayname = "";
$error = array();

//Include Header
require_once("../Resources/Includes/header.php");
?>

<body>
<?php
// Include Menu and Top Bar
require_once("../Resources/Includes/menu.php");
?>


<link href="Css/approvebp.css" rel="stylesheet" type="text/css"/>

<div class="hr"></div>
<div id="main-content" class="col-xs-10">
  <h1 id="title">TITLE</h1> 

  <div id="list" class="col-xs-2">
      <ul class="tabs-nav">
          <li class="tab1 active">1. ITEM 1</li>
          <li class="tab2 disabled">2. ITEM 2</li>
          <li class="tab3 disabled">3. ITEM 3</li>
          <li class="tab4 disabled">4. ITEM 4</li>
      </ul>
  </div>


  <div id="form" class="col-xs-9">
      <form action="" method="POST">   
          <div class="form-group tab1 active" id="actionlist">
            <!--  <?php if (isset($_POST['approve'])) { ?>
                 <div class="col-xs-8 alert alert-success">
                     <?php foreach ($error as $value) echo $value; ?>
                 </div>
             <?php } ?> -->

             <label class="col-xs-12" for="missiontitle">FORM LABEL 1</label>
             
             <div class="col-xs-12">
                  <textarea rows="5" cols="25" wrap="hard" class="form-control" name="NAME" id=""></textarea>
                 
                 <button id="next-tab" type="button" class="btn-primary col-xs-3 pull-right changeTab"> Next Tab
                 </button>
              </div>
         </div>

         <div class="form-group hidden tab2" id="actionlist">
             <label class="col-xs-12" for="missiontitle">FORM LABEL 2</label>
             
             <div class="col-xs-12">
                  <textarea rows="5" cols="25" wrap="hard" class="form-control" name="NAME" id=""></textarea>
                 
                 <button id="next-tab" type="button" class="btn-primary col-xs-3 pull-right changeTab"> Next Tab
                 </button>
              </div>
         </div>

         <div class="form-group hidden tab3" id="actionlist">
              <label class="col-xs-12" for="missiontitle">FORM LABEL 3</label>
             
             <div class="col-xs-12">
                  <textarea rows="5" cols="25" wrap="hard" class="form-control" name="NAME" id=""></textarea>
                 
                 <button id="next-tab" type="button" class="btn-primary col-xs-3 pull-right changeTab"> Next Tab
                 </button>
              </div>
         </div>

         <div class="form-group hidden tab4" id="actionlist">
             <label class="col-xs-12" for="missiontitle">FORM LABEL 4</label>
             
             <div class="col-xs-12">
                  <textarea rows="5" cols="25" wrap="hard" class="form-control" name="NAME" id=""></textarea>
                 
                 <button id="next-tab" type="button" class="btn-primary col-xs-3 pull-right changeTab"> Next Tab
                 </button>
              </div>
         </div>
      </form>
  </div>
</div>





<?php
//Include Footer
require_once("../Resources/Includes/footer.php");
?>

<script src="../Resources/Library/js/tabAlert.js"></script>
