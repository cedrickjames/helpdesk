
  
$(document).ready(function () {
  
    $('#employeeTable').DataTable(  {
    "columnDefs": [
      { "width": "1%", "targets": 0},
      {"className": "dt-center", "targets": "_all"}
    ],
      responsive: true,
      
    }   );
  
  });
  
  $(document).ready(function () {
  
    $('#adminApprovalTable').DataTable(  {
    "columnDefs": [
      { "width": "1%", "targets": 0},
      {"className": "dt-center", "targets": "_all"}
  

    ],
  
      responsive: true,
      
    }   );
  
  });
  $(document).ready(function () {
  
    $('#inProgressTable').DataTable(  {
    "columnDefs": [
      { "width": "1%", "targets": 0},
      {"className": "dt-center", "targets": "_all"}
    ],
      responsive: true,
      
    }   );
  
  });
  $(document).ready(function () {
  
    $('#toRateTable').DataTable(  {
    "columnDefs": [
      { "width": "1%", "targets": 0},
      {"className": "dt-center", "targets": "_all"}
    ],
      responsive: true,
      
    }   );
  
  }); 
