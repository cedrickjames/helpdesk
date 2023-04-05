 <!-- session for who is login user    -->
 <?php 




//Set the session timeout for 1 hour

$timeout = 3600;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Set the maxlifetime of the session

ini_set( "session.gc_maxlifetime", $timeout );

//Set the cookie lifetime of the session

ini_set( "session.cookie_lifetime", $timeout );

  // session_start();
  
$s_name = session_name();
$url1=$_SERVER['REQUEST_URI'];

//Check the session exists or not

if(isset( $_COOKIE[ $s_name ] )) {

    setcookie( $s_name, $_COOKIE[ $s_name ], time() + $timeout, '/' );

    
}

else

    echo "Session is expired.<br/>";


// end of session timeout>";






session_start();

    if(!isset($_SESSION['connected'])){
      header("location: ../index.php");
    }


    
// connection php and transfer of session
include ("../includes/connect.php");
$user_dept = $_SESSION['department'];
$user_level=$_SESSION['level'];
$username = $_SESSION['username'];


if(isset($_POST['updateJO'])){
    $computername = $_POST['computername'];
    $start = $_POST['start'];
    $finish = $_POST['finish'];
    $telephone = $_POST['telephone'];
    
    
    $joid = $_POST['joid2'];
    $message = $_POST['message'];
    $sql = "UPDATE `request` SET `computerName`='$computername',`reqstart_date`='$start',`reqfinish_date`='$finish', `request_details` = '$message' , `telephone`='$telephone' WHERE `id` = '$joid';";
    $results = mysqli_query($con,$sql);
    
    }
    
if(isset($_POST['rateJo'])){
    $rateScore = $_POST['rateScore'];
    $ratingcomment = $_POST['ratingcomment'];
    $joid = $_POST['joid2'];
    $assigned = $_POST['misPersonnel'];
    $requestor = $_POST['requestor'];

    $sql = "UPDATE `request` SET `status2`='rated',`rating_final`='$rateScore',`requestor_remarks`='$ratingcomment' WHERE `id` = '$joid';";
    $results = mysqli_query($con,$sql);

    if($results){


        
            

        $sql1 = "Select * FROM `user` WHERE `username` = '$assigned'";
        $result = mysqli_query($con, $sql1);
        while($list=mysqli_fetch_assoc($result))
        {
        $personnelEmail=$list["email"];
        $perseonnelName=$list["name"];

        }


        $sql2 = "Select * FROM `sender`";
        $result2 = mysqli_query($con, $sql2);
        while($list=mysqli_fetch_assoc($result2))
        {
        $account=$list["email"];
        $accountpass=$list["password"];

          }    

        $subject ='Job Order Rating';
        $message = 'Hi '.$perseonnelName.',<br> <br> Mr./Ms. '.$requestor.' rated your Job Order with '.$rateScore.'. Please check the details by signing in into our Helpdesk <br> Click this http://192.168.60.53/helpdesk to signin. <br><br><br> This is a generated email. Please do not reply. <br><br> Helpdesk';
        

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
            $mail->addAddress($personnelEmail);              
            $mail->isHTML(true);                                  
            $mail->Subject = $subject;
            $mail->Body    = $message;
    
            $mail->send();

                  $_SESSION['message'] = 'Message has been sent';
                  echo "<script>alert('Thank you! Your rating is now submitted.') </script>";
                  echo "<script> location.href='index.php'; </script>";


                // header("location: form.php");
            } catch (Exception $e) {
                $_SESSION['message'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
            echo "<script>alert('Message could not be sent. Mailer Error.') </script>";

            }

       
       }
    
    }


    if(isset($_POST['cancelJO'])){
        $joid = $_POST['joid2'];
        $reasonCancel = $_POST['reasonCancel'];
        $sql = "UPDATE `request` SET `status`='cancelled', `reasonOfCancellation`='$reasonCancel' WHERE `id` = '$joid';";
        $results = mysqli_query($con,$sql);
        
        
        }
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FEM MIS Helpdesk</title>
    
    <!-- font awesome -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" /> -->
    <link rel="stylesheet" href="../fontawesome-free-6.2.0-web/css/all.min.css">


    
  
    <link rel="stylesheet" href="../node_modules/DataTables/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../node_modules/DataTables/Responsive-2.3.0/css/responsive.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="../node_modules/DataTables/Editor-2023-04-08-2.1.2/css/editor.dataTables.min.css"/>

    <link rel="stylesheet" href="index.css">
     <!-- tailwind play cdn -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="../cdn_tailwindcss.js"></script>

  


    <!-- <link href="/dist/output.css" rel="stylesheet"> -->


     <!-- from flowbite cdn -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" /> -->
    <link rel="stylesheet" href="../node_modules/flowbite/dist/flowbite.min.css" />


    <link rel="shortcut icon" href="../resources/img/helpdesk.jpg">
    <!-- <link rel="stylesheet" href="css/style.css" /> -->




