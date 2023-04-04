 <!-- session for who is login user    -->
<?php 




//Set the session timeout for 1 hour

$timeout = 3600;

//Set the maxlifetime of the session

ini_set( "session.gc_maxlifetime", $timeout );

//Set the cookie lifetime of the session

ini_set( "session.cookie_lifetime", $timeout );

  // session_start();
  
$s_name = session_name();
$url1=$_SERVER['REQUEST_URI'];
    header("Refresh: 500; URL=$url1");
//Check the session exists or not

if(isset( $_COOKIE[ $s_name ] )) {

    setcookie( $s_name, $_COOKIE[ $s_name ], time() + $timeout, '/' );

    
}

else

    echo "Session is expired.<br/>";


// end of session timeout>";






session_start();

    if(!isset($_SESSION['connected'])){
      header("location: index.php");
    }


    
// connection php and transfer of session
include ("includes/connect.php");
$user_dept = $_SESSION['department'];
$user_level=$_SESSION['level'];
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
    <link rel="stylesheet" href="./fontawesome-free-6.2.0-web/css/all.min.css">
  
    
     <!-- tailwind play cdn -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="./cdn_tailwindcss.js"></script>

  


    <!-- <link href="/dist/output.css" rel="stylesheet"> -->


     <!-- from flowbite cdn -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" /> -->
    <link rel="stylesheet" href="node_modules/flowbite/dist/flowbite.min.css" />


    <link rel="shortcut icon" href="resources/img/helpdesk.jpg">
    <!-- <link rel="stylesheet" href="css/style.css" /> -->


    <!-- darkmode -->
    <script>
    // On page load or when changing themes, best to add inline in `head` to avoid FOUC
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark')
    }
</script>



</head>
<body   class="static  bg-white dark:bg-gray-900"  >

    <!-- nav -->
    <?php require_once 'nav.php';?>


<!-- main -->






<div class="  flex mt-16  left-10 right-5  flex-col  px-14 sm:px-14  pt-6 pb-14 z-50 ">

<div class="flex flex-col">

  <div class="mt-0 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">

    <div class="flex items-start rounded-xl bg-blue-900 dark:bg-white p-4 shadow-lg">
      <div class="flex h-12 w-12 items-center justify-center rounded-full border border-red-100 bg-red-50">
      <img src="./resources/img/fem.png" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>

      <div class="ml-3">
        <h2 class="font-semibold text-gray-100 dark:text-gray-900">FEM Pending</h2>
        <p class="mt-2 text-xl text-gray-100">10</p>
      </div>
    </div>
    <div class="flex items-start rounded-xl bg-amber-500 dark:bg-white p-4 shadow-lg">
      <div class="flex h-12 w-12 items-center justify-center rounded-full border border-indigo-100 bg-indigo-50">
      <img src="./resources/img/mis.png" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
        </svg>
      </div>

      <div class="ml-3">
        <h2 class="font-semibold">MIS Pending</h2>
        <p class="mt-2 text-xl text-gray-900">20</p>
      </div>
    </div>



    <div class="flex items-start rounded-xl bg-green-500 dark:bg-white p-4 shadow-lg">
      <div class="flex h-12 w-12 items-center justify-center rounded-full border border-blue-100 bg-blue-50">
        <img src="./resources/img/open.png" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
        </svg>
      </div>

      <div class="ml-3">
        <h2 class="font-semibold">Finish Request for Acceptance</h2>
        <p class="mt-2 text-xl text-gray-900">20</p>
      </div>
    </div>



    
  </div>

  
</div> 
<?php require_once 'myjo.php';?>
<section class="bg-white dark:bg-gray-900">
    <div class="gap-8 items-center py-8 px-4 mx-auto max-w-screen-xl xl:gap-16 md:grid md:grid-cols-2 sm:py-16 lg:px-6">
        <img class="w-full dark:hidden" src="resources/img/helpdesk.jpg" alt="dashboard image">
        <img class="w-full hidden dark:block" src="resources/img/helpdesk.jpg" alt="dashboard image">
        <div class="mt-4 md:mt-0">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">FEM & MIS Help Desk System</h2>
            <p class="mb-6 font-light text-gray-500 md:text-lg dark:text-gray-400">Welcome to the IT help desk
              Get help with technical issues, check the status of an existing ticket, find self-guided training, and connect with your IT support team.</p>
            <a href="form.php" class="inline-flex items-center text-gray-900 dark:text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-primary-900">
                Proceed to request
                <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </a>
        </div>
    </div>
</section>


 </div> 



 


<!-- end of main -->
    

<!-- flowbite javascript -->

<!-- <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script> -->
<script src="node_modules/flowbite/dist/flowbite.js"></script>



<!-- darkmode script -->
<script>  
var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

// Change the icons inside the button based on previous settings
if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    themeToggleLightIcon.classList.remove('hidden');
} else {
    themeToggleDarkIcon.classList.remove('hidden');
}

var themeToggleBtn = document.getElementById('theme-toggle');

themeToggleBtn.addEventListener('click', function() {

    // toggle icons inside button
    themeToggleDarkIcon.classList.toggle('hidden');
    themeToggleLightIcon.classList.toggle('hidden');

    // if set via local storage previously
    if (localStorage.getItem('color-theme')) {
        if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }

    // if NOT set via local storage previously
    } else {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        }
    }
    
});




// active page highlight


var activepage = document.getElementById("main_index");
activepage.classList.remove("text-gray-700");
activepage.classList.add("text-blue-700");
activepage.classList.remove("dark:text-gray-400");
activepage.classList.add("dark:text-white");

</script>

</body>
</html>