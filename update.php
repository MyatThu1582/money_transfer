<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
  <body>
    <?php

    $dateError = "";
    $desError = "";
    $amountError = "";

    $id = $_GET['id'];
        if ($_POST) {
          $date = $_POST['date'];
          $des = $_POST['des'];
          $amount = $_POST['amount'];
          $inorout = $_POST['inorout'];

          if(empty($date)){
            $dateError = "*The date is Required*";
          }
          if(empty($des)){
            $desError = "*The description is Required*";
          }
          if(empty($amount)){
            $amountError = "*The amount is Required*";
          }
          if(empty($time)){
            $timeError = "*The time is Required*";
          }

          if(!empty($date) && !empty($des) && !empty($amount)){
           
          $opening_balancestmt = $pdo->prepare("SELECT * FROM opening_balances WHERE date='$date'");
          $opening_balancestmt->execute();
          $opening_balancedatas = $opening_balancestmt->fetch(PDO::FETCH_ASSOC);

          $balancestmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND id < '$id' ORDER BY id DESC");
          $balancestmt->execute();
          $balancedatas = $balancestmt->fetch(PDO::FETCH_ASSOC);


            if($inorout == 'in'){
              if(!empty($balancedatas)){
                $balance = $balancedatas['balance'] + $amount;
              }else{
                if (!empty($opening_balancedatas)) {
                  $balance = $opening_balancedatas['opening_amt'] + $amount;
                }else{
                  $balance = $amount;
                }
              }
              $stmt = $pdo->prepare("UPDATE cashbook SET date ='$date', description ='$des', in_amt='$amount', out_amt='0', balance='$balance' WHERE id=$id");
            }elseif($inorout == 'out'){
              if(!empty($balancedatas)){
                $balance = $balancedatas['balance'] - $amount;
              }else{
                if (!empty($opening_balancedatas)) {
                  $balance = $opening_balancedatas['opening_amt'] - $amount;
                }else{
                  $balance = $amount;
                }
              }
              $stmt = $pdo->prepare("UPDATE cashbook SET date ='$date', description ='$des', in_amt='0', out_amt='$amount', balance='$balance' WHERE id=$id");
            }
            $stmt->execute();

            $moredatastmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND id > '$id'");
            $moredatastmt->execute();
            $moredatas = $moredatastmt->fetchAll();
            foreach($moredatas as $moredata){
              $id = $moredata['id'];
              $date = $moredata['date'];
              $in_amt = $moredata['in_amt'];
              $out_amt = $moredata['out_amt'];
              
              $datastmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND id < '$id' ORDER BY id DESC");
              $datastmt->execute();
              $datas = $datastmt->fetch(PDO::FETCH_ASSOC);
              $currentbalance = $datas['balance'];
            
              $balance = ($currentbalance + $in_amt) - $out_amt;

              $stmt = $pdo->prepare("UPDATE cashbook SET balance='$balance' WHERE id='$id'");
              $stmt->execute();
            }

            if($stmt){
              echo "<script>alert('Updated successfully'); window.location.href='index.php'</script>";
            }else{
              echo "<script>alert('Updated failed'); win dow.location.href='index.php'</script>";
            }

          }
        }



        $stmt = $pdo->prepare("SELECT * FROM cashbook WHERE id=$id");
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
     ?>

     <div class="container mt-5 w-50">
       <div class="card">
         <div class="card-header">
           <div class="row">
             <div class="col-10">
               <h2>Update Your Cash</h2>
             </div>
             <div class="col-1">
               <a href="index.php"><button type="button" name="button" class="btn btn-danger">Back</button></a>
             </div>
           </div>
         </div>
         <div class="card-body">
           <form class="" action="" method="post">
             <h4><label>Date</label></h4>
             <input type="date" name="date" value="<?php echo $data['date']; ?>" class="form-control">
             <span class="text-danger"><?php echo $dateError; ?></span><br>
             <h4><label>Description</label></h4>
             <input type="text" name="des" value="<?php echo $data['description']; ?>" class="form-control">
             <span class="text-danger"><?php echo $desError; ?></span><br>
             <h4><label>In or Out</label></h4>
            <select name="inorout" id="" class="form-control">
              <option value="in" <?php if($data['in_amt'] != 0){ echo "selected"; } ?>>In</option>
              <option value="out" <?php if($data['out_amt'] != 0){ echo "selected"; } ?>>Out</option>
            </select>
             <span class="text-danger"><?php echo $desError; ?></span><br>
             <h4><label>Amount</label></h4>
             <input type="number" name="amount" value="<?php if($data['in_amt'] != 0){ echo $data['in_amt']; }else{ echo $data['out_amt']; } ?>" class="form-control mb-3">
             <span class="text-danger"><?php echo $amountError; ?></span><br>
         </div>
         <div class="card-footer">
           <button type="submit" name="button" class="btn btn-primary form-control mt-1 mb-1">Save</button>
         </div>
       </form>
       </div>
     </div>


  </body>
</html>