</head>
<body   class="static  bg-white dark:bg-gray-900"  >

    <!-- nav -->
    <?php require_once 'nav.php';?>


<!-- main -->






<div class=" ml-72 flex mt-16  left-10 right-5  flex-col  px-14 sm:px-8  pt-6 pb-14 z-50 ">
<div class="justify-center text-center flex items-start h-auto bg-gradient-to-r from-blue-900 to-teal-500 rounded-xl ">
<div class="text-center py-2 m-auto lg:text-center w-full">
        <!-- <h6 class="text-sm  tracking-tight text-gray-200 sm:text-lg">Good Day</h6> -->
        <!-- <div class="m-auto flex flex-col w-2/4  h-12">
<h2 class="text-xl font-bold tracking-tight text-gray-100 sm:text-xl">Total numbers of pending Job Order</h2>

</div> -->

<!--        
<div class="m-auto flex flex-col w-2/4">

<div class="mt-0 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 ">

  <div class="flex items-start rounded-xl bg-teal-700 dark:bg-white p-4 shadow-lg">
    <div class="flex h-12 w-12 overflow-hidden items-center justify-center rounded-full border border-red-100 bg-red-50">
    <img src="../resources/img/Engineer.png" class="h-full w-full text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

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
    <div class="flex h-12 w-12 items-center overflow-hidden  justify-center rounded-full border border-indigo-100 bg-indigo-50">
    <img src="../resources/img/itboy.png" class="h-full w-full text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

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
</div>  -->
<div class="FrD3PA">
    <div class="QnQnDA" tabindex="-1">
        <div  role="tablist" class="_6TVppg sJ9N9w">
            <div class="uGmi4w">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400" id="tabExample" role="tablist">
                <li  role="presentation">
                <div class="p__uwg" style="width: 106px; margin-right: 0px;">
                    <button id="headApprovalTab"  onclick="goToFinished()" type="button" role="tab" aria-controls="headApproval"  class="_1QoxDw o4TrkA CA2Rbg Di_DSA cwOZMg zQlusQ uRvRjQ POMxOg _lWDfA"  aria-selected="false">
                        <div class="_1cZINw">
                        <div class="_qiHHw Ut_ecQ kHy45A">

<img src="../resources/img/list.png" class="h-full w-full text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

</div>
                        </div>
                        <p class="_5NHXTA _2xcaIA ZSdr0w CCfw7w GHIRjw">Finished J.O.</p>
                    </button></div>
                </li>
                <li  role="presentation">
                    
                <div class="p__uwg" style="width: 113px; margin-left: 16px; margin-right: 0px;">
                <button id="adminApprovalTab" onclick="goToCancelled()"
                        class="_1QoxDw o4TrkA CA2Rbg cwOZMg zQlusQ uRvRjQ POMxOg" type="button" tabindex="-1" role="tab" aria-controls="adminApproval" aria-selected="false">
                        <div class="_1cZINw">
                            <div class="_qiHHw Ut_ecQ kHy45A">

                            <img src="../resources/img/disapprove.png" class="h-full w-full text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                            </div>
                        </div>
                        <p class="_5NHXTA _2xcaIA ZSdr0w CCfw7w GHIRjw">Cancelled</p>
                    </button></div>
                </li>   
            
              
                    </ul>
            </div>
            <div class="rzHaWQ theme light" id="diamond" style="transform: translateX(55px) translateY(2px) rotate(135deg);"></div>
        </div>
    </div>
