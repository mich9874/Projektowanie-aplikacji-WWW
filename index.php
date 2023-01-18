<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="./img/2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.css">
    <script src="./javascript/timeDate.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="./javascript/kolorujTlo.js"></script>

    <title>Agro-Kajak</title>
</head>
<body>
<div id="cont">
<header id="header">
    <div id="container">
        <a href="./index.php?id=1"><img src="./img/logo.png" id="logo" alt=""></a>
        <nav class="bar">
            <ul>
                <li><a href="./index.php?id=1">O nas</a></li>
                <li><a href="./index.php?id=2">Spływy</a></li>
                <li><a href="./index.php?id=3">Cennik</a></li>
                <li><a href="./index.php?id=4">Skrypty</a></li>
                <li><a href="./index.php?id=5">Filmy</a></li>
                <li><a href="./index.php?id=6">Regulamin</a></li>
                <li><a href="./index.php?id=7">Kontakt</a></li>
                <li><a href="./index.php?id=8">Dojazd</a></li>
                <li><a href="./index.php?id=9">Produkty</a></li>
                <li><a href="./index.php?id=10">Koszyk</a></li>
            </ul>
        </nav>
    </div>
</header>
<?php
  session_start();
  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
  require_once('./show_page.php');
  require_once('./contact.php');
  require_once('./products.php');
  require_once('./koszyk.php');
  echo show_page($_GET['id']);
  if ($_GET['id'] == '9') {
    Produkty();
  }
  if ($_GET['id'] == '10') {
    Koszyk();
  }
  if(isset($_POST['cont_form'])) {
    echo sendMail();
  }
?>
</div>
  <footer>    
    <span>
      <?php
      $autor = 'Michał Bagiński';
      $nr_indeksu = '162131';
      $nr_grupy = '1';
      echo 'Autor: ' . $autor . ' (' . $nr_indeksu . '), grupa ' . $nr_grupy;
      ?>
    </span>
    Agro-Kajak
  </footer>
  <script src="./javascript/kolorujTlo.js"></script>
</body>
</html>