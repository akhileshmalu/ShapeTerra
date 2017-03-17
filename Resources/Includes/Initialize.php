<?php

  Class Initialize {

    public $connection, $mysqli, $menucon;

    function __construct()
    {
        session_start();
      //error logging set to 0 for prod
      error_reporting(1);
      @ini_set('display_errors', 1);
        ini_set('always_populate_raw_post_data', '-1');

      //Setting Default Time
      date_default_timezone_set('America/New_York');
      ini_set("date.timezone", "America/New_York");

      //security headers
      header("X-Frame-Options: SAMEORIGIN");

      $this->connection = $this->connectToDB();

        $_SESSION['site'] = "Shapeterra";

      //Global Email Variable
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= "From: ShapeTerra <admin@ShapeTerra.com>" . "\r\n";

    }

    private function connectToDB()
    {
      define('HOSTNAME', "localhost");
      define("USERNAME", "root");
      define("PASSCODE", "root");
      define("DB", "TESTDB");


        //Temporary objects for mysqli in order to run old code
      $this->mysqli = new mysqli(HOSTNAME,USERNAME,PASSCODE,DB);
      $mysqli1 = new mysqli(HOSTNAME,USERNAME,PASSCODE,DB);
      $mysqli2 = new mysqli(HOSTNAME,USERNAME,PASSCODE,DB);
//      $this->menucon = new mysqli(HOSTNAME,USERNAME,PASSCODE,DB);

      if($this->mysqli->connect_error){
          echo "Connection Failed". $this->mysqli->connect_error;
      }

      // PDO Object for SQL
      try{
          $this->connection = new PDO(sprintf('mysql:host=%s;dbname=%s', HOSTNAME, DB), USERNAME, PASSCODE);
          $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $this->connection;
      }
      catch (PDOException $e){
          error_log($e->getMessage());
          exit();
      }

    }

    public function checkSessionStatus()
    {
      if(!$_SESSION['isLogged']) {
          header("location:login.php");
          die();
      }

    }

    // Input Security Check
    public function test_input($data)
    {
        $data = trim($data);
        $data = htmlspecialchars($data,ENT_COMPAT,'ISO-8859-1', true);
        $data = htmlentities($data,ENT_COMPAT,'ISO-8859-1', true);
        return $data;
    }

    /*Common String function to interchange AY-ID and Desc
    */
    // When there is start date and end date on form.
    public function stringdatestoid ($string1, $string2)
    {
        $id1 = intval(substr($string1,2,2));
        $id2= intval(substr($string2,2,2));
        $id = ($id1*100)+$id2;
        return $id;
    }

    // e.g. 1617 - AY2016-2017
    public function idtostring ($id)
    {
      $id2= $id %100;
      $id1= intval($id/100);

      if($id2<$id1){
          $id1 =str_pad($id1, 2, '0', STR_PAD_LEFT);
          $id2 =str_pad($id2, 2, '0', STR_PAD_LEFT);
          $string = "AY19".$id1."-20".$id2;
          return $string;
      }else{
          $id1 = str_pad($id1, 2, '0', STR_PAD_LEFT);
          $id2 =str_pad($id2, 2, '0', STR_PAD_LEFT);
          $string = "AY20" . $id1 . "-20" . $id2;
          return $string;
      }

    }

    // e.g. AY2016-2017 to 1617.
    public function stringtoid ($string)
    {
        $id = intval(substr($string,4,2));
        $id = ($id*100)+$id+1;
        return $id;
    }

    /*
     * Function for preserving HTML line breaks in Text area
     */
    public function mynl2br($text)
    {
        return strtr($text, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />', ";" => '&#59;', "'" => '&#39;'));
    }

    public function mybr2nl($text)
    {
        return strtr($text, array("<br />" => "\r\n", "&#39;" => "'", "&#59;" => ";"));
    }

    public function getUserName($author){
        try {
            $sql = "SELECT FNAME,LNAME FROM PermittedUsers WHERE ID_STATUS = :author;";
            $result = $this->connection->prepare($sql);
            $result->bindParam(':author', $author, 1);
            $result->execute();

        } catch (PDOException $e) {
            //SYSTEM::pLog($e->__toString(), $_SERVER['PHP_SELF']);
            error_log($e->getMessage());
        }

        $rows = $result->fetch(2);
        return $rows['FNAME']." ".$rows['LNAME'];
    }

  }
