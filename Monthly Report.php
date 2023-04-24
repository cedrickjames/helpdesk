<?php
include ("includes/connect.php");
    require 'dompdf/vendor/autoload.php';

    use Dompdf\Dompdf;
    session_start();

    $month = $_SESSION['month'];
    $year = $_SESSION['year'];

    $section = $_SESSION['level'];

    $firstdate = date('d', strtotime("first day of $year-$month"));
    $lastDateOfMonth = date('d', strtotime("last day of $year-$month"));
  
    $monthNumber = date('m', strtotime("$month"));
  
    $sql="SELECT ROUND((select (SELECT COUNT(`id`) as totalDone from `request` WHERE `request_to` = '$section' and (`status2` = 'Done' or `status2` = 'rated') AND `admin_approved_date` BETWEEN '$year-$monthNumber-$firstdate' AND '$year-$monthNumber-$lastDateOfMonth')/(SELECT COUNT(`id`) as totalDone from `request` WHERE `request_to` = '$section' AND `admin_approved_date` BETWEEN '$year-$monthNumber-$firstdate' AND '$year-$monthNumber-$lastDateOfMonth') as dividend) *100 , 2) as percentage;";
    $result = mysqli_query($con,$sql);


    $resultPercentage="";
  while($row=mysqli_fetch_assoc($result)){
    $resultPercentage = $row['percentage'];
  }
    $html ='<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Monthly Report</title>
        <style>
        @page { margin: 15px; }
        body{
            font-family: "Calibri", sans-serif;
        }
        .header{
            display: flex; 
            justify-content: center; 
        }
        .logo{
            height: 100px; width: 100px; background-color: blue
        }
        .label{
            font-weight: bold;
            font-size:11px;
        }
        .child{
            font-size:11px;

        }
        p{
            margin: 5px
        }
        .category{
            font-weight: bold;
            text-decoration: underline;
            font-size:11px;

        }
        table {
            border-collapse: collapse;
            border-color: inherit;
            text-indent: 0;
            margin-top: 10px;
			border: 2px;
            font-size: 9px;
            width: 100%;
		}
		#finishedTable td {
            border-width: .5px; border-style: solid; 
           padding-left: 5px;  border-color: gray
		}
        .first{
            width: 25%;
        }
        .second{
            width: 40%;

        }
        .third{
            width: 15%;
        }
        .fourth{
            width: 25%;

        }

        </style>

        <script src="../cdn_tailwindcss.js"></script>
    
       
    </head>
    <body style="margin: 0px; padding: 0px; ">
        <div style="text-align: center">
            <p style="font-size: 11px; margin: 0">Glory (Philippines) Inc.</p>
            <p style="font-size: 11px; margin: 0">Administration Department</p>
            <p style="font-size: 10px; margin: 0">http://glory-helpdesk.com</p>
            <p style="font-size: 11px; margin: 0; font-weight: bold">Job Order Monthly Report</p>
        </div>


        <table>
        <tr>
                <td class="first"><span class="label">Date</span><span style="align-text: right">:</span></td>
                <td class="second"> <span class="child">April 20, 2023</span></td>
               
           

            </tr>
            <tr>
                <td class="first"><span class="label">Section</span><span style="align-text: right">:</span></td>
                <td class="second"> <span style="text-transform: uppercase;" class="child">'.$section.'</span></td>
                <td><span class="label">Month of: </span></td>
                <td class="fourth"><span class="child">'.$month.' '. $year.' </span></td>


            </tr>

            <tr>
            <td class="first"><span class="label">Target:  </span></td>
            <td class="second"> <span class="child">100% accomplishment of Job Order Schedule.</span></td>
            <td><span class="label">Result: </span></td>
            <td class="fourth"><span class="child">'.$resultPercentage.'%</span></td>
                
            </tr>
            <tr>
            
        </tr>
        </table>

        <h5 style="margin-bottom: 0">Finished Job Order</h5>
        <table id="finishedTable" >
        
        <tr>
               <td>No.</td>
               <td>JO Number</td>
               <td>Requestor</td>
               <td>Details</td>
               <td>Date Started</td>
               <td>Date Finished</td>
            </tr>

            
            ';
        $a=1;
      
        $sql="select * from `request` WHERE `request_to` = '$section' and (`status2` = 'Done' or `status2` = 'rated') AND `admin_approved_date` BETWEEN '$year-$monthNumber-$firstdate' AND '$year-$monthNumber-$lastDateOfMonth' order by id asc  ";
        $result = mysqli_query($con,$sql);

      while($row=mysqli_fetch_assoc($result)){

        $date = new DateTime($row['date_filled']);
        $date = $date->format('ym');
         
        $dateApproved = new DateTime($row['admin_approved_date']);
        $dateApproved = $dateApproved->format('F d, Y');

        $dateFinished = new DateTime($row['actual_finish_date']);
        $dateFinished = $dateFinished->format('F d, Y');
          $html.='  <tr>
           <td>'.$a.'</td>
           <td>'.$date.'-'.$row['id'].'</td>
           <td>'.$row['requestor'].'</td>
           <td>'.$row['request_details'].'</td>
           <td>'.$dateApproved.'</td>
           <td>'.$dateFinished.'</td>
            </tr>';

            $a++;
      }
            
            
        
       $html.=' </table>
        <h5 style="margin-bottom: 0">Pending Job Order</h5>
        <table id="finishedTable" >
        
        <tr>
               <td>No.</td>
               <td>JO Number</td>
               <td>Requestor</td>
               <td>Details</td>
               <td>Personnel</td>
               <td>Date Started</td>

            </tr>

            
            ';
        $a=1;

        $sql="select * from `request` WHERE `request_to` = '$section' and `status2` = 'inprogress'  AND `admin_approved_date` BETWEEN '$year-$monthNumber-$firstdate' AND '$year-$monthNumber-$lastDateOfMonth' order by id asc  ";
        $result = mysqli_query($con,$sql);

      while($row=mysqli_fetch_assoc($result)){

        $date = new DateTime($row['date_filled']);
        $date = $date->format('ym');
         
        $dateApproved = new DateTime($row['admin_approved_date']);
        $dateApproved = $dateApproved->format('F d, Y');

          $html.='  <tr>
           <td>'.$a.'</td>
           <td>'.$date.'-'.$row['id'].'</td>
           <td>'.$row['requestor'].'</td>
           <td>'.$row['request_details'].'</td>
           <td>'.$row['assignedPersonnelName'].'</td>
           <td>'.$dateApproved.'</td>
            </tr>';

            $a++;
      }
            
            
        
       $html.=' </table>';
        $html.='<table style="bottom: 35px; position: absolute;">
<tr>
<td class="first"><span class="label">Prepared by: </span></td>
<td class="second"> <span class="child">Cedrick James Orozo</span></td>
<td class="third"><span class="label">Checked by: </span></td>
<td><span class="child">Jonathan Nemedez</span></td>
</tr>
</table>

        
    </body>
    </html>';   
    $dompdf = new Dompdf();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Monthly Report.pdf', ['Attachment' => 0]);
?>

