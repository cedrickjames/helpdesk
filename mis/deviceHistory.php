<?php 


$user_dept = $_SESSION['department'];
$user_level=$_SESSION['level'];
$username=$_SESSION['username'];



?>
<section class="mt-10">
  
<table id="deviceHistoryTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Number</th>
             
                <th>Device</th>
                <th>Field Changed</th>
                <th>From</th>
                <th>To</th>
                <th>Modifier</th>
                <th>Date</th>


            </tr>
        </thead>
        <tbody>
              <?php
                          $date = new DateTime(); 
                          $month = $_SESSION['selectedMonth'];
                          $year = $_SESSION['selectedYear'];
                $a=1;

                $sql="SELECT
                devicehistory.type,
                devicehistory.deviceId,
                devicehistory.modifier,
                devicehistory.date,
                CASE
                  WHEN devicehistory.type = 'cctv' THEN cctv.cameraNo
                  WHEN devicehistory.type = 'printer' THEN printer.serialNo
                  ELSE devices.computerName
                END AS computerName,
                devicehistory.field,
                devicehistory.fromThis,
                devicehistory.toThis
              FROM
                devicehistory
              LEFT JOIN
                cctv ON devicehistory.deviceId = cctv.id AND devicehistory.type = 'cctv'
              LEFT JOIN
                printer ON devicehistory.deviceId = printer.id AND devicehistory.type = 'printer'
              LEFT JOIN
                devices ON devicehistory.deviceId = devices.id AND devicehistory.type NOT IN ('cctv', 'printer')
              ORDER BY
                devicehistory.id DESC;
              ";
                $result = mysqli_query($con,$sql);

                while($row=mysqli_fetch_assoc($result)){
                  ?>
              <tr class="">
              <td>
              <?php 
              echo $a;?> 
             </td>
             <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php echo $row['computerName'];?> 
              
              </td>

              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php echo $row['field'];?> 
              
              </td>

              <!-- to view pdf -->
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php echo $row['fromThis'];?> 
              
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php echo $row['toThis'];?> 
              </td>
            
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php echo $row['modifier'];?> 
              </td>

              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
              <?php echo $row['date'];?> 
              </td>




                </tr>
                  <?php 

                    $a++;
            }
               ?>
          </tbody>
    </table>

</section>







  