</div>
<div class="hidden"> 
<ul class="uGmi4w  mb-1 m-4 flex text-sm font-medium text-center text-gray-500 divide-x divide-gray-200 rounded-lg shadow  dark:divide-gray-700 dark:text-gray-400">
    <li class="w-full relative">
        <a href="#" class="inline-block w-full p-4 text-gray-900 bg-gray-100 rounded-l-lg focus:ring-4 focus:ring-blue-300 active focus:outline-none dark:bg-gray-700 dark:text-white" aria-current="page">For Approval</a>
        <div class="rzHaWQ theme light" style="transform: translateX(198px) translateY(2px) rotate(135deg);"></div>
  
    </li>
    <li class="w-full">
        <a href="#" class="inline-block w-full p-4 bg-white hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">Dashboard</a>
    </li>
    <li class="w-full">
        <a href="#" class="inline-block w-full p-4 bg-white hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">Settings</a>
    </li>
    <li class="w-full">
        <a href="#" class="inline-block w-full p-4 bg-white rounded-r-lg hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">Invoice</a>
    </li>

</ul>

</div>

</div>
</div>



<!-- <div class="grid grid-cols-2 m-auto flex flex-col w-full h-20 mt-4">
<div class="flex items-center justify-center h-full bg-teal-500 p-2">
<div class=" flex h-full w-20 overflow-hidden items-center justify-center rounded-full border border-red-100 bg-red-50">
    <img src="../resources/img/list.png" class="h-full w-full text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    </div>
    <div class="ml-3">
      <h2 class="font-semibold text-4xl text-gray-100 dark:text-gray-900">My Job Order</h2>
    </div>
</div>
<div class="h-full bg-gray-500"></div>


</div> -->
<div id="myTabContent">
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="headApproval" role="tabpanel" aria-labelledby="profile-tab">
    <?php include 'finished.php';?>   



    </div>
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="adminApproval" role="tabpanel" aria-labelledby="dashboard-tab">
    <?php include 'cancelled.php';?>   
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
            <div id="cancelledByDiv"class="hidden w-full">
                     <h2 class="font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Cancelled by: </span><span id="cancelledBy"></span></h2>
    
         
                </div>
            <div id="assignedPersonnelDiv"class=" w-full">
                     <h2 class="font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Assigned Personnel : </span><span id="assignedPersonnel"></span></h2>
    
         
                </div>
            <input type="text" name="joid2" id="joid2" class="hidden col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                
                <div class="w-full grid gap-4 grid-cols-2">
                     <h2 class="font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">JO Number : </span><span id="jonumber"></span></h2>
                    <h2 class="pl-10 font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Date filed: </span><span id="datefiled"></span></h2>
                </div>
                <div class="w-full grid gap-4 grid-cols-2">
                     <h2 class="font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Requested Section: </span><span id="sectionmodal"></span></h2>
                     <h2 class="pl-10 font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Type: </span><span id="category"></span></h2>
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
                                    placeholder="Request date finish" required>
                            </div>
                        </div>
                    </div>

                </div>
                <div id="actualDateFinishedDiv" class="w-full grid gap-4 grid-cols-2">
                     <h2 class="font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Actual date finished : </span><span id="actualDateFinished"></span></h2>
                    </div>
                    <div id="actualDateFinishedDiv" class="w-full grid gap-4 grid-cols-12">
                     <h2 class="col-span-2 font-semibold text-gray-900 dark:text-gray-900"><span class="text-gray-400">Ratings: </span> </h2>
                     <div id="stars" class="grid col-span-10">
<div class="flex items-center">
    <svg aria-hidden="true" id="star1" class="w-5 h-5 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>First star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
    <svg aria-hidden="true" id="star2" class="w-5 h-5 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Second star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
    <svg aria-hidden="true" id="star3" class="w-5 h-5 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Third star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
    <svg aria-hidden="true" id="star4" class="w-5 h-5 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fourth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
    <svg aria-hidden="true" id="star5" class="w-5 h-5 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fifth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
    <p class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400"><span id="finalRatings"></span> out of 5</p>
