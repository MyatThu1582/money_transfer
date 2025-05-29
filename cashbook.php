<?php include 'connect.php'; ?>
  <?php
  $category_id = $_SESSION['category_id'];

  // Add Opening Balance
  if(isset($_POST['addbalance'])){
      $date = $_POST['date'];
      $desc = $_POST['desc'];
      $amt = $_POST['amt'];

      $stmt = $pdo->prepare("INSERT INTO opening_balances (date,description,opening_amt,category_id) VALUES ('$date','$desc','$amt','$category_id')");
      $query = $stmt->execute();
  }
  
  // Edit Opening Balance
  if(isset($_POST['editbalance'])){
      $id = $_POST['up_id'];
      $date = $_POST['up_date'];
      $desc = $_POST['up_desc'];
      $amt = $_POST['up_amt'];

      $stmt = $pdo->prepare("UPDATE opening_balances SET date ='$date', description ='$desc', opening_amt='$amt' WHERE id='$id'");
      $query = $stmt->execute();

      $moredatastmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND category_id='$category_id'");
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
        if(!empty($datas)){
          $currentbalance = $datas['balance'];
        }else{
          $currentbalance = $amt;
        }
        $balance = ($currentbalance + $in_amt) - $out_amt;
      

        $stmt = $pdo->prepare("UPDATE cashbook SET balance='$balance' WHERE id='$id' AND category_id='$category_id'");
        $stmt->execute();

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
            }
      }

  }

      $date = date('Y-m-d');
      // $date = '2025-05-25';

      $openingbalancestmt = $pdo->prepare("SELECT * FROM opening_balances WHERE date='$date' AND category_id='$category_id'");
      $openingbalancestmt->execute();
      $openingbalancedatas = $openingbalancestmt->fetch(PDO::FETCH_ASSOC);

      $stmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND category_id='$category_id'");
      $stmt->execute();
      $datas = $stmt->fetchall();

      $categorystmt = $pdo->prepare("SELECT * FROM payment_categories WHERE id = '$category_id'");
      $categorystmt->execute();
      $categorydatas = $categorystmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="container mt-5">
      <div class="row pt-2 pb-2">
          <div class="col-8">
            <h3><?php echo $categorydatas['name']; ?> ငွေစာရင်းမှတ်တမ်း <?php echo date('d-m-Y', strtotime($date)); ?></h3>
          </div>
        <!-- <div class="col-3">
          <form action="index.php" method="post">
            <div class="input-group mt-2">
              <input type="date" class="form-control" placeholder="Search...." aria-label="Recipient's username" name="search" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary me-4 text-dark" type="submit" id="button-addon2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg></button>
            </div>
          </form>
        </div> -->
        <?php 
          if(!empty($openingbalancedatas)){
            ?>
            <div class="col-4 ps-5 text-center">
              <a href="in.php"><button type="button" name="button" class="btn btn-primary me-2 mt-1">ငွေသွင်းရန်</button></a>
              <a href="out.php"><button type="button" name="button" class="btn btn-danger mt-1">ငွေထုတ်ရန်</button></a>
              <a href="index.php"><button type="button" name="button" class="btn btn-secondary mt-1 ms-2">နောက်သို့</button></a>  
            </div>
            <?php
          }else{
            ?>
            <div class="col-4 text-center ps-5">
              <button type="button" name="button" class="btn btn-info text-light mt-1 ms-2" data-bs-toggle="modal" data-bs-target="#exampleModal">အဖွင့်ငွေပမာဏ ထည့်ရန်</button>
              <a href="index.php"><button type="button" name="button" class="btn btn-secondary mt-1 ms-2">နောက်သို့</button></a>
            </div>
            <?php
          }
          ?>
      </div>

      <table class="table table-bordered table-hover align-middle mt-3">
        <thead class="table-light">
          <tr>
            <th>စဥ်</th>
            <th>ရက်စွဲ</th>
            <th>အကြောင်းအရာ</th>
            <th class="text-center">အဝင်</th>
            <th class="text-center">အထွက်</th>
            <th class="text-center">လက်ကျန်စာရင်း</th>
            <th class="text-center">( % ) </th>
            <th class="text-center" style="width: 150px;">အပြင်အဆင်</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php if(!empty($openingbalancedatas)){ echo "1"; } ?></td>
            <td><?php if(!empty($openingbalancedatas)){ echo $openingbalancedatas['date']; } ?></td>
            <td><?php if(!empty($openingbalancedatas)){ echo $openingbalancedatas['description']; } ?></td>
            <td></td>
            <td></td>
            <td class="text-center"><?php if(!empty($openingbalancedatas)){ echo $openingbalancedatas['opening_amt']; } ?></td>
            <td></td>
            <td class="text-center">
              <button style="border: none; background-color: transparent; color: blue; text-decoration: underline;" data-bs-toggle="modal" data-bs-target="#editOpeningBalance">ပြင်ရန်</button>
              <a href="delete.php?id=<?php echo $openingbalancedatas['id']; ?>&date=<?php echo $openingbalancedatas['date']; ?>&table=opening_balance">ဖျက်ရန်</a>
            </td>
          </tr>
          <!-- modal -->
          <div class="modal fade" id="editOpeningBalance" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">အဖွင့်ငွေပမာဏပြင်ရန်</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="" method="post">
                  <input type="hidden" name="up_id" class="form-control" value="<?php echo $openingbalancedatas['id']; ?>">
                  <label for="" class="h6">ရက်စွဲ</label>
                  <input type="date" name="up_date" class="form-control" value="<?php echo $openingbalancedatas['date']; ?>">
                  <label for="" class="h6 mt-3 mb-2">အကြောင်းအရာ</label>
                  <input type="text" name="up_desc" class="form-control" value="<?php echo $openingbalancedatas['description']; ?>">
                  <label for="" class="h6 mt-3 mb-2">ငွေပမာဏ</label>
                  <input type="text" name="up_amt" class="form-control" value="<?php echo $openingbalancedatas['opening_amt']; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="editbalance" class="btn btn-primary">Save changes</button>
                  </form>
                </div>
              </div>
            </div>
        </div>
          <?php
          $no = 2;
          foreach ($datas as $data){
            $id = $data['id'];

            $percentagestmt = $pdo->prepare("SELECT * FROM percentage WHERE cash_id='$id'");
            $percentagestmt->execute();
            $percentagedatas = $percentagestmt->fetch(PDO::FETCH_ASSOC);
            $percentage = $percentagedatas['percentage'];
            ?>
          <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $data['date']; ?></td>
            <td><?php echo $data['description']; ?></td>
            <td class="text-center"><?php echo $data['in_amt']; ?></td>
            <td class="text-center"><?php echo $data['out_amt']; ?></td>
            <td class="text-center"><?php echo $data['balance']; ?></td>
            <td class="text-center"><?php echo $percentage; ?></td>
            <td class="text-center">
              <a href="update.php?id=<?php echo $data['id']; ?>" class="me-2">ပြင်ရန်</a>
              <a href="delete.php?id=<?php echo $data['id']; ?>&date=<?php echo $data['date']; ?>">ဖျက်ရန်</a>
            </td>
          </tr>
        </tbody>
        <?php
          $no++;
            }
          ?>
      </table>

      <div class="card mt-4">
        <div class="card-body pt-4">
          <?php
            $date = date('Y-m-d');
  
            $cashin = $pdo->prepare("SELECT SUM(in_amt) AS cashIn FROM cashbook WHERE date = '$date' AND category_id='$category_id'");
            $cashin->execute();
            $cashindata = $cashin->fetch(PDO::FETCH_ASSOC);
  
            $cashout = $pdo->prepare("SELECT SUM(out_amt) AS cashOut FROM cashbook WHERE date = '$date' AND category_id='$category_id'");
            $cashout->execute();
            $cashoutdata = $cashout->fetch(PDO::FETCH_ASSOC);
  
            $balance = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND category_id='$category_id' ORDER BY id DESC");
            $balance->execute();
            $balancedata = $balance->fetch(PDO::FETCH_ASSOC);
  
            $percentage = $pdo->prepare("SELECT SUM(percentage_amt) AS percentage_amt FROM percentage WHERE date = '$date' AND category_id='$category_id'");
            $percentage->execute();
            $percentagedata = $percentage->fetch(PDO::FETCH_ASSOC);
    
          ?>
          <table class="table">
            <tr>
              <th class="text-center">စုစုပေါင်းဝင်ငွေ</th>
              <th class="text-center">စုစုပေါင်းထွက်ငွေ</th>
              <th class="text-center">လက်ကျန်ငွေ</th>
              <th class="text-center">ငွှေသွင်း/ငွေထုတ်ဝန်ဆောင်ခ</th>
            </tr>
            <tr>
              <th class="text-center"><?php if(!empty($cashindata) && $cashindata['cashIn'] != 0){ echo $cashindata['cashIn']; }else{ echo "-"; } ?></th>
              <th class="text-center"><?php if(!empty($cashoutdata) && $cashoutdata['cashOut'] != 0){ echo $cashoutdata['cashOut']; }else{ echo "-"; } ?></th>
              <th class="text-center"><?php if(!empty($balancedata)){ echo $balancedata['balance']; }elseif(!empty($openingbalances)){ echo $openingbalances['opening_amt']; }else{ echo "-"; } ?></th>
              <th class="text-center"><?php if(!empty($percentagedata) && $percentagedata['percentage_amt'] != 0){ echo $percentagedata['percentage_amt']; }else{ echo "-"; } ?></th>
            </tr>
          </table>
        </div>
      </div>


      <!-- modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">အဖွင့်ငွေပမာဏထည့်သွင်းရန်</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="" method="post">
              <label for="" class="h6">ရက်စွဲ</label>
              <input type="date" name="date" class="form-control">
              <label for="" class="h6 mt-3 mb-2">အကြောင်းအရာ</label>
              <input type="text" name="desc" class="form-control">
              <label for="" class="h6 mt-3 mb-2">ငွေပမာဏ</label>
              <input type="text" name="amt" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="addbalance" class="btn btn-primary">Save changes</button>
              </form>
            </div>
          </div>
        </div>
    </div>
<?php include 'footer.php'; ?>

