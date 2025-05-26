<?php
  include 'connect.php';
  $id = $_GET['id'];
  $stmt = $pdo->prepare("DELETE FROM cashbook WHERE id=$id");
  $stmt->execute();
  if ($stmt) {
  echo "<script>alert('deleted successfully'); window.location.href='index.php'</script>";
  }
 ?>