</div>
</div>
                    </div>
        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <label for="message" class="py-4 col-span-1 font-semibold text-gray-400 dark:text-gray-400">Request Details</label>
                <textarea disabled id="message" name="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
                <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <div id="actionDetailsDiv" class="">
                <label for="message" class="py-4 col-span-1 font-semibold text-gray-400 dark:text-gray-400">Details of action</label>
                <textarea disabled id="actionDetails" name="actionDetails" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
            
                </div>
                <div id="reasonCancelDiv" class="hidden">
                <label for="message" class="py-4 col-span-1 font-semibold text-gray-400 dark:text-gray-400">Reason of Cancellation</label>
                <textarea disabled id="reasonCancel" name="reasonCancel" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
            
                </div>
               
            </div> 
            
            <div id="buttondiv" class=" items-center p-4 border-t border-gray-200 rounded-b dark:border-gray-600">
            <button type="submit" name="updateJO" class="shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80  w-full text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Print</button>

     
            </div>
            <div id="buttonRateDiv" class="hidden items-center p-4 border-t border-gray-200 rounded-b dark:border-gray-600">
            <button  type="button" data-modal-target="rateModal" data-modal-toggle="rateModal"   class="shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80  w-full text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Rate</button>
            </div>
            


            <div id="popup-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button"  onclick="exitcancellation()" data-modal-toggle="popup-modal" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" >
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            
            <div class="p-6 text-center">
            <br>
              <br><br>
              <br><br>
              <br>
                <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">If you're sure about canceling, please give a reason.</h3>
                <textarea  id="reasonCancel" name="reasonCancel" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a reason..."></textarea>
              <br>
              <br>

                <button type="submit" name="cancelJO"  class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Submit
                </button>
                <button  onclick="exitcancellation()" data-modal-toggle="popup-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Exit</button>
                <br>
              <br>
              <br>
              <br>
              <br>
              <br>
            </div>
        </div>
    </div>
</div>
<div id="rateModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Job Order Details
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="rateModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class=" items-center p-6 space-y-2">
                <br>
                <br>
                <br>
                <br>
                <br>


            <div class="flex justify-center  m-auto">
                <input type="text" value="5" id="rateScore" name="rateScore" class="hidden">
                <input type="text" id="misPersonnel" name="misPersonnel" class="hidden">
                <input type="text" id="requestor" name="requestor" class="hidden">

                

    <svg aria-hidden="true" id="rate1" onclick = "rate('rate1')" class="cursor-pointer w-20 h-20 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>First star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
    <svg aria-hidden="true" id="rate2"  onclick = "rate('rate2')" class="cursor-pointer w-20 h-20 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Second star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
    <svg aria-hidden="true" id="rate3" onclick = "rate('rate3')" class="cursor-pointer w-20 h-20 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Third star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
    <svg aria-hidden="true" id="rate4"  onclick = "rate('rate4')" class="cursor-pointer w-20 h-20 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fourth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
    <svg aria-hidden="true" id="rate5"  onclick = "rate('rate5')" class="cursor-pointer w-20 h-20 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fifth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
</div> 
<hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <label for="ratingcomment" class="py-4 col-span-1 font-semibold text-gray-400 dark:text-gray-400">What is your thoughts about the service?</label>
                <textarea id="ratingcomment" name="ratingcomment" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
</div>
            <div class=" items-center p-4 ">
            <button type="submit" name="rateJo" class="shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80  w-full text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Submit</button>
            <br>
                
                <br>
                <br>
                <br>
                <br>
                <br>

            </div>
        </div>
    </div>
   

</div>
        </form>
            
        </div>
    </div>
</div>



 
    

<!-- flowbite javascript -->

<!-- <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script> -->

<script src="../node_modules/flowbite/dist/flowbite.js"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>

<script type="text/javascript" src="../node_modules/DataTables/datatables.min.js"></script>

<script type="text/javascript" src="../node_modules/DataTables/Editor-2023-04-08-2.1.2/js/dataTables.editor.min.js"></script>
    <script type="text/javascript" src="../node_modules/DataTables/Responsive-2.3.0/js/dataTables.responsive.min.js"></script>
    <!-- <script type="text/javascript" src="../node_modules/DataTables/Editor-2023-04-08-2.1.2/js/editor.dataTables.js"></script> -->
    <script type="text/javascript" src="index.js"></script>

