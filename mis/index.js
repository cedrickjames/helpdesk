
  
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
                      var table = $('#workingStationTable').DataTable({
                        select: {
                          style: 'multi'
                        },
                        "columnDefs": [
                          { "width": "1%", "targets": 0 },
                          { "className": "dt-center", "targets": "_all" }
                        ],
                        responsive: true,
                      });
                    
                      var selectedPcIds = []; // Array to store the selected PC IDs
                      var previousSelection = []; // Array to store the previously selected rows
                    
                      $('#workingStationTable').on('select.dt deselect.dt', function () {
                        var currentSelection = table.rows({ selected: true }).nodes().toArray();
                    
                        if (currentSelection.length > previousSelection.length) {
                          // New rows have been selected
                          var newSelectedRows = currentSelection.filter(function (row) {
                            return !previousSelection.includes(row);
                          });
                    
                          // Extract the PC IDs from the new selected rows
                          var newSelectedPcIds = newSelectedRows.map(function (row) {
                            return $(row).find('td:first').data('pcid');
                          });
                    
                          // Add the new selected PC IDs to the array
                          selectedPcIds = selectedPcIds.concat(newSelectedPcIds);
                        } else if (currentSelection.length < previousSelection.length) {
                          // Rows have been deselected
                          var deselectedRows = previousSelection.filter(function (row) {
                            return !currentSelection.includes(row);
                          });
                    
                          // Extract the PC IDs from the deselected rows
                          var deselectedPcIds = deselectedRows.map(function (row) {
                            return $(row).find('td:first').data('pcid');
                          });
                    
                          // Remove the deselected PC IDs from the array
                          selectedPcIds = selectedPcIds.filter(function (pcId) {
                            return !deselectedPcIds.includes(pcId);
                          });
                        }
                    
                        previousSelection = currentSelection.slice(); // Update the previous selection
                    
                        console.log(selectedPcIds);

                        // const divIdArrayUser = [1];
                        // divIdArrayUser.push(parseInt(inputCount));
                        document.getElementById("arrayOfSelected").value = selectedPcIds;
                      });
                    
                      $('#showSelectedButton').click(function () {
                        console.log("Asd");
                        $.ajax({
                          url: './devices.php',
                          method: 'POST',
                          data: { deviceIds: selectedPcIds },
                          success: function (response) {
                            // Handle the response from the server
                            // console.log(response);
                          },
                          error: function () {
                            // Handle the error case
                            console.log('Error updating devices');
                          }
                        });
                      });
                    });

  
              
            
  });
    // Submit the form and prevent page refresh
    const $targetDeviceModal = document.getElementById('editDeviceModal');
const optionsModalDevice = {
  placement: 'center-center',
  backdrop: 'dynamic',
  backdropClasses: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40',
  closable: true,
  onHide: () => {
      console.log('modal is hidden');
  },
  onShow: () => {
      console.log('modal is shown');

  },
  onToggle: () => {
      console.log('modal has been toggled');

  }
};
const modalDevice = new Modal($targetDeviceModal, optionsModalDevice);



    document.getElementById("myForm").addEventListener("submit", function(event) {

      event.preventDefault();

      // Make an AJAX request to getData.php
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "./getData.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
              // Parse the JSON response
              var response = JSON.parse(xhr.responseText);
              
              console.log(response); // Log the response array to the console
              // var divContainer = document.getElementById("divContainer");
              const inputContainer = document.getElementById("inputDevicesData");
              inputContainer.innerHTML="";



              // Clear any existing content in the div
              // divContainer.innerHTML = "";
              
              // Generate rows of inputs based on the response
              for (var i = 0; i < response.length; i++) {
                const div =document.createElement("div");
                div.classList.add("grid");
                div.classList.add("gap-1");
                div.classList.add("md:grid-cols-12");
                div.id = "div"+i+"";
                var set = "<div class='w-full'><input name='pcTag"+i+1+"' value='"+response[i].pctag+"' type='text' id='first_name' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='Insert PC Tag' required></div><div class='w-full'><input name='assetTag"+i+1+"' value='"+response[i].assetTag+"' type='text'  class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='FFFE-123' required></div><div class='w-full'><input name='pcname"+i+1+"' value='"+response[i].computerName+"' type='text'  class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='PC Name' required></div><div class='w-full'><input name='type"+i+1+"' value='"+response[i].type+"' type='text' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='Type' required></div><div class='w-full'><input name='user"+i+1+"' value='"+response[i].user+"' type='text'  class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='Full Name' required></div><div class='w-full'><input name='ipaddress"+i+1+"' value='"+response[i].ipAddress+"' type='text'  class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='IP Address' required></div><div class='w-full'><input name='department"+i+1+"' value='"+response[i].department+"' type='text'  class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='Department' required></div><div class='w-full'><input name='MAC Address"+i+1+"' value='"+response[i].macAddress+"' type='text' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='Mac Address' required></div><div class='w-full'><input name='email"+i+1+"'  type='text' value='"+response[i].email+"'  class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='Email' required></div><div class='w-full'><input name='os"+i+1+"' value='"+response[i].os+"' type='text'  class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='OS' required></div><div class='w-full'><input name='status"+i+1+"' value='"+response[i].deactivated+"' type='text'  class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='Active/Inactive' required></div><div class='w-full'><button type='button' onclick='removeSet("+i+")' class='text-white bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4  focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2'>-</button></div>";

                // <div class='w-full'><input name='status"+i+1+"' type='text'  class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' placeholder='Active/Inactive' required></div>
                  div.innerHTML=set;
                  inputContainer.appendChild(div);
                  
              }
         modalDevice.toggle();
            
              
          }
      };
      xhr.send("updateSelected=true&arrayOfSelected=" + encodeURIComponent(document.getElementById("arrayOfSelected").value));

  });
  function removeSet(id){

    // Retrieve the element by its id
var element = document.getElementById('div'+id);

// Check if the element exists
if (element) {
  // Retrieve the parent element
  var parentElement = element.parentNode;
  
  // Remove the element from its parent
  parentElement.removeChild(element);
}

}
  function devicemodalHide(){
    modalDevice.toggle();

  }
  // $(document).ready(function() {
  //   $('#myForm').submit(function(e) {
  //     e.preventDefault(); // Prevent the form from submitting normally
  
  //   $.ajax({
  //       type: 'POST',
  //       url: 'getData.php',
  //       dataType: 'json',
  //       success: function(data) {
  //         console.log(data); // Log the entire array
      
  //         // Log each array in $rowsList individually
  //         data.forEach(function(array) {
  //           console.log(array);
  //         });
  //       },
  //       error: function(xhr, status, error) {
  //         console.error(error); // Display an error message if the request fails
  //       }
  //     });
  //   });
  // });


  
  // $.ajax({
  //   type: 'POST',
  //   url: 'getData.php',
  //   dataType: 'json',
  //   success: function(data) {
  //     console.log(data); // Log the entire array
  
  //     // Log each array in $rowsList individually
  //     data.forEach(function(array) {
  //       console.log(array);
  //     });
  //   },
  //   error: function(xhr, status, error) {
  //     console.error(error); // Display an error message if the request fails
  //   }
  // });


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