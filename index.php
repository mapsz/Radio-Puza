<?php

  require_once 'lib.php';  

  session_start();

  // dd(json_decode(@file_get_contents("http://myradio24.com/users/46047/status.json")));
  // if(isset($_SERVER['HTTP_COOKIE'])){
  //   dd($_SERVER['HTTP_COOKIE']);
  // }
  // dd($_SERVER['HTTP_USER_AGENT']);
  
  //Check login
  if (!isset($_SESSION['auth'])) {
    header('Location: login.php');
  }else{
    require_once 'config.php';
    $users = getUsers();

    $user = false;
    foreach ($users as $u) {
      if($_SESSION['auth'] == $u['login']){
        $user = $u;
      }
    }

    if(!$user) header('Location: login.php');

  }

?>


<!doctype html>
<html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Libs -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    
    <title>PLAYLIST by DJPUZA</title>
  </head>

  <body style="background-color: <?php echo $user['color']?>">

    <!-- Session data -->
    <input type="hidden" id="login" value="<?php echo $user['login'] ?>">
    <input type="hidden" id="session" value="<?php echo isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : "" ?>">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="/"><?php echo isset($user['username']) ? $user['username'] : 'RADIO'?></a>
        <div class="" id="navbarNav">
          <!-- Auth -->
          <div v-if="user" class="w-100 d-flex" style="justify-content:flex-end">
            <!-- Profile -->
            <span class="navbar-text">
              <?php echo $user['login']?>
            </span>
            <!-- Logout -->
            <li style="display: flex;align-items: center; margin-left: 10px;">
              <a href="logout.php" class="btn btn-sm btn-outline-danger" type="button">Logout</a>
            </li>
          </div> 
        </div>
      </div>
    </nav>

    <!-- Image -->
    <div style="display: flex;justify-content: center; max-height: 80%;margin-top: 20px;">
		  <img src="images\<?php echo $user['image']?>" style="width:100%;max-width:600px;">
    </div>

    <!-- Player -->
    <div style="display: flex;justify-content: center; margin-top: 20px;">

      <audio controls id="music">
      </audio>
    </div>

    <!-- Form -->
    <div>
      <?php require "form.php" ?>
    </div>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->


    <script>async function getSession(){axios.get("checkSession.php?login="+document.getElementById("login").value).then((function(e){document.getElementById("session").value!=e.data&&(console.log(document.getElementById("login").value),console.log(e.data),console.log("-----"),location.href="logout.php")}))}$(document).ready((function(){var e=document.getElementById("music");e.src=atob("<?php echo $user['player']?>"),setTimeout((()=>{e.removeAttribute("src")}),0)})),setInterval((()=>{getSession()}),<?php echo $sessionDelay?>);</script>

  </body>
</html>