<script>
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
    const buttonModal = document.querySelector("#viewdetails");

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
    document.getElementById("datefiled").innerHTML =element.getAttribute("data-datefiled");
    document.getElementById("sectionmodal").innerHTML =element.getAttribute("data-section");
    document.getElementById("telephone").value =element.getAttribute("data-telephone");
    document.getElementById("attachment").setAttribute("href", element.getAttribute("data-attachment"));
    document.getElementById("category").innerHTML =element.getAttribute("data-category");
    document.getElementById("computername").value =element.getAttribute("data-comname");
    document.getElementById("datestart").value =element.getAttribute("data-start");
    document.getElementById("datefinish").value =element.getAttribute("data-end");
    document.getElementById("message").value =element.getAttribute("data-details");
    document.getElementById("actionDetails").value =element.getAttribute("data-action");
    document.getElementById("misPersonnel").value =element.getAttribute("data-personnel");
    document.getElementById("requestor").value =element.getAttribute("data-requestor");
    document.getElementById("assignedPersonnel").innerHTML =element.getAttribute("data-assignedpersonnel");
    document.getElementById("cancelledBy").innerHTML =element.getAttribute("data-cancelledby");
    document.getElementById("reasonCancel").innerHTML =element.getAttribute("data-reason");
    document.getElementById("actualDateFinished").innerHTML =element.getAttribute("data-actualdatefinished");
    document.getElementById("finalRatings").innerHTML =element.getAttribute("data-ratings");

    var ratings = element.getAttribute("data-ratings");
    $("#star1").removeClass("text-yellow-400");
        $("#star2").removeClass("text-yellow-400");
        $("#star3").removeClass("text-yellow-400");
        $("#star4").removeClass("text-yellow-400");
        $("#star5").removeClass("text-yellow-400");

        $("#star1").removeClass("text-gray-300");
        $("#star2").removeClass("text-gray-300");
        $("#star3").removeClass("text-gray-300");
        $("#star4").removeClass("text-gray-300");
        $("#star5").removeClass("text-gray-300");
    if(ratings == '1'){


        $("#star1").addClass("text-yellow-400");
        $("#star2").addClass("text-gray-300");
        $("#star3").addClass("text-gray-300");
        $("#star4").addClass("text-gray-300");
        $("#star5").addClass("text-gray-300");

    }
    else if(ratings == '2'){
        $("#star1").addClass("text-yellow-400");
        $("#star2").addClass("text-yellow-400");
        $("#star3").addClass("text-gray-300");
        $("#star4").addClass("text-gray-300");
        $("#star5").addClass("text-gray-300");

    }
    else if(ratings == '3'){
        $("#star1").addClass("text-yellow-400");
        $("#star2").addClass("text-yellow-400");
        $("#star3").addClass("text-yellow-400");
        $("#star4").addClass("text-gray-300");
        $("#star5").addClass("text-gray-300");

    }
    else if(ratings == '4'){
        $("#star1").addClass("text-yellow-400");
        $("#star2").addClass("text-yellow-400");
        $("#star3").addClass("text-yellow-400");
        $("#star4").addClass("text-yellow-400");
        $("#star5").addClass("text-gray-300");

    }
    else if(ratings == '5'){
        $("#star1").addClass("text-yellow-400");
        $("#star2").addClass("text-yellow-400");
        $("#star3").addClass("text-yellow-400");
        $("#star4").addClass("text-yellow-400");
        $("#star5").addClass("text-yellow-400");

    }
    
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
    // set the drawer menu element
const $targetEl = document.getElementById('sidebar');

// options with default values
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

/*
* targetEl: required
* options: optional
*/
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





// // Code for tabs
const tabElements= [
    {
        id: 'headApproval1',
        triggerEl: document.querySelector('#headApprovalTab'),
        targetEl: document.querySelector('#headApproval')
    },
    {
        id: 'adminApproval1',
        triggerEl: document.querySelector('#adminApprovalTab'),
        targetEl: document.querySelector('#adminApproval')
    }
];

// options with default values
const taboptions = {
    defaultTabId: 'headApproval1',
    activeClasses: 'text-white hover:text-amber-400 dark:text-blue-500 dark:hover:text-blue-400 border-blue-600 dark:border-blue-500',
    inactiveClasses: 'text-gray-300 hover:text-amber-500 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300',
    onShow: () => {
        console.log('tab is shown');
    }
};

/*
* tabElements: array of tab objects
* options: optional
*/
const tabs = new Tabs(tabElements, taboptions);

// open tab item based on id
tabs.show('headApproval1');


// // get the tab object based on ID
// tabs.getTab('adminApproval1')

// // get the current active tab object
// tabs.getActiveTab()


function goToAdmin(){
    const myElement = document.querySelector('#diamond');
    
    document.getElementById("telephone").disabled = true;
    document.getElementById("datestart").disabled = true;
    document.getElementById("datefinish").disabled = true;
    document.getElementById("message").disabled = true;
    document.getElementById("computername").disabled = true;

    $("#assignedPersonnelDiv").addClass("hidden");

    $("#buttondiv").addClass("hidden");

    $("#actionDetailsDiv").addClass("hidden");

    

// Get the current transform value
const currentTransform = myElement.style.transform = 'translateX(180px) translateY(2px) rotate(135deg)';


// transform: translateX(55px) translateY(2px) rotate(135deg);
}

