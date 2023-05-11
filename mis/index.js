
  
$(document).ready(function () {
  
    $('#employeeTable').DataTable(  {
    "columnDefs": [
      { "width": "1%", "targets": 0},
      {"className": "dt-center", "targets": "_all"}
    ],
      responsive: true,
      
    }   );
    $('#overAllTable').DataTable(  {
      "columnDefs": [
        { "width": "1%", "targets": 0},
        {"className": "dt-center", "targets": "_all"}
      ],
        responsive: true,
        
      }   );
      $('#overAllFinished').DataTable(  {
        "columnDefs": [
          { "width": "1%", "targets": 0},
          {"className": "dt-center", "targets": "_all"}
        ],
          responsive: true,
          
        }   );
      $('#forRatingTable').DataTable(  {
        "columnDefs": [
          { "width": "1%", "targets": 0},
          {"className": "dt-center", "targets": "_all"}
        ],
          responsive: true,
          
        }   );
        $('#cancelledTable').DataTable(  {
          "columnDefs": [
            { "width": "1%", "targets": 0},
            {"className": "dt-center", "targets": "_all"}
          ],
            responsive: true,
            
          }   );
          $('#adminApprovalTable').DataTable(  {
            "columnDefs": [
              { "width": "1%", "targets": 0},
              {"className": "dt-center", "targets": "_all"}
            ],
              responsive: true,
              
            }   );
            $('#inProgressTable').DataTable(  {
              "columnDefs": [
                { "width": "1%", "targets": 0},
                {"className": "dt-center", "targets": "_all"}
              ],
                responsive: true,
                
              }   );
              
              $('#toRateTable').DataTable(  {
                "columnDefs": [
                  { "width": "1%", "targets": 0},
                  {"className": "dt-center", "targets": "_all"}
                ],
                  responsive: true,
                  
                }   );
                $('#overAllEmployees').DataTable(  {
                  "columnDefs": [
                    { "width": "1%", "targets": 0},
                    {"className": "dt-center", "targets": "_all"}
                  ],
                    responsive: true,
                    
                  }   );
                  $('#pmsTable').DataTable(  {
                    "columnDefs": [
                      { "width": "1%", "targets": 0},
                      {"className": "dt-center", "targets": "_all"}
                    ],
                      responsive: true,
                      
                    }   );
                  
                    $(document).ready(function () {
  
                      $('#removableDeviceTable').DataTable(  {
                      "columnDefs": [
                        { "width": "1%", "targets": 0},
                        {"className": "dt-center", "targets": "_all"}
                      ],
                        responsive: true,
                        
                      }   );
                    
                    });
                    $(document).ready(function () {
                    
                      $('#workingStationTable').DataTable(  {
                      "columnDefs": [
                        { "width": "1%", "targets": 0},
                        {"className": "dt-center", "targets": "_all"}
                      ],
                        responsive: true,
                        
                      }   );
                    
                    });  
              
            
  });

  

  function slideMainContent(){
    if(sidebar==0){
    document.getElementById("mainContent").style.width="100%";  
    document.getElementById("mainContent").style.marginLeft= "0px"; 
    // document.getElementById("sidebar").style.opacity= ""; 
    // document.getElementById("sidebar").style.transition = "all .1s";
    
    document.getElementById("mainContent").style.transition = "all .3s";
    
    
    
    
    
    
    sidebar=1;
    }
    else{
      document.getElementById("mainContent").style.width="calc(100% - 280px)";  
    document.getElementById("mainContent").style.marginLeft= "280px";  
    
    sidebar=0;
    }
    
    }