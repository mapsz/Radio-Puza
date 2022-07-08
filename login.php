<?php

  //Start Session
  session_start();

  //Set data
  $data = $_POST;
  $login = '';
  $password = '';
  $error_msg = '';
  
  if (isset($data['login_action'])) {

      //Check credentials
      require_once 'config.php';
      $users = getUsers();

      $login = $data['login'];
      $password = $data['password'];

      foreach ($users as $user) {
        if($user['login'] == $login && $user['password'] == $password)
          {//Success

            if(!isset($_SERVER['HTTP_COOKIE'])){
              dd(22);
            }

            //Add Session
            $sessionFile = fopen("sessions/".$user['login'], "w+");
            fwrite($sessionFile, $_SERVER['HTTP_COOKIE']);
            fclose($sessionFile);

            //Add Auth
            $_SESSION['auth'] = $user['login'];

            //Redirect
            header('Location: index.php');            
            exit;
          }
      }

      //Set error
      $error_msg = 'Incorrect login or password';

  }

  // if (isset($data['login_action'])) {
      // if ($login == $config['first_user']['login']) {
      //     if ($password == $config['first_user']['password']) {
      //         $_SESSION['auth'] = 1;
      //         header('Location: index.php');
      //     } else {
      //         $error_msg = 'Incorrect password...';
      //     }
      // } else if ($login == $config['second_user']['login']) {
      //     if ($password == $config['second_user']['password']) {
      //         $_SESSION['auth'] = 2;
      //         header('Location: index.php');
      //     } else {
      //         $error_msg = 'Incorrect password...';
      //     }
      // } else if ($login == $config['third_user']['login']) {
      //     if ($password == $config['third_user']['password']) {
      //         $_SESSION['auth'] = 3;
      //         header('Location: index.php');
      //     } else {
      //         $error_msg = 'Incorrect password...';
      //     }
      // } else {
      //     $error_msg = 'Incorrect login...';
      // }
  // }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Login page</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">

    

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/docs/5.1/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/5.1/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/5.1/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.1/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.1/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
    <link rel="icon" href="/docs/5.1/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#7952b3">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      html,
        body {
        height: 100%;
        }

        body {
        display: flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
        }

        .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
        }

        .form-signin .checkbox {
        font-weight: 400;
        }

        .form-signin .form-floating:focus-within {
        z-index: 2;
        }

        .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        }
    </style>

  </head>
  <body class="text-center">
    
    <main class="form-signin">
        <form action="login.php" method="POST">
          <h1 class="h3 mb-3 fw-normal">Login</h1>
          <!-- Login -->
          <div class="form-floating">
            <input type="text" class="form-control" id="floatingInput" name="login" placeholder="Login" value="<?php echo $login; ?>">
            <label for="floatingInput">Login</label>
          </div>
          <!-- Password -->
          <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" value="<?php echo $password; ?>" name="password" placeholder="Password">
            <label for="floatingPassword">Password</label>
          </div>
          <button class="w-100 btn btn-lg btn-primary" name="login_action" type="submit">Sign in</button>
          <!-- Errors -->
          <p style="color: #ca2525;"><?php echo $error_msg; ?></p>
        </form>
    </main>

  </body>
</html>

