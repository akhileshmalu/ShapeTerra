<?php

require_once("../Resources/Includes/Initialize.php");
$initalize = new Initialize();
$initalize->checkSessionStatus();

$message = array();
$errorflag = 0;

if(isset($_POST['forgot'])){

    // Email Validity Check
    if (empty($_POST['email'])) {
        $message[0] = " Please enter email address.";
        $errorflag = 1;
    } else{
        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            $message[1] = "Invalid Email Address.";
            $errorflag = 1;
        }
    }

    // Process if Input is valid
    if($errorflag == 0){

        //Establish Connection
        $email = $initalize->test_input($_POST['email']);

        $sql ="select FNAME,PW_DEV FROM PermittedUsers where NETWORK_USERNAME = '$email'";
        $result = $mysqli -> query($sql);
        $rows = $result -> num_rows;

        if($rows != 0){
            while($record =$result ->fetch_assoc()) {
                $fname = $record['fname'];
                $hash = md5( rand(0,1000));
                $type = 0;

                /* Type is defined as request type for email link verification
                1-  Active Account Verification
                0-  Forgot Password Verification
                 */

                $sql = "update PermittedUsers SET HASH = $hash where NETWORK_USERNAME ='$email'";
                $mysqli->query($sql);

                //Confirmation Mail Variables
                $sub = "You have successfully retrieved password.";
                $msg = "Hello $fname" . "<br/>" . "<br/>";
                $msg .= "Please reset your password by clicking over below link"."<br/>";
                $msg .= "http://".$site."/Pages/verify.php?email=$email&hash=$hash&type=$type";

                mail($email, $sub, $msg, $headers);
                $message[0] = "Your Password has been sent to registered email id";
            }
            unset($record);
            unset($rows);
            unset($result);

        }else{
            $message[0] = "No Record found.Please use your registered email address.";
        }
    }
    $mysqli->close();

}

require_once("../Resources/Includes/header.php");


?>

