<?php
session_start();
include ("includes/connect.php");

if(isset( $_SESSION['connected'])){

  header("location: main.php");
  
    }

if(isset($_POST['submit'])){
    
  $username = $_POST['username'];
  $password = $_POST['password'];
  $sql1 = "Select * FROM `user` WHERE `username`='$username'";
  $result = mysqli_query($con, $sql1);
  $numrows = mysqli_num_rows($result);
  while($userRow = mysqli_fetch_assoc($result)){
    $userDept = $userRow['department'];
    $name = $userRow['name'];
    $level = $userRow['level'];
    $userpass = $userRow['password'];
    $usermail = $userRow['email'];

    if($password == $userpass){
      $_SESSION['name'] = $name;
      $_SESSION['department'] = $userDept;
      $_SESSION['level'] = $level;
      $_SESSION['username'] = $username;
      $_SESSION['email'] = $usermail;


      $_SESSION['connected']=true;

      $date = new DateTime(); 
      $month = $date->format('F');
      $year = $date->format('Y');

      $_SESSION['selectedMonth'] = $month;
  $_SESSION['selectedYear'] = $year;



      if($level =='user'){
        header("location:employees");
      }
     else if($level =='mis'){
        header("location:mis");
      }
      else if($level =='fem'){
        header("location:fem");
      }
      else if($level =='head'){
        header("location:department-head");
      }
      else if($level =='admin'){
        header("location:department-admin");
      }
    }
    else{
      echo '<script>alert("Login Failed! Wrong password")</script>';
    }
  }

  if($numrows == 0){
    echo '<script>alert("Login Failed! Wrong credentials")</script>';
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
    
    <!-- font awesome -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" /> -->
    <link rel="stylesheet" href="./fontawesome-free-6.2.0-web/css/all.min.css">
  
     <!-- tailwind play cdn -->
    <script src="./cdn_tailwindcss.js"></script>





     <!-- from flowbite cdn -->
    <link rel="stylesheet" href="node_modules/flowbite/dist/flowbite.min.css" />
    <!-- <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" /> -->


    <link rel="shortcut icon" href="resources/img/helpdesk.png">
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
<body  onload=navFuntion() class="static  bg-white dark:bg-gray-900"  >

    <!-- nav -->
    <?php require_once 'nav_login.php';?>


<!-- main -->


<section class="h-screen">
  <div class="m-auto container px-6 py-4 h-full">
    <div class="flex justify-center items-center flex-wrap h-full g-6 text-gray-800">
      <div class="p-28 md:w-8/12 lg:w-6/12 mb-12 md:mb-0">
        <img
          src="resources/img/heldesk 3d.png"
          class="w-full"
          alt="Login image"
        />
      </div>
      <div class="p-28 md:w-8/12 lg:w-6/12 mb-12 md:mb-0">


        <form  method="post" action="login.php">

        <h1 class="text-gray-400 text-xl font-bold text-center mb-10">Welcome to Helpdesk System</h1>
          <!-- password input -->
          <div class="mb-6">
            <input
              type="text"
              name="username"
              autocomplete="off"
              class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
              placeholder="User Name"
            />
          </div>

          <!-- Password input -->
          <div class="mb-6">
            <input
              type="password"
              name="password"
              class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
              placeholder="Password"
            />
          </div>

          <div class="flex justify-between items-center mb-6">
           
            <a
              href="#!"
              class="hidden text-blue-600 hover:text-blue-700 focus:text-blue-700 active:text-blue-800 duration-200 transition ease-in-out"
              >Forgot password?</a
            >
          </div>

          <!-- Submit button -->
          <button
            type="submit"
            name="submit"
            class="inline-block px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-full"
            data-mdb-ripple="true"
            data-mdb-ripple-color="light"
          >
            Sign in
          </button>

  
        </form>

      </div>
      <footer class="w-full bg-white m-4">

<!-- <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" /> -->
<span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">  <a href="https://flowbite.com/" class="hover:underline">Designed By</a> Cedrick James - MIS Section</span>
</div>
</footer>
    </div>
     

  </div>
  
</section>



 


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






var activepage = document.getElementById("main_index");
activepage.classList.remove("text-gray-700");
activepage.classList.add("text-blue-700");
activepage.classList.remove("dark:text-gray-400");
activepage.classList.add("dark:text-white");

</script>

</body>
</html>