function goToMis(){
    document.getElementById("telephone").disabled = true;
    document.getElementById("datestart").disabled = true;
    document.getElementById("datefinish").disabled = true;
    document.getElementById("message").disabled = true;
    document.getElementById("computername").disabled = true;
    
    $("#assignedPersonnelDiv").removeClass("hidden");
    $("#buttondiv").addClass("hidden");


    $("#actionDetailsDiv").addClass("hidden");

    const myElement = document.querySelector('#diamond');

// Get the current transform value
const currentTransform = myElement.style.transform = 'translateX(300px) translateY(2px) rotate(135deg)';


// transform: translateX(55px) translateY(2px) rotate(135deg);
}
function goToRate(){
    document.getElementById("telephone").disabled = true;
    document.getElementById("datestart").disabled = true;
    document.getElementById("datefinish").disabled = true;
    document.getElementById("message").disabled = true;
    document.getElementById("computername").disabled = true;
    $("#assignedPersonnelDiv").removeClass("hidden");


    $("#actionDetailsDiv").removeClass("hidden");

    $("#buttondiv").addClass("hidden");


    const myElement = document.querySelector('#diamond');

// Get the current transform value
const currentTransform = myElement.style.transform = 'translateX(420px) translateY(2px) rotate(135deg)';


// transform: translateX(55px) translateY(2px) rotate(135deg);
}
function goToFinished(){
    document.getElementById("telephone").disabled = true;
    document.getElementById("datestart").disabled = true;
    document.getElementById("datefinish").disabled = true;
    document.getElementById("message").disabled = true;
    document.getElementById("computername").disabled = true;
    $("#assignedPersonnelDiv").removeClass("hidden");


    $("#actionDetailsDiv").removeClass("hidden");

    $("#buttondiv").removeClass("hidden");
    $("#reasonCancelDiv").addClass("hidden");
    $("#cancelledByDiv").addClass("hidden");
    $("#actualDateFinishedDiv").removeClass("hidden");
    const myElement = document.querySelector('#diamond');

// Get the current transform value
const currentTransform = myElement.style.transform = 'translateX(50px) translateY(2px) rotate(135deg)';


// transform: translateX(55px) translateY(2px) rotate(135deg);
}
function goToCancelled(){
    document.getElementById("telephone").disabled = true;
    document.getElementById("datestart").disabled = true;
    document.getElementById("datefinish").disabled = true;
    document.getElementById("message").disabled = true;
    document.getElementById("computername").disabled = true;
    $("#assignedPersonnelDiv").addClass("hidden");
    $("#reasonCancelDiv").removeClass("hidden");
    $("#cancelledByDiv").removeClass("hidden");
    $("#actualDateFinishedDiv").addClass("hidden");

    


    
    $("#actionDetailsDiv").addClass("hidden");

    $("#buttondiv").addClass("hidden");


    const myElement = document.querySelector('#diamond');

// Get the current transform value
const currentTransform = myElement.style.transform = 'translateX(180px) translateY(2px) rotate(135deg)';


// transform: translateX(55px) translateY(2px) rotate(135deg);
}
function goToHead(){
    $("#buttondiv").removeClass("hidden");
   
    $("#actionDetailsDiv").addClass("hidden");
    $("#assignedPersonnelDiv").addClass("hidden");

    document.getElementById("telephone").disabled =false;
    document.getElementById("computername").disabled = false;
    document.getElementById("datestart").disabled = false;
    document.getElementById("datefinish").disabled = false;
    document.getElementById("message").disabled = false;
    const myElement = document.querySelector('#diamond');

// Get the current transform value
const currentTransform = myElement.style.transform = 'translateX(50px) translateY(2px) rotate(135deg)';


// transform: translateX(55px) translateY(2px) rotate(135deg);
}
function cancellation(){
    document.getElementById("reasonCancel").required = true;
}
function exitcancellation(){
    document.getElementById("reasonCancel").required = false;
}


var setdate2;
var setdate;