<link href="Css/forgotpassword.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div id ="Main" class="row text-center">
    <svg id="logo" class="col-xs-6 col-xs-offset-3 col-xs-offset-5 col-md-6 col-md-offset-3 col-lg-2 col-lg-offset-5" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 276 157.5"
    enable-background="new 0 0 276 157.5" xml:space="preserve">
    <switch>
    <foreignObject requiredExtensions="&ns_ai;" x="0" y="0" width="1" height="1">
        <i:pgfRef  xlink:href="#adobe_illustrator_pgf">
        </i:pgfRef>
    </foreignObject>
    <g i:extraneous="self">
        <g>
            <g>
                <path fill="#FFFFFF" d="M67.7,112.4c-0.9,0.4-1.1,0.8-1.1,1.9l0,4.4v0.1c0,2.3,0.2,3,1.1,3.7c0.5,0.4,1.3,0.6,2.2,0.6
                    c1.6,0,2.6-0.7,2.8-1.9c0.1-0.5,0.2-1,0.2-1.7v-0.2l0-3.9v-0.1c0-1.6-0.3-2.3-1.3-2.8h3.6c-1.1,0.7-1.4,1.2-1.4,3.1l-0.1,4.7
                    c-0.1,2.6-1.4,3.6-4.3,3.6c-3.4,0-5.2-1.2-5.2-3.4v-0.2l-0.1-5.9c0-1.2-0.2-1.5-0.8-1.9H67.7z"/>
                <path fill="#FFFFFF" d="M79.2,121.1c0-0.5,0-0.8,0-1c0-0.1,0-0.1,0-0.1l0-6.3c-0.3-0.6-0.8-1-1.6-1.3l3.6,0l7.2,8.3l0-5.1v-0.1
                    c0-2-0.3-2.6-1.4-3.1h3.4c-0.4,0.1-0.8,0.7-1,1.3c-0.1,0.4-0.3,1.4-0.3,2.3l-0.1,2.4c0,0,0,0,0,0.1c0,0.1,0,0.2,0,0.3
                    c0,0.1,0,0.2,0,0.3c0,0.1,0,0.1,0,0.1l-0.1,4.5l-1.3,0l-0.1-0.1c-0.2-0.3-1.4-1.7-3.5-4.3c-1.9-2.3-3.3-3.9-4-4.6l-0.2-0.2v4.8
                    v0.5l0.1,1.1c0.1,1.9,0.2,2.2,1,2.6l0.1,0.1h-3.3C78.8,123.1,79.1,122.5,79.2,121.1z"/>
                <path fill="#FFFFFF" d="M94.4,121.6v-0.1v-7.1v-0.1c0-1.1-0.2-1.5-0.9-1.8h4.2c-0.7,0.4-0.8,0.9-0.8,2.2v0.1l0,6.8v0.1
                    c0,1.1,0.2,1.6,0.7,1.9h-4.1C94.3,123.1,94.4,122.8,94.4,121.6z"/>
                <path fill="#FFFFFF" d="M105.3,123.6l-0.8-1.9l-0.7-1.8l-0.1-0.1l-2.4-5.6c-0.4-1-0.8-1.5-1.2-1.8l4.6,0c-0.7,0.4-0.9,0.6-0.9,1
                    c0,0.2,0.1,0.5,0.2,0.8l0.1,0.1l0.8,2.1c0,0.1,0,0.1,0,0.1c0.1,0.2,0.2,0.4,0.4,0.9c0.1,0.2,0.2,0.5,0.4,0.9
                    c0,0.1,0,0.1,0.1,0.1l1.4,3.2l2.4-5.5c0.4-1,0.8-2.2,0.8-2.6c0-0.4-0.3-0.8-0.9-1.2h3.3c-0.6,0.4-1.2,1.2-1.7,2.1l-0.7,1.5
                    l-0.1,0.2l-2,4.5l-0.1,0.1l-0.6,1.4l-0.6,1.4l-0.1,0.2h-1.6L105.3,123.6z"/>
                <path fill="#FFFFFF" d="M115,121l0-0.7v-0.5v-5.1v-0.1l0-0.6c-0.1-0.8-0.4-1.4-1-1.6h0.2l3.9,0.1l4-0.1l0.1,0l0.2,2.4l-0.1,0
                    c-0.6-1.3-1.7-1.8-3.5-1.8h-1.3l-0.1,4.5h1.1h0c1.6,0,2.4-0.4,2.6-1.4h0.1v3.3h-0.1c-0.2-1-1.1-1.5-2.4-1.5l-0.2,0h-1.2v3.3v0.2
                    l0.1,0.9c0,0.6,0.3,0.8,1.2,0.8c1.7,0,2.6-0.3,3.5-1.1c0.4-0.3,0.5-0.5,0.7-0.9l0.1,0l-0.7,2.4H114
                    C114.9,123.2,114.9,123.1,115,121z"/>
                <path fill="#FFFFFF" d="M126.5,121.7l0-2.2v-0.2v-0.8v-0.7c0-0.1,0-0.1,0-0.2l-0.1-3.5c0-0.9-0.3-1.5-0.9-1.7h1c0,0,0.1,0,0.1,0
                    c0.1,0,0.5,0,1,0c0.1,0,0.5,0,1.2,0l1.6-0.1c2.4,0,3.5,0.4,4.2,1.5c0.3,0.4,0.4,0.9,0.4,1.4c0,1.5-1,2.6-2.7,2.9l2.2,2.5
                    l0.1,0.1c0.1,0.1,0.4,0.4,1,1.1c0.5,0.5,0.8,0.9,1,1c0.1,0.1,0.4,0.3,0.9,0.7l0.1,0.1c-0.3,0.1-0.4,0.1-0.9,0.1l-0.7,0
                    c-0.1,0-0.1,0-0.2,0c-1.3,0-2.2-0.5-3.1-1.7c0-0.1-0.1-0.1-0.1-0.1l-2.9-3.4H129l0.1,3.2v0.1c0,0.9,0.2,1.2,0.8,1.7l0.1,0.1
                    h-4.4C126.2,123.3,126.5,122.7,126.5,121.7z M129.9,118L129.9,118c1.8,0,2.8-0.9,2.8-2.6c0-1.5-1-2.5-2.4-2.5
                    c-0.1,0-0.1,0-0.2,0c-0.1,0-0.1,0-0.1,0l-1,0l0,5L129.9,118z"/>
                <path fill="#FFFFFF" d="M138.7,120.2h0.2c0.7,2,2.2,3.2,4,3.2c1.4,0,2.4-0.9,2.4-2c0-1.1-0.7-1.7-2.9-2.5
                    c-2.1-0.8-2.9-1.3-3.3-2.2c-0.2-0.4-0.3-0.8-0.3-1.2c0-1.9,1.7-3.4,3.9-3.4c0.9,0,1.6,0.2,2.7,0.6c0.3,0.1,0.4,0.1,0.5,0.1
                    c0.2,0,0.4-0.1,0.7-0.5l0.1,2.8h-0.2c-0.5-1.4-2.1-2.5-3.5-2.5c-1.2,0-1.9,0.7-1.9,1.8c0,1.1,0.5,1.6,2.6,2.3
                    c1.9,0.7,2.9,1.3,3.4,2.2c0.3,0.5,0.4,1,0.4,1.5c0,2.1-1.9,3.5-4.5,3.5c-1.6,0-3.7-0.7-4.4-1.4L138.7,120.2z"/>
                <path fill="#FFFFFF" d="M151.5,121.6v-0.1v-7.1v-0.1c0-1.1-0.2-1.5-0.9-1.8h4.2c-0.7,0.4-0.8,0.9-0.8,2.2v0.1l0,6.8v0.1
                    c0,1.1,0.2,1.6,0.7,1.9h-4.1C151.3,123.1,151.5,122.8,151.5,121.6z"/>
                <path fill="#FFFFFF" d="M161.2,121.4v-0.2l0-8.1h-0.2c-1,0-1.7,0.2-2.5,0.7c-0.6,0.4-0.7,0.6-1.1,1.1l-0.2-0.1l0.8-2.6
                    c0.6,0.2,0.8,0.2,3,0.2h1.9c2.6,0,4.5-0.1,5.1-0.2l0.2,0.2l-1,2.2l-0.2-0.1c0-0.1,0-0.1,0-0.2c0-1.1-0.6-1.5-1.9-1.5h-0.1
                    l-1.3,0l0.1,8.3c0,1.5,0.2,1.9,1.1,2.3h-4.8C161,123.3,161.2,122.8,161.2,121.4z"/>
                <path fill="#FFFFFF" d="M173.7,123.4c0.8-0.5,0.9-0.7,0.9-1.8v-2.7l-3.3-4.9c-0.6-0.9-1-1.4-1.5-1.6l4.9,0l-0.2,0.1l-0.3,0.1
                    c-0.1,0.1-0.2,0.2-0.3,0.3c-0.1,0.1-0.2,0.3-0.2,0.4c0,0.2,0.2,0.6,0.5,1l2.4,3.7l1.5-2.3l0.1-0.1c0.4-0.7,0.8-1.6,0.8-2
                    c0-0.5-0.3-0.8-0.9-1.1h3.7c-0.5,0.3-0.9,0.7-1.5,1.5c-0.3,0.4-0.8,1.1-1.5,2c0,0.1-0.1,0.1-0.1,0.1l-1.8,2.7v2.3v0.1
                    c0,1.4,0.2,2,0.9,2.3h-4.4C173.6,123.5,173.6,123.5,173.7,123.4z"/>
                <path fill="#FFFFFF" d="M188.8,118c0-3.4,2.8-5.9,6.6-5.9c3.8,0,6.6,2.4,6.6,5.8c0,3.5-2.8,5.9-6.8,5.9
                    C191.6,123.9,188.8,121.4,188.8,118z M199.4,118c0-3-1.6-5.3-3.8-5.3c-2.2,0-3.8,2.3-3.8,5.5c0,3,1.5,5.1,3.6,5.1
                    C197.7,123.2,199.4,121,199.4,118z"/>
                <path fill="#FFFFFF" d="M205.9,121.7c0-0.6,0.1-1.3,0.1-2.2v-0.2l0-3.5v-0.1v-0.3c0-2-0.3-2.9-1-3l7.8-0.1l0.1,2.5h-0.1
                    c-0.3-1.3-1.3-2-2.7-2l-0.2,0l-1.4,0l0,4.6h1.4c1.3,0,1.7-0.2,2.3-1.3c0-0.1,0-0.1,0.1-0.1h0.1l0,3.4l-0.1,0
                    c-0.3-1-1-1.6-2.3-1.6h-0.1l-1.3,0v3.3v0.1c0,1.4,0.3,2.1,0.9,2.2H205C205.6,123.3,205.8,122.8,205.9,121.7z"/>
            </g>
            <g>
                <path fill="#FFFFFF" d="M189.4,155.1c-4.7,0-8-4.6-8-11.3c0-6.6,3.2-11.2,8-11.2c4.7,0,8.3,4.8,8.3,11.2
                    C197.6,150.5,194.2,155.1,189.4,155.1z M189,156.2c7.8,0,13.5-5.3,13.5-12.5c0-7.1-5.4-12.2-12.8-12.2
                    c-7.8,0-13.7,5.4-13.7,12.5C176,151,181.6,156.2,189,156.2z"/>
                <path fill="#FFFFFF" d="M202.4,155.8h0.4c1.9-0.1,5-0.2,8.6-0.2c2.5,0,4.8,0.1,6.6,0.2h0.1l1.4-6.2l-0.3,0l-0.1,0.2
                    c-1.7,3.7-3.5,4.8-7.2,4.8c-2.7,0-3.2-0.3-3.2-1.9l0-16.5c0-2,0.8-3.3,2.4-4.1h-9c1.8,0.7,2.3,1.7,2.4,4.4l0.1,8
                    c0,0,0,8.4,0,8.5C204.6,154.1,203.8,155.1,202.4,155.8z"/>
                <path fill="#FFFFFF" d="M219.5,155.8h8c-1.5-0.7-2.1-1.7-2.1-3.6l0-16c0-2.4,0.3-3.1,2-4.1h-7.8c1.5,1,1.7,1.5,1.7,3.4v0.7
                    l-0.1,16.1C221.2,154.1,220.8,154.9,219.5,155.8z"/>
                <path fill="#FFFFFF" d="M31.3,131.4c-7.8,0-13.6,5.4-13.6,12.5c0,0.6,0.1,1.3,0.1,1.9c-1.1-1.9-3.3-3.3-7.5-4.8l-1.5-0.5
                    c-2.9-1.1-4.2-2.3-4.2-4.4c0-2.2,1.9-4,4.2-4c2.3,0,4.8,1.3,6.2,3.1c0.6,0.8,0.8,1.2,1.3,2.3l0.6-0.1l-0.4-5.7l-0.4,0
                    c-0.3,0.5-0.6,0.6-1.1,0.6c-0.3,0-0.6,0-1.1-0.3c-1.7-0.7-3.6-1.2-5.4-1.2c-4.7,0-8.1,2.7-8.1,6.5c0,3.5,2.1,5.6,8,7.9
                    c1.1,0.4,1.7,0.7,2,0.8c2.6,1.1,4.1,2.8,4.1,4.9c0,2.7-2.3,4.7-5.3,4.7c-3.7,0-7-2.6-8.6-6.8L0,149l0.2,5
                    c0.4,0.3,0.6,0.4,1.3,0.8c2.9,1.5,4.7,2,7.6,2c1.7,0,3.2-0.3,4.6-0.7c2.9-1.1,5-4.1,5-7c0-0.2,0-0.3,0-0.5
                    c1.9,4.6,6.5,7.7,12.1,7.7c7.8,0,13.4-5.3,13.4-12.6C44.1,136.6,38.7,131.5,31.3,131.4z M31.1,155.1c-4.7,0-8-4.6-8-11.2
                    c0-6.6,3.2-11.2,7.9-11.2c4.7,0,8.3,4.7,8.3,11.2C39.3,150.4,35.9,155.1,31.1,155.1z"/>
                <path fill="#FFFFFF" d="M105.4,143.6l0-5.8l0.1-2.8c0-1.2,0.7-2.1,1.9-2.9h-8.4c1.2,0.8,1.6,1.8,1.7,4v0.1l-0.1,7.5H89.6l0-5.4
                    v-0.2c0-3.9,0.3-4.8,2.4-6l0.1-0.1l-9,0l-15.5,0l-6.8,0c1.5,0.7,2.2,2.1,2.2,5.1l-0.3,10.9c0,1.4-0.1,2.2-0.4,3
                    c-0.6,2.2-2.9,3.5-6,3.5c-2.5,0-4.7-0.9-5.6-2.3c-0.8-1.2-1.1-2.6-1.1-5.1l0-10.5l0.1-1.6c0.1-1.8,0.6-2.6,2.2-3.2h-8.8
                    c1.3,0.4,1.8,1.2,1.9,3.2l0,13.9c0,2.5,0.7,4,2.3,5.1c1.9,1.3,4.5,1.9,7.9,1.9c6.3,0,9.4-2.7,9.4-8.4l0.2-8.6c0-4,1.5-6,6.1-6
                    l1.6,0l0.1,17.6c0,3-0.5,4.2-1.9,4.8h8.9c-1.8-0.6-2.4-1.7-2.4-4.6l-0.1-17.9l3.1,0c4.3,0,4.5,2,4.5,6.2l0,6.5v0.2l-0.1,2.5v0.3
                    l-0.1,2.8c-0.1,1.9-0.6,3.1-1.6,3.9l-0.3,0.2h8.4c-1.3-0.8-1.8-1.6-1.8-3.4l0-7.2h11.1l-0.1,5.9c0,2.6-0.6,3.8-2.1,4.7h9
                    c-1.5-0.7-2.1-1.9-2.1-3.9l0-6.8L105.4,143.6z"/>
                <path fill="#FFFFFF" d="M266.6,155.8h9.4c-1.2-0.4-2-1.3-3.1-3.5c0,0-3.2-7.1-3.4-7.6c-0.5-1.1-0.5-1-5.4-13.1
                    c-0.5,0.2-2.5,0.5-2.6,0.5c0,0-0.8,1.9-1.3,3c-0.7,1.7-4,8.7-4,8.7l-3.5,7.8c-0.5,1.1-1,2-1.7,2.7l-0.1-3.2l0-5l0.1-3l0.3-6.3
                    c0.1-2.5,0.5-3.5,2-4.6h-5.7c1.4,0.6,2,2.4,2,5.6l0,2.5l-0.2,9.3l-14.4-17.4l-6.9,0c1.3,0.7,2.2,1.4,3,2.2l0,16.5
                    c0,2.8-0.5,3.9-2.5,4.9h7.2c-2.2-0.7-3-1.8-3-4.6l0-15.1l16.2,19.6h0.4h1.7h5.3c-1.2-0.5-2-1.4-2-2.5c0-0.9,0.4-3.1,0.9-4.3
                    l1.8-3.9h8l3,7.5c0,0,0,0,0.1,0.2c0.2,0.4,0.3,1,0.3,1.2C268.5,154.7,267.9,155.4,266.6,155.8z M257.9,143.7l3.4-7.9l3.2,7.9
                    H257.9z"/>
                <path fill="#FFFFFF" d="M178.8,155.1c-0.9-0.7-1.6-1.6-2.8-2.8c-0.6-0.7-2.1-2.3-2.9-3.2c-0.1-0.1-0.1-0.1-0.1-0.2l-3.9-4.8
                    c3.6-0.9,5.5-3,5.5-6.1c0-2.1-1.1-4-2.9-5.1c-1.3-0.7-3.3-1.1-5.7-1.1c-0.2,0-0.8,0-0.8,0l-5.1,0.1l-1.1,0l-3.8,0
                    c1.6,0.8,2.1,1.7,2.1,3.7l0,16l0,0.4c0,0.9-0.2,1.6-0.6,2.2c-0.4-0.5-0.8-1.2-1.3-2.2c0,0-3.2-7.1-3.4-7.6
                    c-0.5-1.1-0.5-1-5.4-13.1c-0.5,0.2-2.5,0.5-2.6,0.5c0,0-0.8,1.9-1.3,3c-0.7,1.7-2.4,5.4-2.4,5.4l-1.6,3.3l-2.9,6.6
                    c-0.4,0.9-0.7,1.2-1.5,2.1c-2,2.2-4.1,3.2-6.5,3.2c-5.6,0-9.3-4.9-9.3-12.3c0-7,3.4-11.7,8.4-11.7c3.2,0,5.9,2,8.8,6.3l0.1,0.2
                    l0.5,0l-0.6-5.8c-0.4,0.1-0.6,0.2-0.8,0.2c-0.1,0-0.2,0-0.3-0.1c-2.2-1.1-5.3-1.9-7.8-1.9c-8,0-13.7,5.5-13.7,13.2
                    c0,7.6,5.6,13.1,13.4,13.1c2,0,3.8-0.3,5.8-1h6.6c-1.2-0.5-2-1.4-2-2.5c0-0.9,0.4-3.1,0.9-4.3l1.8-3.9h8c0,0,3,7.5,3.1,7.7
                    c0.2,0.4,0.3,1,0.3,1.2c0,0.8-0.7,1.4-1.9,1.8h5.8h3.5h6.2c-2.2-0.6-2.9-1.6-2.9-4.2l-0.1-6.6l1.1-0.1c1,0,1.3,0.1,2,0.9
                    l1.2,1.4c0,0,4.2,5.2,4.3,5.4c1.2,1.3,1.9,2.1,2.2,2.4c1.2,1.1,3.2,2.4,6.9,2.5c1.9,0.1,2.6-0.2,3.9-0.7
                    C181.3,156.5,180,156,178.8,155.1z M140.3,143.7l3.4-7.9l3.2,7.9H140.3z M164.2,143.9c-0.2,0-2.5-0.1-2.5-0.1l0-10.6l2.2-0.1
                    c1.4,0,2.6,0.2,3.5,0.6c1.6,0.6,2.7,2.6,2.7,4.9C170.1,142.1,168,143.9,164.2,143.9z"/>
            </g>
            <g>
                <path fill="#FFFFFF" d="M137.6,94.5c-3.3,0-5.2-0.3-8.3-1.4c-3-1.1-4.6-1.9-7-3.9l1.1-1.7c2.3,1.8,3.6,2.7,6.3,3.6
                    c2.9,1.1,4.7,1.4,7.8,1.4c3.1,0,5-0.3,7.9-1.4c2.7-1,4.1-1.8,6.3-3.6l1.1,1.7c-2.4,2-4.1,2.9-7,3.9
                    C142.8,94.1,140.9,94.5,137.6,94.5z"/>
                <g>
                    <path fill="#FFFFFF" d="M128,88.2l1.8,0.8c-0.2-0.2-0.3-0.4-0.1-0.9l1.4-3c-0.3,0-0.7-0.1-1.4-0.3l-0.1,0c0,0,0.1,0,0.1,0.1
                        c0.2,0.2,0.3,0.3,0.1,0.7c0,0-0.9,2-1,2.1c-0.3,0.5-0.3,0.6-0.7,0.6C128.1,88.2,128.1,88.2,128,88.2z"/>
                </g>
                <g>
                    <path fill="#FFFFFF" d="M134.9,87.5L134.9,87.5c-0.7-0.5-1-0.9-0.8-1.4c0.1-0.5,0.5-0.8,1-0.6c0.5,0.1,0.7,0.6,0.6,1.2
                        C135.5,87,135.2,87.4,134.9,87.5z M134,87.9c1,0.6,1.3,1.2,1.1,1.9c-0.2,0.7-0.7,1-1.3,0.8c-0.7-0.2-1-0.8-0.7-1.7
                        C133.3,88.5,133.6,88.1,134,87.9z M133.8,87.8l-0.1,0c-0.2,0-0.4,0.1-0.6,0.1c-0.5,0.1-0.9,0.5-1.1,1c-0.2,0.9,0.4,1.7,1.6,2.1
                        c1.2,0.3,2.3-0.1,2.6-1.1c0.1-0.2,0.1-0.5,0-0.7c-0.1-0.5-0.3-0.8-1.1-1.4c0,0-0.1-0.1-0.1-0.1c0.8,0,1.4-0.3,1.5-0.9
                        c0.2-0.7-0.4-1.3-1.4-1.6c-1.1-0.3-2,0.1-2.2,1c-0.1,0.4,0,0.8,0.3,1.2C133.4,87.5,133.5,87.6,133.8,87.8z"/>
                </g>
                <g>
                    <path fill="#FFFFFF" d="M141,90.3c-0.8,0.1-1.5-0.6-1.7-1.8c-0.2-1.1,0.2-1.9,0.8-2c0.8-0.1,1.5,0.7,1.7,1.8
                        C142.1,89.4,141.7,90.1,141,90.3z M141,90.5c1.5-0.3,2.4-1.3,2.2-2.6c-0.2-1.3-1.5-2-3-1.7c-0.4,0.1-0.7,0.2-1.1,0.4
                        c-0.9,0.5-1.2,1.2-1,2.2C138.3,90.1,139.5,90.8,141,90.5z"/>
                </g>
                <g>
                    <path fill="#FFFFFF" d="M145.6,89l1.8-0.9c-0.3,0.1-0.5-0.1-0.7-0.5l-1.4-3c-0.2,0.2-0.5,0.5-1.1,0.9l-0.1,0.1c0,0,0.1,0,0.1,0
                        c0.3-0.1,0.4,0,0.6,0.3c0,0,1,2,1,2.1C145.9,88.5,145.9,88.6,145.6,89C145.7,88.9,145.7,88.9,145.6,89z"/>
                </g>
                <polygon fill="#FFFFFF" points="147.3,52.2 160.3,52.2 158.8,54.6 148.7,54.6                 "/>
                <polygon fill="#FFFFFF" points="149,55.7 158.8,55.7 158.8,64.2 174.5,64.2 174.5,65.3 158.8,65.3 158.8,76.1 156.8,76.1
                    156.8,57.8 151.1,57.8 151.1,76.1 149,76.1               "/>
                <path fill="#FFFFFF" d="M161.2,58.8c1.1-0.5,1.7,0,1.7,0v17.3h-1.7V58.8z"/>
                <path fill="#FFFFFF" d="M165.5,60.8c1.1-0.5,1.7,0,1.7,0v15.3h-1.7V60.8z"/>
                <path fill="#FFFFFF" d="M169.8,62.1c1.1-0.5,1.7,0,1.7,0v14.1h-1.7V62.1z"/>
                <path fill="#FFFFFF" d="M174.1,63c1.1-0.5,1.7,0,1.7,0v13.1h-1.7V63z"/>
                <path fill="#FFFFFF" d="M142.6,76.1h2.8l-3.4-4.5h3.9v-1.9h-4.2l0.1-13.6l3.9-4h-2.9l2.9-4.3h-2.8l2.4-2.9
                    c1.1,0.8,1.6,1.4,2.5,2.5c1.1,1.4,1.6,2.3,2.3,4v-5.2c-0.8-0.7-1.3-1.1-2.3-1.7c-1.5-0.9-2.5-1.2-4.2-1.6l-4.1,4.9h3.1l-2.8,4.3
                    h2.6l-3.1,3.3l0,15.8L142.6,76.1z"/>
                <polygon fill="#FFFFFF" points="127.9,52.2 114.9,52.2 116.3,54.6 126.5,54.6                 "/>
                <polygon fill="#FFFFFF" points="126.1,55.7 116.3,55.7 116.3,64.2 100.7,64.2 100.7,65.3 116.3,65.3 116.3,76.1 118.3,76.1
                    118.3,57.8 124.1,57.8 124.1,76.1 126.1,76.1                 "/>
                <path fill="#FFFFFF" d="M113.9,58.8c-1.1-0.5-1.7,0-1.7,0v17.3h1.7V58.8z"/>
                <path fill="#FFFFFF" d="M109.6,60.8c-1.1-0.5-1.7,0-1.7,0v15.3h1.7V60.8z"/>
                <path fill="#FFFFFF" d="M105.3,62.1c-1.1-0.5-1.7,0-1.7,0v14.1h1.7V62.1z"/>
                <path fill="#FFFFFF" d="M101.1,63c-1.1-0.5-1.7,0-1.7,0v13.1h1.7V63z"/>
                <path fill="#FFFFFF" d="M132.5,76.1h-2.8l3.4-4.5h-3.9v-1.9h4.2l-0.1-13.6l-2.9-4h2.9l-3.9-4.3h2.8L130,45
                    c-1.1,0.8-1.6,1.4-2.5,2.5c-1.1,1.4-1.7,2.3-2.3,4v-5.2c0.8-0.7,1.3-1.1,2.3-1.7c1.5-0.9,2.5-1.2,4.2-1.6l4.1,4.9h-3.1l3.7,4.3
                    h-2.6l2.2,3.3l0,15.8L132.5,76.1z"/>
                <g>
                    <path fill="#FFFFFF" d="M140.9,39.6c2-0.7,3.3-0.8,5.4-0.6c2.7,0.2,4.2,0.9,6.4,2.4c1.4,1,2.1,1.7,3,3v6.1
                        c-0.7-2.7-1.6-4.3-3.5-6.3c-1.9-2-3.4-2.9-5.9-3.8C144.2,39.8,143,39.6,140.9,39.6z"/>
                    <path fill="#FFFFFF" d="M134.8,39.6c-2-0.7-3.3-0.8-5.4-0.6c-2.7,0.3-4.2,0.9-6.4,2.4c-1.4,1-2.1,1.7-3,3v6.1
                        c0.7-2.7,1.6-4.3,3.5-6.3c1.9-2,3.4-2.9,5.9-3.8C131.5,39.8,132.7,39.6,134.8,39.6z"/>
                    <path fill="#FFFFFF" d="M137.7,15c0.6-4,1.4-6.1,3.3-8.3c1-1.2,1.8-1.5,3.2-2.4l0-4.3c-2.1,1.3-3.2,2.4-4.5,4.7
                        c-1.2,2.1-1.7,5.2-2,7.3c-0.3-2.1-0.8-5.2-2-7.3c-1.3-2.3-2.4-3.4-4.5-4.7l0,4.3c1.4,0.9,2.2,1.1,3.2,2.4
                        C136.3,8.9,137.1,11,137.7,15z"/>
                    <path fill="#FFFFFF" d="M137.7,21.9c0.8-2.1,1.4-3.2,2.6-5c1.8-2.7,3-4.1,5.6-6.1c2.2-1.7,3.6-2.5,6.2-3.4V2.7
                        c-2.4,0.9-3.2,1.8-5.1,3.3c-0.8,0.6-2.3,2.1-3.7,3.8c-1.5,1.8-2.8,4-3.3,5c-0.7,1.4-1.9,3.9-2.2,5.9c-0.3-2-1.6-4.5-2.2-5.9
                        c-0.5-1-1.8-3.1-3.3-5c-1.4-1.8-3-3.2-3.7-3.8c-1.9-1.5-2.6-2.4-5.1-3.3v4.8c2.5,0.9,4,1.7,6.2,3.4c2.5,1.9,3.8,3.4,5.6,6.1
                        C136.3,18.7,136.9,19.8,137.7,21.9z"/>
                    <path fill="#FFFFFF" d="M137.7,25.8c0.8-2,1.7-4.2,3.5-6.5c1.8-2.3,3.2-3.3,5.6-4.9c4.3-3,7.3-4.3,12.4-5.2v4.9
                        c-2.3,0.1-4.2,0.3-6.8,1c-3,0.8-4.5,1.6-7.3,3.2c-2.2,1.3-5.3,4.8-7.4,8.5c-2.1-3.7-5.3-7.3-7.4-8.5c-2.7-1.6-4.3-2.4-7.3-3.2
                        c-2.6-0.7-4.5-0.9-6.8-1V9.2c5.1,0.8,8.1,2.2,12.4,5.2c2.4,1.7,3.8,2.7,5.6,4.9C135.9,21.6,136.9,23.8,137.7,25.8z"/>
                    <path fill="#FFFFFF" d="M137.7,30.7c-3-3.4-6.5-6.1-8.9-7.3c-3.3-1.6-5.9-2.1-9.7-2c-2.6,0.1-6.5,1.2-6.5,1.2l0-5.6
                        c3.8,0,6.1,0.4,9.7,1.5c3.8,1.2,6,2.2,9.2,4.6c2.9,2.2,4.2,4.1,6.3,6.8c2.1-2.7,3.5-4.6,6.3-6.8c3.2-2.4,5.4-3.4,9.2-4.6
                        c3.7-1.2,5.9-1.5,9.7-1.5l0,5.6c0,0-3.9-1.1-6.5-1.2c-3.9-0.1-6.4,0.4-9.7,2C144.1,24.6,140.6,27.3,137.7,30.7z"/>
                    <path fill="#FFFFFF" d="M137.7,33.4c2.5-2.5,4-3.8,7.1-5.4c3.9-2.2,7.8-3.3,11-3.4c3.8-0.1,6.4,0.4,10.1,1.9v5.3
                        c-3-2.5-4.9-3.6-8.6-4.3c-4.7-0.9-7.7,0-12.1,1.8c-3.2,1.3-4.8,2.5-7.4,4.8c-2.6-2.4-4.3-3.5-7.5-4.8
                        c-4.4-1.8-7.4-2.7-12.1-1.8c-3.7,0.7-5.7,1.8-8.7,4.3l0-5.3c3.6-1.5,6.2-2,10.1-1.9c3.2,0.1,7.1,1.3,11,3.4
                        C133.6,29.7,135.2,30.9,137.7,33.4z"/>
                    <path fill="#FFFFFF" d="M137.7,39.2c1.9-1.7,3.7-2.9,5.5-3.4c3.6-1.1,7.3-0.2,9.4,0.4c2.8,0.8,7,3,9.9,6.7v5.7
                        c-1.4-2.3-2.4-3.5-4.3-5.3c-2.7-2.6-4.4-4.3-7.9-5.4c-3.3-1-5.7-1.4-8.9-0.2c-1.6,0.6-3.7,2.2-3.7,2.2
                        c-0.9-0.8-2.1-1.6-3.7-2.2c-3.3-1.2-5.6-0.8-8.9,0.2c-3.6,1.1-5.3,2.8-7.9,5.4c-1.9,1.9-2.9,3-4.3,5.3v-5.7
                        c2.9-3.7,7.1-5.9,9.9-6.7c2.1-0.6,5.9-1.6,9.4-0.4C134,36.4,135.8,37.6,137.7,39.2z"/>
                    <path fill="#FFFFFF" d="M137.7,36.5c2.3-2.4,3.9-3.5,6.9-4.7c5.1-2.1,9-1.8,14.2,0c3.8,1.3,5.9,2.6,8.7,5.5v6.9
                        c-1.6-2.7-2.7-4.1-5.1-6.2c-2.7-2.4-4.5-3.7-7.9-4.7c-3-0.9-7.5-1-10.8,0c-1.8,0.6-4,1.9-6.1,3.9c-2.1-2-4.3-3.3-6.1-3.9
                        c-3.3-1-7.7-0.9-10.8,0c-3.3,1-5.2,2.3-7.9,4.7c-2.3,2.1-3.5,3.5-5.1,6.2v-6.9c2.8-2.9,4.9-4.2,8.7-5.5c5.2-1.8,9.1-2.1,14.2,0
                        C133.8,33,135.3,34.1,137.7,36.5z"/>
                </g>
                <path fill="#FFFFFF" d="M137.6,98.6c3.2,0.1,5.3-0.2,8.6-1.2c2.8-0.8,4.5-1.5,6.9-3.2c0.9,0.9,1.1,1.2,2,1.9
                    c1.4,0.9,1.7,1.2,3.2,1.7v-2.2c-1.1-0.5-1.7-0.9-2.7-1.7c-1.1-0.8-1.6-1.4-2.4-2.5c-2.7,2.1-4.5,3.1-7.8,4.1
                    c-2.9,0.9-4.7,1.2-7.8,1.1h0h0h0c-3.1,0.1-4.9-0.2-7.8-1.1c-3.3-1-5.1-2-7.8-4.1c-0.8,1.1-1.3,1.7-2.4,2.5
                    c-1,0.8-1.6,1.2-2.7,1.7v2.2c1.5-0.6,1.9-0.8,3.2-1.7c1-0.7,1.2-1,2-1.9c2.4,1.7,4.1,2.3,6.9,3.2
                    C132.3,98.4,134.4,98.6,137.6,98.6z"/>
                <path fill="#FFFFFF" d="M137.6,82.2c2.7,0,4.5-0.4,6.9-1.6c2-1,3-1.9,4.4-3.6l10.1,1.4l4.5,7.7c1.1-0.9,1.7-1.4,2.3-2.7
                    c0.5-0.9,0.4-1.8,0.5-2.7c0-0.3,0.4-0.4,0.6-0.2s6.4,10.3,6.4,10.3c-1.3,1.5-2.3,2.1-4.1,2.9c-2.5,1.1-4.5,1.3-7.2,0.8
                    c-3.3-0.6-5.1-1.8-7-4.7l-5.8-10c-1.5,1.5-2.5,2.3-4.5,3.1c-1.5,0.7-4.1,1.4-7,1.3c-2.8-0.1-5.5-0.6-7-1.3
                    c-1.9-0.9-3-1.6-4.5-3.1l-5.8,10c-1.9,2.8-3.7,4-7,4.7c-2.7,0.5-4.7,0.2-7.2-0.8c-1.8-0.8-2.8-1.4-4.1-2.9c0,0,6.2-10,6.4-10.3
                    s0.5-0.2,0.6,0.2c0.1,0.9,0,1.7,0.5,2.7c0.6,1.3,1.2,1.8,2.3,2.7l4.5-7.7l10.1-1.4c1.4,1.8,2.4,2.6,4.4,3.6
                    C133.1,81.8,134.9,82.2,137.6,82.2C137.6,82.2,137.6,82.2,137.6,82.2z M123.2,79.4l-5.7,0.8l-3.9,6.8c1.1,0.5,1.9,0.5,3.2,0.4
                    c1.5-0.2,2.9-0.5,3.7-1.8c0,0,3.2-5.4,3.2-5.4C124,79.3,123.2,79.4,123.2,79.4z M118.4,88.9c-3,0.9-5,0.4-7-0.7
                    c-1.6-0.9-2.5-1.7-3.4-3.4l-3.3,5.3c1.3,1.4,3,2.1,4.9,2.3C113.3,92.9,115.7,92.2,118.4,88.9z M151.6,80.1c0,0,3.2,5.4,3.2,5.4
                    c0.8,1.3,2.2,1.6,3.7,1.8c1.2,0.2,2.1,0.2,3.2-0.4l-3.9-6.8l-5.7-0.8C152,79.4,151.2,79.3,151.6,80.1z M165.5,92.5
                    c1.9-0.2,3.6-0.9,4.9-2.3l-3.3-5.3c-0.9,1.6-1.7,2.5-3.4,3.4c-2,1.1-4,1.6-7,0.7C159.4,92.2,161.9,92.9,165.5,92.5z"/>
                <g>
                    <path fill="#FFFFFF" d="M174.4,80.4c1.3,0,2.3,1,2.3,2.3c0,1.3-1,2.3-2.3,2.3c-1.3,0-2.3-1-2.3-2.3
                        C172.1,81.4,173.1,80.4,174.4,80.4L174.4,80.4z M174.4,80.7c-1,0-1.9,0.9-1.9,2c0,1.1,0.8,2,1.9,2c1,0,1.9-0.9,1.9-2
                        C176.2,81.6,175.4,80.7,174.4,80.7L174.4,80.7z M173.9,84h-0.4l0-2.6c0.2,0,0.4-0.1,0.7-0.1c0.4,0,0.7,0.1,0.8,0.2
                        c0.1,0.1,0.2,0.3,0.2,0.5c0,0.3-0.2,0.6-0.5,0.6v0c0.2,0,0.4,0.2,0.4,0.6c0.1,0.4,0.1,0.6,0.2,0.6H175
                        c-0.1-0.1-0.1-0.3-0.2-0.7c-0.1-0.3-0.2-0.5-0.6-0.5l-0.3,0L173.9,84z M173.9,82.6h0.3c0.3,0,0.6-0.1,0.6-0.4
                        c0-0.2-0.2-0.5-0.6-0.5c-0.1,0-0.2,0-0.3,0V82.6z"/>
                </g>
            </g>
        </g>
    </g>
        </switch>
    <h1 class="col-xs-12">Forgot your password?</h1>
    <div id="login-form" class="col-xs-6 col-xs-offset-3 col-md-6 col-md-offset-3 col-lg-2 col-lg-offset-5">
    <form action="" id="forgotpassword" name="forgotpasswordform" method="POST">
        <label id="error" class="text-center"> <?php foreach ($message as $value)echo "<span class=\"icon\">&#xe063;</span> ".$value; ?> </label>
        <input class="col-xs-12" name="email" type="email" placeholder="Enter your Email" required>
        <input type="submit" name="forgot" class ="col-xs-12" value="Retrieve Password" >
        <a href="login.php" id="login-link" class="pull-right">Return to Login ? </a>
    </form>
    </div>

    </div>
</div>



<?php
require_once("../Resources/Includes/footer.php");
?>
<script src="../Resources/Library/js/login.js"></script>