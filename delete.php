<?php include 'connect.php'; ?>
  <?php
  $deleteid = $_GET['id'];
  $date = $_GET['date'];

  if(!empty($_GET['table'] && $_GET['table'] == 'opening_balance')){
    $stmt = $pdo->prepare("DELETE FROM opening_balances WHERE id=$deleteid");
    // $stmt->execute();
  }else{
    $stmt = $pdo->prepare("DELETE FROM cashbook WHERE id=$deleteid");
    $stmt->execute();
    $percentagestmt = $pdo->prepare("DELETE FROM percentage WHERE cash_id=$deleteid");
    $percentagestmt->execute();
    
    $id = $deleteid;
  
    $moredatastmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND id > '$id'");
    $moredatastmt->execute();
    $moredatas = $moredatastmt->fetchAll();
  
    foreach($moredatas as $moredata){
        $id = $moredata['id'];
        $in_amt = $moredata['in_amt'];
        $out_amt = $moredata['out_amt'];
        
        $datastmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND id < '$id' ORDER BY id DESC");
        $datastmt->execute();
        $datas = $datastmt->fetch(PDO::FETCH_ASSOC);
  
        if(!empty($datas)){
          $currentbalance = $datas['balance'];
        }else{
          $opening_balancestmt = $pdo->prepare("SELECT * FROM opening_balances WHERE date='$date'");
          $opening_balancestmt->execute();
          $opening_balancedatas = $opening_balancestmt->fetch(PDO::FETCH_ASSOC);
          $currentbalance = $opening_balancedatas['opening_amt'];
        }
  
          $balance = ($currentbalance + $in_amt) - $out_amt;
    
          $stmt = $pdo->prepare("UPDATE cashbook SET balance='$balance' WHERE id='$id'");
          $stmt->execute();
      }
  }

  
        if($stmt){
          echo "
              <script>
                  Swal.fire({
                      icon: 'success',
                      title: 'Done!',
                      text: 'Transaction Deleted successfully',
                      confirmButtonText: 'Ok'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          window.location.href = 'cashbook.php';
                      }
                  });
              </script>
              ";
        }
 ?>
<?php include 'footer.php'; ?>