function testDate() {
    var chosendate = document.getElementById("datestart").value;


     console.log(chosendate)
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
function rate(id){
    console.log(id);

    if(id=="rate1"){
        document.getElementById("rateScore").value='1';
        $("#rate1").removeClass("text-gray-300");
        $("#rate2").removeClass("text-gray-300");
        $("#rate3").removeClass("text-gray-300");
        $("#rate4").removeClass("text-gray-300");
        $("#rate5").removeClass("text-gray-300");

        $("#rate1").removeClass("text-yellow-400");
        $("#rate2").removeClass("text-yellow-400");
        $("#rate3").removeClass("text-yellow-400");
        $("#rate4").removeClass("text-yellow-400");
        $("#rate5").removeClass("text-yellow-400");

        $("#rate1").addClass("text-yellow-400");
        $("#rate2").addClass("text-gray-300");
        $("#rate3").addClass("text-gray-300");
        $("#rate4").addClass("text-gray-300");
        $("#rate5").addClass("text-gray-300");
    }
    else if(id=="rate2"){
        document.getElementById("rateScore").value='2';

        $("#rate1").removeClass("text-gray-300");
        $("#rate2").removeClass("text-gray-300");
        $("#rate3").removeClass("text-gray-300");
        $("#rate4").removeClass("text-gray-300");
        $("#rate5").removeClass("text-gray-300");

        $("#rate1").removeClass("text-yellow-400");
        $("#rate2").removeClass("text-yellow-400");
        $("#rate3").removeClass("text-yellow-400");
        $("#rate4").removeClass("text-yellow-400");
        $("#rate5").removeClass("text-yellow-400");

        $("#rate1").addClass("text-yellow-400");
        $("#rate2").addClass("text-yellow-400");

        $("#rate3").addClass("text-gray-300");
        $("#rate4").addClass("text-gray-300");
        $("#rate5").addClass("text-gray-300");
    
    }
    else if(id=="rate3"){
        document.getElementById("rateScore").value='3';

        $("#rate1").removeClass("text-gray-300");
        $("#rate2").removeClass("text-gray-300");
        $("#rate3").removeClass("text-gray-300");
        $("#rate4").removeClass("text-gray-300");
        $("#rate5").removeClass("text-gray-300");

        $("#rate1").removeClass("text-yellow-400");
        $("#rate2").removeClass("text-yellow-400");
        $("#rate3").removeClass("text-yellow-400");
        $("#rate4").removeClass("text-yellow-400");
        $("#rate5").removeClass("text-yellow-400");

        $("#rate1").addClass("text-yellow-400");
        $("#rate2").addClass("text-yellow-400");
        $("#rate3").addClass("text-yellow-400");

        $("#rate4").addClass("text-gray-300");
        $("#rate5").addClass("text-gray-300");
    
    }
    else if(id=="rate4"){
        document.getElementById("rateScore").value='4';

        $("#rate1").removeClass("text-gray-300");
        $("#rate2").removeClass("text-gray-300");
        $("#rate3").removeClass("text-gray-300");
        $("#rate4").removeClass("text-gray-300");
        $("#rate5").removeClass("text-gray-300");

        $("#rate1").removeClass("text-yellow-400");
        $("#rate2").removeClass("text-yellow-400");
        $("#rate3").removeClass("text-yellow-400");
        $("#rate4").removeClass("text-yellow-400");
        $("#rate5").removeClass("text-yellow-400");

        $("#rate1").addClass("text-yellow-400");
        $("#rate2").addClass("text-yellow-400");
        $("#rate3").addClass("text-yellow-400");
        $("#rate4").addClass("text-yellow-400");

        $("#rate5").addClass("text-gray-300");
    
    }
    else if(id=="rate5"){
        document.getElementById("rateScore").value='5';

        $("#rate1").removeClass("text-gray-300");
        $("#rate2").removeClass("text-gray-300");
        $("#rate3").removeClass("text-gray-300");
        $("#rate4").removeClass("text-gray-300");
        $("#rate5").removeClass("text-gray-300");

        $("#rate1").removeClass("text-yellow-400");
        $("#rate2").removeClass("text-yellow-400");
        $("#rate3").removeClass("text-yellow-400");
        $("#rate4").removeClass("text-yellow-400");
        $("#rate5").removeClass("text-yellow-400");

        $("#rate1").addClass("text-yellow-400");
        $("#rate2").addClass("text-yellow-400");
        $("#rate3").addClass("text-yellow-400");
        $("#rate4").addClass("text-yellow-400");
        $("#rate5").addClass("text-yellow-400");

    
    }
}
$("#sidehome").removeClass("bg-gray-200");
$("#sidehistory").addClass("bg-gray-200");
$("#sidepms").removeClass("bg-gray-200");
</script>

</body>
</html>