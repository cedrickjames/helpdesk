
 <?php 
 $timeout = 3600;

 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
 

    ini_set( "session.gc_maxlifetime", $timeout );

    ini_set( "session.cookie_lifetime", $timeout );

    $s_name = session_name();
    $url1=$_SERVER['REQUEST_URI'];
    header("Refresh: 500; URL=$url1");

    if(isset( $_COOKIE[ $s_name ] )) {
    
        setcookie( $s_name, $_COOKIE[ $s_name ], time() + $timeout, '/' );

    }
 
 else
 
     echo "Session is expired.<br/>";
  

     session_start();
    include ("../includes/connect.php");


   $misusername =  $_SESSION['username'];

    if(!isset($_SESSION['connected'])){
        header("location: ../index.php");
      }
      
        $user_dept = $_SESSION['department'];
        $user_level=$_SESSION['level'];

    


        if(isset($_POST['approveRequest'])){
            $requestID = $_POST['joid2'];
            $completejoid = $_POST['completejoid'];

            $action = $_POST['action'];
            $requestor = $_POST['requestor'];
            $requestorEmail = $_POST['requestoremail'];

            

            $sql1 = "Select * FROM `request` WHERE `id` = '$requestID'";
            $result = mysqli_query($con, $sql1);
            while($list=mysqli_fetch_assoc($result))
            {
            $requestorUsername=$list["requestorUsername"];
            $email=$list["email"];
            $requestor=$list["requestor"];

    
            }

            $date = date("Y-m-d");
            $username = $_SESSION['name'];
            $sql = "UPDATE `request` SET `status`='Done',`status2`='Done',`actual_finish_date`='$date',`action`='$action' WHERE `id` = '$requestID';";
               $results = mysqli_query($con,$sql);
  
               if($results){
                $sql2 = "Select * FROM `sender`";
                $result2 = mysqli_query($con, $sql2);
                while($list=mysqli_fetch_assoc($result2))
                {
                $account=$list["email"];
                $accountpass=$list["password"];
        
                  }    

                $subject ='Completed Job Order';
                $message = 'Hi '.$requestor.',<br> <br> MIS has completed one of your job order requests. Please check the details by signing in into our Helpdesk <br> Click this http://192.168.60.53/helpdesk to signin. <br><br><br> This is a generated email. Please do not reply. <br><br> Helpdesk';
                

                 require '../vendor/autoload.php';
    
                 $mail = new PHPMailer(true);       
                //  email the admin               
                 try {
                  //Server settings
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'mail.glory.com.ph';                       // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = $account;     // Your Email/ Server Email
                    $mail->Password = $accountpass;                     // Your Password
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                        )
                                                );                         
                    $mail->SMTPSecure = 'none';                           
                    $mail->Port = 25;                                   
            
                    //Send Email
                    // $mail->setFrom('Helpdesk'); //eto ang mag front  notificationsys01@gmail.com
                    
                    //Recipients
                    $mail->setFrom('mis.dev@glory.com.ph', 'Helpdesk');
                    $mail->addAddress($email);              
                    $mail->isHTML(true);                                  
                    $mail->Subject = $subject;
                    $mail->Body    = $message;
            
                    $mail->send();

                    
                          $_SESSION['message'] = 'Message has been sent';
                          echo "<script>alert('Job order completed.') </script>";
                          echo "<script> location.href='index.php'; </script>";
      

                        // header("location: form.php");
                    } catch (Exception $e) {
                        $_SESSION['message'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
                    echo "<script>alert('Message could not be sent. Mailer Error.') </script>";

                    }

               
               }
          
          
          // end of sending email
          
          
          
            }
          
          
          
          
          
          
            if(isset($_POST['cancelJO'])){
                $joid = $_POST['joid2'];
                $reasonCancel = $_POST['reasonCancel'];
                $requestorEmail = $_POST['requestoremail'];
                $requestor = $_POST['requestor'];
                $completejoid = $_POST['completejoid'];
                $sql = "UPDATE `request` SET `status2`='cancelled', `reasonOfCancellation`='$reasonCancel' WHERE `id` = '$joid';";
                $results = mysqli_query($con,$sql);
                if($results){
                    $sql2 = "Select * FROM `sender`";
                    $result2 = mysqli_query($con, $sql2);
                    while($list=mysqli_fetch_assoc($result2))
                    {
                    $account=$list["email"];
                    $accountpass=$list["password"];
            
                      }    
    
                     require '../vendor/autoload.php';
        
                     $mail = new PHPMailer(true);       
                    //  email the admin               
                     try {
                      //Server settings
                       
                        $subject2 ='Cancelled Job Order';
                        $message2 = 'Hi '.$requestor.',<br> <br>  Your Job Order with JO number of '.$completejoid.' is CANCELLED by the administrator. Please check the details by signing in into our Helpdesk <br> Click this http://192.168.60.53/helpdesk to signin. <br><br><br> This is a generated email. Please do not reply. <br><br> Helpdesk';
                        
                        // email this requestor
                
                            //Server settings
                              $mail->isSMTP();                                      // Set mailer to use SMTP
                              $mail->Host = 'mail.glory.com.ph';                       // Specify main and backup SMTP servers
                              $mail->SMTPAuth = true;                               // Enable SMTP authentication
                              $mail->Username = $account;     // Your Email/ Server Email
                              $mail->Password = $accountpass;                     // Your Password
                              $mail->SMTPOptions = array(
                                  'ssl' => array(
                                  'verify_peer' => false,
                                  'verify_peer_name' => false,
                                  'allow_self_signed' => true
                                  ));  

                              $mail->SMTPSecure = 'none';                           
                              $mail->Port = 25;                                   
                      
                              //Send Email
                              // $mail->setFrom('Helpdesk'); //eto ang mag front  notificationsys01@gmail.com
                              
                              //Recipients
                              $mail->setFrom('mis.dev@glory.com.ph', 'Helpdesk');
                              $mail->addAddress($requestorEmail);              
                              $mail->isHTML(true);                                  
                              $mail->Subject = $subject2;
                              $mail->Body    = $message2;
                      
                              $mail->send();
                              $_SESSION['message'] = 'Message has been sent';
                              echo "<script>alert('The request was successfully cancelled.') </script>";
                              echo "<script> location.href='index.php'; </script>";
          
    
                            // header("location: form.php");
                        } catch (Exception $e) {
                            $_SESSION['message'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
                        echo "<script>alert('Message could not be sent. Mailer Error.') </script>";
    
                        }
    
                   
                   }
                
                }
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FEM MIS Helpdesk</title>

    <link rel="stylesheet" href="../fontawesome-free-6.2.0-web/css/all.min.css">

    <link rel="stylesheet" href="../node_modules/DataTables/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../node_modules/DataTables/Responsive-2.3.0/css/responsive.dataTables.min.css"/>

    <link rel="stylesheet" href="index.css">

    <script src="../cdn_tailwindcss.js"></script>

    <link rel="stylesheet" href="../node_modules/flowbite/dist/flowbite.min.css" />
   
</head>
    <body   class="static  bg-white dark:bg-gray-900"  >
    <?php require_once 'nav.php';?>

<div class=" ml-72 flex mt-16  left-10 right-5  flex-col  px-14 sm:px-8  pt-6 pb-14 z-50 ">
    <div
        class="justify-center text-center flex items-start h-auto bg-gradient-to-r from-blue-900 to-teal-500 rounded-xl ">
        <div class="text-center py-2 m-auto lg:text-center w-full">

            <div class="m-auto flex flex-col w-2/4  h-12">
                <h2 class="text-xl font-bold tracking-tight text-gray-100 sm:text-xl">Total numbers of pending Job Order
                </h2>

            </div>


            <div class="m-auto flex flex-col w-2/4">

                <div class="mt-0 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 ">

                    <div class="flex items-start rounded-xl bg-teal-700 dark:bg-white p-4 shadow-lg">
                        <div
                            class="flex h-12 w-12 overflow-hidden items-center justify-center rounded-full border border-red-100 bg-red-50">
                            <img src="../resources/img/Engineer.png" class="h-full w-full text-blue-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                        </div>

                        <div class="ml-3">
                            <h2 class="font-semibold text-gray-100 dark:text-gray-900">FEM Pending</h2>
                            <p class="mt-2 text-xl text-left text-gray-100"><?php 
                                        $sql1 = "SELECT COUNT(id) as 'pending' FROM request WHERE request_to = 'fem' AND status2 = 'inprogress'";
                                        $result = mysqli_query($con, $sql1);
                                        while($count=mysqli_fetch_assoc($result))
                                        {
                                        echo $count["pending"];
                                      
                                        }
                            ?></p>
                        </div>
                    </div>
                    <div class="flex items-start rounded-xl bg-sky-900 dark:bg-white p-4 shadow-lg">
                        <div
                            class="flex h-12 w-12 items-center overflow-hidden  justify-center rounded-full border border-indigo-100 bg-indigo-50">
                            <img src="../resources/img/itboy.png" class="h-full w-full text-blue-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                        </div>

                        <div class="ml-3">
                            <h2 class="font-semibold text-gray-100 dark:text-gray-900">MIS Pending</h2>
                            <p class="mt-2 text-xl text-left text-gray-100"><?php 
                                        $sql1 = "SELECT COUNT(id) as 'pending' FROM request WHERE request_to = 'mis' AND status2 = 'inprogress'";
                                        $result = mysqli_query($con, $sql1);
                                        while($count=mysqli_fetch_assoc($result))
                                        {
                                        echo $count["pending"];
                                      
                                        }
                            ?></p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="FrD3PA">
                <div class="QnQnDA" tabindex="-1">
                    <div role="tablist" class="_6TVppg sJ9N9w">
                        <div class="uGmi4w">
                            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400"
                                id="tabExample" role="tablist">
                                <li role="presentation">
                                    <div class="p__uwg" style="width: 106px; margin-right: 0px;">
                                        <button id="headApprovalTab" onclick="goToHead()" type="button" role="tab"
                                            aria-controls="headApproval"
                                            class="_1QoxDw o4TrkA CA2Rbg Di_DSA cwOZMg zQlusQ uRvRjQ POMxOg _lWDfA"
                                            aria-selected="false">
                                            <div class="_1cZINw">
                                                <div class="_qiHHw Ut_ecQ kHy45A">

                                                    <img src="../resources/img/list.png"
                                                        class="h-full w-full text-blue-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                                                </div>
                                            </div>
                                            <p class="_5NHXTA _2xcaIA ZSdr0w CCfw7w GHIRjw">My Job Order</p>
                                        </button></div>
                                </li>
                                <li role="presentation">
                                    <div class="p__uwg" style="width: 106px; margin-right: 0px;">
                                        <button id="overallTab" onclick="goToOverall()" type="button" role="tab"
                                            aria-controls="overall"
                                            class="_1QoxDw o4TrkA CA2Rbg Di_DSA cwOZMg zQlusQ uRvRjQ POMxOg _lWDfA"
                                            aria-selected="false">
                                            <div class="_1cZINw">
                                                <div class="_qiHHw Ut_ecQ kHy45A">

                                                <span class="gkK1Zg jxuDbQ"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"><path fill="currentColor" d="M24 0C10.7 0 0 10.7 0 24s10.7 24 24 24 24-10.7 24-24S37.3 0 24 0zM11.9 15.2c.1-.1.2-.1.2-.1 1.6-.5 2.5-1.4 3-3 0 0 0-.1.1-.2l.1-.1c.1 0 .2-.1.3-.1.4 0 .5.3.5.3.5 1.6 1.4 2.5 3 3 0 0 .1 0 .2.1s.1.2.1.3c0 .4-.3.5-.3.5-1.6.5-2.5 1.4-3 3 0 0-.1.3-.4.3-.6.1-.7-.2-.7-.2-.5-1.6-1.4-2.5-3-3 0 0-.4-.1-.4-.5l.3-.3zm24.2 18.6c-.5.2-.9.6-1.3 1s-.7.8-1 1.3c0 0 0 .1-.1.2-.1 0-.1.1-.3.1-.3-.1-.4-.4-.4-.4-.2-.5-.6-.9-1-1.3s-.8-.7-1.3-1c0 0-.1 0-.1-.1-.1-.1-.1-.2-.1-.3 0-.3.2-.4.2-.4.5-.2.9-.6 1.3-1s.7-.8 1-1.3c0 0 .1-.2.4-.2.3 0 .4.2.4.2.2.5.6.9 1 1.3s.8.7 1.3 1c0 0 .2.1.2.4 0 .4-.2.5-.2.5zm-.7-8.7s-4.6 1.5-5.7 2.4c-1 .6-1.9 1.5-2.4 2.5-.9 1.5-2.2 5.4-2.2 5.4-.1.5-.5.9-1 .9v-.1.1c-.5 0-.9-.4-1.1-.9 0 0-1.5-4.6-2.4-5.7-.6-1-1.5-1.9-2.5-2.4-1.5-.9-5.4-2.2-5.4-2.2-.5-.1-.9-.5-.9-1h.1-.1c0-.5.4-.9.9-1.1 0 0 4.6-1.5 5.7-2.4 1-.6 1.9-1.5 2.4-2.5.9-1.5 2.2-5.4 2.2-5.4.1-.5.5-.9 1-.9s.9.4 1 .9c0 0 1.5 4.6 2.4 5.7.6 1 1.5 1.9 2.5 2.4 1.5.9 5.4 2.2 5.4 2.2.5.1.9.5.9 1h-.1.1c.1.5-.2.9-.8 1.1z"></path></svg></span>

                                                </div>
                                            </div>
                                            <p class="_5NHXTA _2xcaIA ZSdr0w CCfw7w GHIRjw">Overall</p>
                                        </button></div>
                                </li>
                                <li role="presentation">
                    <div class="p__uwg" style="width: 96px; margin-left: 16px; margin-right: 0px;">
                    <button id="toRateTab" onclick="goToRate()"
                        class="_1QoxDw o4TrkA CA2Rbg cwOZMg zQlusQ uRvRjQ POMxOg" tabindex="-1" type="button" role="tab" aria-controls="toRate"
                        aria-selected="false">
                        <div class="_1cZINw">
                        <div class="_qiHHw Ut_ecQ kHy45A">

                        <img src="../resources/img/star.png" class="h-full w-full text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                        </div>
                        </div>
                        <p class="_5NHXTA _2xcaIA ZSdr0w CCfw7w GHIRjw">Rates</p>
                    </button></div>
                    </li>
                            </ul>
                        </div>
                        <div class="rzHaWQ theme light" id="diamond"
                            style="transform: translateX(55px) translateY(2px) rotate(135deg);"></div>
                    </div>
                </div>
            </div>
            <div class="hidden">
                <ul
                    class="uGmi4w  mb-1 m-4 flex text-sm font-medium text-center text-gray-500 divide-x divide-gray-200 rounded-lg shadow  dark:divide-gray-700 dark:text-gray-400">
                    <li class="w-full relative">
                        <a href="#"
                            class="inline-block w-full p-4 text-gray-900 bg-gray-100 rounded-l-lg focus:ring-4 focus:ring-blue-300 active focus:outline-none dark:bg-gray-700 dark:text-white"
                            aria-current="page">For Approval</a>
                        <div class="rzHaWQ theme light"
                            style="transform: translateX(198px) translateY(2px) rotate(135deg);"></div>

                    </li>
                    <li class="w-full">
                        <a href="#"
                            class="inline-block w-full p-4 bg-white hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">Dashboard</a>
                    </li>
                    <li class="w-full">
                        <a href="#"
                            class="inline-block w-full p-4 bg-white hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">Settings</a>
                    </li>
                    <li class="w-full">
                        <a href="#"
                            class="inline-block w-full p-4 bg-white rounded-r-lg hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">Invoice</a>
                    </li>

                </ul>

            </div>

        </div>
    </div>



    <div id="myTabContent">
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="headApproval" role="tabpanel"
            aria-labelledby="profile-tab">
            <section class="mt-10">
                <table id="employeeTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>JO Number</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>Date Filed</th>
                            <th>Category</th>
                            <th>Assigned to</th>
                        </tr>
                    </thead>
                    <tbody>
              <?php
                $a=1;

                  $sql="select * from `request` WHERE `status2` ='inprogress' and `assignedPersonnel` = '$misusername' order by id asc  ";
                  $result = mysqli_query($con,$sql);

                while($row=mysqli_fetch_assoc($result)){
                  ?>
              <tr>
              <td>
              <?php 
              $date = new DateTime($row['date_filled']);
              $date = $date->format('ym');
              echo $date.'-'.$row['id'];?> 
             
              <td>
                    <!-- <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Select</a> -->
                    <button type="button" id="viewdetails" onclick="modalShow(this)" data-telephone="<?php echo $row['telephone']; ?>" data-attachment="<?php echo $row['attachment']; ?>" data-action="<?php echo $row['action']; ?>" data-joidprint="<?php $date = new DateTime($row['date_filled']); $date = $date->format('ym');  echo $date.'-'.$row['id']; ?>" data-headremarks="<?php echo $row['head_remarks']; ?>" data-adminremarks="<?php echo $row['admin_remarks']; ?>"  data-joid="<?php echo $row['id']; ?>" data-requestoremail="<?php echo $row['email']; ?>"  data-requestor="<?php echo $row['requestor']; ?>"  data-datefiled="<?php $date = new DateTime($row['date_filled']); $date = $date->format('F d, Y');echo $date;?>" data-section="<?php if($row['request_to'] == "fem"){  echo "FEM";} else if($row['request_to'] == "mis"){ echo "MIS";}?>" data-category="<?php echo $row['request_category'];?>" data-comname="<?php echo $row['computerName']; ?>" data-start="<?php echo $row['reqstart_date']; ?>" data-end="<?php echo $row['reqfinish_date']; ?>" data-details="<?php echo $row['request_details']; ?>" class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"> 
                    View more
                    </button>
                </td>

              <td class="text-sm text-red-700 font-light px-6 py-4 whitespace-nowrap truncate max-w-xs">
              <?php echo $row['request_details'];?> 
              </td>


              <!-- to view pdf -->
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php 
              $date = new DateTime($row['date_filled']);
              $date = $date->format('F d, Y');
              echo $date;?> 
              
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php echo $row['request_category'];?> 
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">

              <?php if($row['request_to'] == "fem"){
                echo "FEM";}
                else if($row['request_to'] == "mis"){
                echo "MIS";
                }
                ?> 
              </td>



              




                </tr>
                  <?php

            }
               ?>
          </tbody>
                </table>

            </section>
        </div>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="overall" role="tabpanel"
            aria-labelledby="profile-tab">
            <section class="mt-10">
                <table id="overAllTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>JO Number</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>Date Filed</th>
                            <th>Category</th>
                            <th>Assigned to</th>
                        </tr>
                    </thead>
                    <tbody>
              <?php
                $a=1;

                  $sql="select * from `request` WHERE `status2` ='inprogress' and `request_to` = 'mis' order by id asc  ";
                  $result = mysqli_query($con,$sql);

                while($row=mysqli_fetch_assoc($result)){
                  ?>
              <tr class="">
              <td class="">
              <?php 
              $date = new DateTime($row['date_filled']);
              $date = $date->format('ym');
              echo $date.'-'.$row['id'];?> 
             
              <td >
                    <!-- <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Select</a> -->
                    <button type="button" id="viewdetails" onclick="modalShow(this)" data-telephone="<?php echo $row['telephone']; ?>" data-attachment="<?php echo $row['attachment']; ?>" data-joidprint="<?php $date = new DateTime($row['date_filled']); $date = $date->format('ym');  echo $date.'-'.$row['id']; ?>" data-headremarks="<?php echo $row['head_remarks']; ?>" data-action="<?php echo $row['action']; ?>"  data-adminremarks="<?php echo $row['admin_remarks']; ?>"  data-joid="<?php echo $row['id']; ?>" data-requestoremail="<?php echo $row['email']; ?>"  data-requestor="<?php echo $row['requestor']; ?>"  data-datefiled="<?php $date = new DateTime($row['date_filled']); $date = $date->format('F d, Y');echo $date;?>" data-section="<?php if($row['request_to'] == "fem"){  echo "FEM";} else if($row['request_to'] == "mis"){ echo "MIS";}?>" data-category="<?php echo $row['request_category'];?>" data-comname="<?php echo $row['computerName']; ?>" data-start="<?php echo $row['reqstart_date']; ?>" data-end="<?php echo $row['reqfinish_date']; ?>" data-details="<?php echo $row['request_details']; ?>" class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"> 
                    View more
                    </button>
                </td>

              <td class="text-sm text-red-700 font-light px-6 py-4 whitespace-nowrap truncate max-w-xs">
              <?php echo $row['request_details'];?> 
              </td>


              <!-- to view pdf -->
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php 
              $date = new DateTime($row['date_filled']);
              $date = $date->format('F d, Y');
              echo $date;?> 
              
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php echo $row['request_category'];?> 
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">

              <?php if($row['request_to'] == "fem"){
                echo "FEM";}
                else if($row['request_to'] == "mis"){
                echo "MIS";
                }
                ?> 
              </td>



              




                </tr>
                  <?php

            }
               ?>
          </tbody>
                </table>

            </section>
        </div>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="forRating" role="tabpanel"
            aria-labelledby="profile-tab">
            <section class="mt-10">
                <table id="forRatingTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>JO Number</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>Date Filed</th>
                            <th>Comments</th>
                            <th>Ratings</th>
                        </tr>
                    </thead>
                    <tbody>
              <?php
                $a=1;

                  $sql="select * from `request` WHERE `status2` ='rated' OR `status2` = 'Done'  and `assignedPersonnel` = '$misusername' order by id asc  ";
                  $result = mysqli_query($con,$sql);

                while($row=mysqli_fetch_assoc($result)){
                  ?>
              <tr class="">
              <td class="">
              <?php 
              $date = new DateTime($row['date_filled']);
              $date = $date->format('ym');
              echo $date.'-'.$row['id'];?> 
             
              <td >
                    <!-- <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Select</a> -->
                    <button type="button" id="viewdetails" onclick="modalShow(this)" data-telephone="<?php echo $row['telephone']; ?>" data-attachment="<?php echo $row['attachment']; ?>" data-action="<?php echo $row['action']; ?>" data-joidprint="<?php $date = new DateTime($row['date_filled']); $date = $date->format('ym');  echo $date.'-'.$row['id']; ?>" data-headremarks="<?php echo $row['head_remarks']; ?>" data-adminremarks="<?php echo $row['admin_remarks']; ?>"  data-joid="<?php echo $row['id']; ?>" data-requestoremail="<?php echo $row['email']; ?>"  data-requestor="<?php echo $row['requestor']; ?>"  data-datefiled="<?php $date = new DateTime($row['date_filled']); $date = $date->format('F d, Y');echo $date;?>" data-section="<?php if($row['request_to'] == "fem"){  echo "FEM";} else if($row['request_to'] == "mis"){ echo "MIS";}?>" data-category="<?php echo $row['request_category'];?>" data-comname="<?php echo $row['computerName']; ?>" data-start="<?php echo $row['reqstart_date']; ?>" data-end="<?php echo $row['reqfinish_date']; ?>" data-details="<?php echo $row['request_details']; ?>" class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"> 
                    View more
                    </button>
                </td>

              <td class="text-sm text-red-700 font-light px-6 py-4 whitespace-nowrap truncate max-w-xs">
              <?php echo $row['request_details'];?> 
              </td>


              <!-- to view pdf -->
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php 
              $date = new DateTime($row['date_filled']);
              $date = $date->format('F d, Y');
              echo $date;?> 
              
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php echo $row['requestor_remarks'];?> 
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">

              <?php echo $row['rating_final']
                ?> 
              </td>



              




                </tr>
                  <?php

            }
               ?>
          </tbody>
                </table>

            </section>
        </div>
    </div>




</div>





<!-- Main modal -->
<div id="defaultModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <form action="" method="POST">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Job Order Details
                </h3>
                <button  onclick="modalHide()"type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class=" items-center p-6 space-y-2">
            <input type="text" name="requestor" id="requestorinput" class="hidden col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <input type="text" name="requestoremail" id="requestoremailinput" class="hidden col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <input type="text" name="completejoid" id="completejoid" class="hidden col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
           
            <input type="text" name="joid2" id="joid2" class="hidden col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">


            <div class="w-full grid gap-4 grid-cols-2">
                     <h2 class="font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Requestor : </span><span id="requestor"></span></h2>
                     <h2 class="pl-10 font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Email: </span><span id="requestorEmail"></span></h2>
         
                </div>
                <div class="w-full grid gap-4 grid-cols-2">
                     <h2 class="font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">JO Number : </span><span id="jonumber"></span></h2>
                    <h2 class="pl-10 font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Date filed: </span><span id="datefiled"></span></h2>
                </div>
                <div class="w-full grid gap-4 grid-cols-2">
                     <h2 class="font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Requested Section: </span><span id="sectionmodal"></span></h2>
                     <h2  class="pl-10 font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Type: </span><span id="category"></span></h2>
                </div>
                <div class="w-full grid gap-4 grid-cols-2">
                <div id="categoryDivParent" class="grid gap-4 grid-cols-2">
                <h2 class="float-left font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Computer Name: </span></h2>
                <input disabled type="text" name="computername" id="computername"class="col-span-1 bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    
                </div>
                     <div class="grid gap-4 grid-cols-2">
                <h2 id="telephoneh2" class="pl-10 float-left font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Telephone</span></h2>
                <input disabled type="text" name="telephone" id="telephone"class="col-span-1 bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    
                </div>
                </div>
                <a type="button" name="attachment" id="attachment" class="shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80  w-full text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">View Attachment</a>

                <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <div>
                    <div class="grid grid-cols-3">
                        <h2 class=" py-4 col-span-1 font-semibold text-gray-400 dark:text-gray-400"><span
                                class="inline-block align-middle">Requested Schedule: </span></h2>
                        <div class="col-span-2 flex items-center">
                            <div class="relative">
                                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input disabled  id="datestart" onchange="testDate()" name="start" type="date"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 datepicker-input"
                                    placeholder="Request date start" required="">
                            </div>
                            <span class="mx-4 text-gray-500">to</span>
                            <div class="relative">
                                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input disabled id="datefinish" onchange="endDate()"  name="finish" type="date"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 datepicker-input"
                                    placeholder="Request date finish" required="">
                            </div>
                        </div>
                    </div>

                </div>
                <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <div class="w-full grid gap-4 grid-col-1">
                     <h2 class="font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Head Remarks: </span><span id="headremarks"></span></h2>
                </div>
                <div class="w-full grid gap-4 grid-col-1">
                     <h2 class="font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Admin Remarks: </span><span id="adminremarks"></span></h2>
                </div>
        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <label for="message" class="py-4 col-span-1 font-semibold text-gray-400 dark:text-gray-400">Request Details</label>
                <textarea disabled id="message" name="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" > </textarea>
                <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <label for="message" class="py-4 col-span-1 font-semibold text-gray-400 dark:text-gray-400">Action</label>
                <textarea required id="action" name="action" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="State your action..."> </textarea>
            </div>
            <div id="buttonDiv" class=" items-center p-4 border-t border-gray-200 rounded-b dark:border-gray-600">
            <button type="button" data-modal-target="popup-modal-approve" data-modal-toggle="popup-modal-approve" class="shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80  w-full text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Done</button>
            <button type="button"  onclick="cancellation()" data-modal-target="popup-modal-cancel" data-modal-toggle="popup-modal-cancel"  class="shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-pink-800/80  w-full text-white bg-gradient-to-br from-red-400 to-pink-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-red-200 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Cancel Request</button>
     
            </div>
            


            <div id="popup-modal-cancel" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button"  onclick="exitcancellation()" data-modal-toggle="popup-modal-cancel" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" >
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            
            <div class="p-6 text-center">
            <br>
              <br><br>
              <br><br>
              <br>   <br>
              <br><br>
              <br>
              <br>
              <br>
                <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">If you're sure about canceling, please give a reason.</h3>
                <textarea  id="reasonCancel" name="reasonCancel" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a reason..."></textarea>
              <br>
              <br>

                <button type="submit" name="cancelJO"  class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Submit
                </button>
                <button  onclick="exitcancellation()" data-modal-toggle="popup-modal-cancel" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Exit</button>
                <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br><br>
              <br>    <br>
              <br>
              <br>
            </div>
        </div>
    </div>
</div>
<div id="popup-modal-approve" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-md md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" data-modal-toggle="popup-modal-approve" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" >
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-6 text-center">
                <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to approve this request?</h3>
                <button type="submit" name="approveRequest" class="text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Yes, I'm sure
                </button>
                <button data-modal-toggle="popup-modal-approve" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
            </div>
        </div>
    </div>
</div>
        </form>
            
        </div>
    </div>
</div>

<script src="../node_modules/flowbite/dist/flowbite.js"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
<script type="text/javascript" src="../node_modules/DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="../node_modules/DataTables/Responsive-2.3.0/js/dataTables.responsive.min.js"></script>

    <script type="text/javascript" src="index.js"></script>

<script>

    
function cancellation(){
    document.getElementById("reasonCancel").required = true;
    document.getElementById("action").required = false;

    
}
function exitcancellation(){
    document.getElementById("reasonCancel").required = false;
    document.getElementById("action").required = true;

}
// set the modal menu element
const $targetElModal = document.getElementById('defaultModal');

// options with default values
const optionsModal = {
  placement: 'center-center',
  backdrop: 'dynamic',
  backdropClasses: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40',
  closable: true,
  onHide: () => {
      console.log('modal is hidden');
  },
  onShow: () => {
      console.log('modal is shown');

    //   console.log(section);
  },
  onToggle: () => {
      console.log('modal has been toggled');

  }
};
const modal = new Modal($targetElModal, optionsModal);

function modalShow(element){
    document.getElementById("joid2").value =element.getAttribute("data-joid");
    document.getElementById("jonumber").innerHTML =element.getAttribute("data-joidprint");
    document.getElementById("completejoid").value =element.getAttribute("data-joidprint");
    document.getElementById("headremarks").innerHTML =element.getAttribute("data-headremarks");
    document.getElementById("adminremarks").innerHTML =element.getAttribute("data-adminremarks");
    document.getElementById("telephone").value =element.getAttribute("data-telephone");
    document.getElementById("attachment").setAttribute("href", element.getAttribute("data-attachment"));
    document.getElementById("requestor").innerHTML =element.getAttribute("data-requestor");
    document.getElementById("requestorEmail").innerHTML =element.getAttribute("data-requestoremail");

    document.getElementById("requestorinput").value =element.getAttribute("data-requestor");
    document.getElementById("requestoremailinput").value =element.getAttribute("data-requestoremail");
    document.getElementById("action").value =element.getAttribute("data-action");





    
    document.getElementById("datefiled").innerHTML =element.getAttribute("data-datefiled");
    document.getElementById("sectionmodal").innerHTML =element.getAttribute("data-section");
    document.getElementById("category").innerHTML =element.getAttribute("data-category");
    document.getElementById("computername").value =element.getAttribute("data-comname");
    document.getElementById("datestart").value =element.getAttribute("data-start");
    document.getElementById("datefinish").value =element.getAttribute("data-end");
    document.getElementById("message").value =element.getAttribute("data-details");
       

   
    var category = element.getAttribute("data-category");
    var attachment = element.getAttribute("data-attachment");

    if(attachment == ""){
        $("#attachment").addClass("hidden");

    }
    else{
        $("#attachment").removeClass("hidden");
    }
    if(category !="Computer"){
        // $("#categoryDivParent").removeClass("grid-cols-2").addClass("grid-col-1");
        $("#categoryDivParent").addClass("hidden");
        $("#telephoneh2").removeClass("pl-10");

    }
    else{
        
        $("#categoryDivParent").removeClass("hidden");
        $("#telephoneh2").addClass("pl-10");

       }

        modal.toggle();
}
function modalHide(){
    modal.toggle();

}


const $targetEl = document.getElementById('sidebar');

const options = {
  placement: 'left',
  backdrop: false,
  bodyScrolling: true,
  edge: false,
  edgeOffset: '',
  backdropClasses: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-30',
  onHide: () => {
      console.log('drawer is hidden');
  },
  onShow: () => {
      console.log('drawer is shown');
  },
  onToggle: () => {
      console.log('drawer has been toggled');
  }
};

const drawer = new Drawer($targetEl, options);
drawer.show();
var show = true;
function shows(){
    if(show){
        drawer.hide();
        show = false;
    }
    else{
        drawer.show();
        show = true;
    }


}


const tabElements= [
    {
        id: 'headApproval1',
        triggerEl: document.querySelector('#headApprovalTab'),
        targetEl: document.querySelector('#headApproval')
    },
    {
        id: 'overall',
        triggerEl: document.querySelector('#overallTab'),
        targetEl: document.querySelector('#overall')
    },
    {
        id: 'forRating',
        triggerEl: document.querySelector('#toRateTab'),
        targetEl: document.querySelector('#forRating')
    },
];


const taboptions = {
    defaultTabId: 'headApproval1',
    activeClasses: 'text-white hover:text-amber-400 dark:text-blue-500 dark:hover:text-blue-400 border-blue-600 dark:border-blue-500',
    inactiveClasses: 'text-gray-300 hover:text-amber-500 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300',
    onShow: () => {
        console.log('tab is shown');
    }
};


const tabs = new Tabs(tabElements, taboptions);

tabs.show('headApproval1');



function goToOverall(){
    const myElement = document.querySelector('#diamond');
    $("#buttonDiv").addClass("hidden");
    document.getElementById("action").disabled = true;

const currentTransform = myElement.style.transform = 'translateX(160px) translateY(2px) rotate(135deg)';


}

function goToMis(){
    const myElement = document.querySelector('#diamond');


const currentTransform = myElement.style.transform = 'translateX(300px) translateY(2px) rotate(135deg)';



}
function goToRate(){
    const myElement = document.querySelector('#diamond');
    $("#buttonDiv").addClass("hidden");
    document.getElementById("action").disabled = true;

    const currentTransform = myElement.style.transform = 'translateX(280px) translateY(2px) rotate(135deg)';



}
function goToHead(){
    const myElement = document.querySelector('#diamond');
    document.getElementById("action").disabled = false;

    $("#buttonDiv").removeClass("hidden");
const currentTransform = myElement.style.transform = 'translateX(50px) translateY(2px) rotate(135deg)';



}



var setdate2;

function testDate() {
    var chosendate = document.getElementById("datestart").value;



    const x = new Date();
    const y = new Date(chosendate);

    if (x < y) {
        console.log("Valid");
        var monthNumber = new Date().getMonth() + 1;
        const asf = new Date(chosendate);
        asf.setDate(asf.getDate() + 1);
        var setdate = asf.getFullYear() + "-" + monthNumber + "-" + asf.getDate();
        document.getElementById("datefinish").value = setdate;

        setdate2 = asf.getFullYear() + "-" + monthNumber + "-" + asf.getDate();
        console.log(setdate)

    } else {
        alert("Sorry your request date is not accepted!")

        const z = new Date();
        var monthNumber = new Date().getMonth() + 1
        z.setDate(z.getDate() + 1);
        console.log(z);
        var setdate = z.getFullYear() + "-" + monthNumber + "-" + z.getDate();
        document.getElementById("datestart").value = setdate;
        console.log(setdate)

        const asf2 = new Date(setdate);
        asf2.setDate(asf2.getDate() + 2);
        setdate2 = asf2.getFullYear() + "-" + monthNumber + "-" + asf2.getDate();
        document.getElementById("datefinish").value = setdate2;

    }
}

function endDate() {
    console.log(setdate2);


    var chosendate3 = document.getElementById("datefinish").value;
    console.log(chosendate3);

    const x = new Date(setdate2);
    const y = new Date(chosendate3);

    if (x < y) {

    } else {
        alert("Sorry your request date is not accepted!")
        document.getElementById("datefinish").value = setdate2;

    }
}




$("#sidehome").addClass("bg-gray-