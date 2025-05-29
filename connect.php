<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <style>
    .scroll{
      overflow-y: auto;
      height: 228px;
    }
  </style>
<?php
define('MYSQL_USER','root');
define('MYSQL_PASSWORD','');
define('MYSQL_HOST','localhost');
define('MYSQL_DATABASE', 'money_transfer');

$options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);

$pdo = new PDO(
  'mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DATABASE,MYSQL_USER,MYSQL_PASSWORD,$options
  )
 ?>
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<script src="bootstrap/js/bootstrap.bundle.js"></script>
<!-- SweetAlert (v1) CDN -->
<script src="bootstrap/sweetalert/sweetalert.min.js"></script>
<body>