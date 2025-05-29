<?php include 'connect.php'; ?>
    <?php
    $category_id = $_SESSION['category_id'];

    $dateError = "";
    $desError = "";
    $amountError = "";

    $id = $_GET['id'];
        if ($_POST) {
          $date = $_POST['date'];
          $des = $_POST['des'];
          $amount = $_POST['amount'];
          $inorout = $_POST['inorout'];
          $percentage = $_POST['percentage'];
          $percentage_amt = ($amount / 100) * $percentage;
          
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
           
          $opening_balancestmt = $pdo->prepare("SELECT * FROM opening_balances WHERE date='$date' AND category_id='$category_id'");
          $opening_balancestmt->execute();
          $opening_balancedatas = $opening_balancestmt->fetch(PDO::FETCH_ASSOC);

          $balancestmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND id < '$id' AND category_id='$category_id' ORDER BY id DESC");
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
              $stmt = $pdo->prepare("UPDATE cashbook SET date ='$date', description ='$des', in_amt='$amount', out_amt='0', balance='$balance', category_id='$category_id' WHERE id=$id");
              $percentagestmt = $pdo->prepare("UPDATE percentage SET percentage = '$percentage',percentage_amt = '$percentage_amt', inorout = 'in' WHERE cash_id=$id");
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
              $stmt = $pdo->prepare("UPDATE cashbook SET date ='$date', description ='$des', in_amt='0', out_amt='$amount', balance='$balance', category_id='$category_id' WHERE id=$id");
              $percentagestmt = $pdo->prepare("UPDATE percentage SET percentage = '$percentage',percentage_amt = '$percentage_amt', inorout = 'out' WHERE cash_id=$id");
            }
            $stmt->execute();
            $percentagestmt->execute();

            $moredatastmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND id > '$id' AND category_id='$category_id'");
            $moredatastmt->execute();
            $moredatas = $moredatastmt->fetchAll();
            foreach($moredatas as $moredata){
              $id = $moredata['id'];
              $date = $moredata['date'];
              $in_amt = $moredata['in_amt'];
              $out_amt = $moredata['out_amt'];
              
              $datastmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND id < '$id' AND category_id='$category_id' ORDER BY id DESC");
              $datastmt->execute();
              $datas = $datastmt->fetch(PDO::FETCH_ASSOC);
              $currentbalance = $datas['balance'];
            
              $balance = ($currentbalance + $in_amt) - $out_amt;

              $stmt = $pdo->prepare("UPDATE cashbook SET balance='$balance' WHERE id='$id' AND category_id='$category_id'");
              $stmt->execute();
            }

            if($stmt){
              echo "
              <script>
                  Swal.fire({
                      icon: 'success',
                      title: 'Updated!',
                      text: 'Transaction Data Updated Successfully',
                      confirmButtonText: 'Ok'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          window.location.href = 'cashbook.php';
                      }
                  });
              </script>
              ";
            }else{
              echo "<script>alert('Updated failed'); win dow.location.href='index.php'</script>";
            }

          }
        }



        $stmt = $pdo->prepare("SELECT * FROM cashbook WHERE id=$id");
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
     ?>

     <div class="container mt-4 w-50">
       <div class="card">
         <div class="card-header pt-3 pb-3">
           <h3>ငွေအဝင်အထွက်ပြင်ဆင်ရန်</h3>     
         </div>
         <div class="card-body">
           <form class="" action="" method="post">
             <h5><label>ရက်စွဲ</label></h5>
             <input type="date" name="date" value="<?php echo $data['date']; ?>" class="form-control">
             <span class="text-danger"><?php echo $dateError; ?></span><br>
             <h5><label>အကြောင်းအရာ</label></h5>
             <input type="text" name="des" value="<?php echo $data['description']; ?>" class="form-control">
             <span class="text-danger"><?php echo $desError; ?></span><br>
             <h5><label>အဝင် ( သို့ ) အထွက်</label></h5>
            <select name="inorout" id="" class="form-control">
              <option value="in" <?php if($data['in_amt'] != 0){ echo "selected"; } ?>>In</option>
              <option value="out" <?php if($data['out_amt'] != 0){ echo "selected"; } ?>>Out</option>
            </select>
             <span class="text-danger"><?php echo $desError; ?></span><br>
              <div class="d-flex gap-3">
                <div class="col">
                  <h5><label>ပမာဏ</label></h5>
                  <input type="number" name="amount" value="<?php if($data['in_amt'] != 0){ echo $data['in_amt']; }else{ echo $data['out_amt']; } ?>" class="form-control mb-3">
                  <span class="text-danger"><?php echo $amountError; ?></span><br>
                </div>
                <div class="col">
                  <?php
                  $id = $_GET['id'];
                  $percentagestmt = $pdo->prepare("SELECT * FROM percentage WHERE cash_id='$id'");
                  $percentagestmt->execute();
                  $percentagedatas = $percentagestmt->fetch(PDO::FETCH_ASSOC);
                  $percentage = $percentagedatas['percentage'];
                  ?>
                  <h5><label>ဝန်ဆောင်ခ( % )</label></h5>
                  <input type="number" name="percentage" value="<?php echo $percentage; ?>" class="form-control mb-3">
                  <span class="text-danger"><?php echo $amountError; ?></span><br>
                </div>
             </div>
         </div>
         <div class="card-footer pt-3 pb-3">
          <div class="float-end">
            <a href="index.php"><button type="button" name="button" class="btn btn-danger">Back</button></a>
            <button type="submit" name="button" class="btn btn-primary mt-1 mb-1 ms-1">Save</button>
          </div>
         </div>
       </form>
       </div>
     </div>
<?php include 'footer.php'; ?>
