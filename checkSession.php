<?php
  require_once 'lib.php';

  if(isset($_GET['login']) && file_exists("sessions/".$_GET['login'])){
    $handle = fopen("sessions/".$_GET['login'], "r");
    $session = stream_get_contents($handle);
    fclose($handle);
    echo $session;
    exit;
  }