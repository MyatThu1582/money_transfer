<?php include 'connect.php'; ?>
    <?php
      $category_id = $_SESSION['category_id'];

        if($_POST){
          $date = $_POST['date'];
          $des = $_POST['des'];
          $amount = $_POST['amount'];
          $percentage = $_POST['percentage'];

          $opening_balancestmt = $pdo->prepare("SELECT * FROM opening_balances WHERE date='$date' AND category_id='$category_id'");
          $opening_balancestmt->execute();
          $opening_balancedatas = $opening_balancestmt->fetch(PDO::FETCH_ASSOC);

          $balancestmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND category_id='$category_id' ORDER BY id DESC");
          $balancestmt->execute();
          $balancedatas = $balancestmt->fetch(PDO::FETCH_ASSOC);

          if(!empty($balancedatas)){
            $balance = $balancedatas['balance'] - $amount;
          }else{
            if (!empty($opening_balancedatas)) {
              $balance = $opening_balancedatas['opening_amt'] - $amount;
            }else{
              $balance = $amount;
            }
          }


          $stmt = $pdo->prepare("INSERT INTO cashbook (date,description,out_amt,balance,category_id) VALUES ('$date','$des','$amount','$balance','$category_id')");
          $query = $stmt->execute();

          $cash_idstmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' ORDER BY id DESC");
          $cash_idstmt->execute();
          $cash_datas = $cash_idstmt->fetch(PDO::FETCH_ASSOC);
          $cash_id = $cash_datas['id'];
          $percentage_amt = ($amount / 100) * $percentage;

          $stmt = $pdo->prepare("INSERT INTO percentage (date,description,percentage,percentage_amt,inorout,category_id,cash_id) VALUES ('$date','$des','$percentage','$percentage_amt','out','$category_id','$cash_id')");
          $query = $stmt->execute();

          if ($query) {
              echo "
              <script>
                  Swal.fire({
                      icon: 'success',
                      title: 'Added!',
                      text: 'Cash in Added Successfully',
                      confirmButtonText: 'Ok'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          window.location.href = 'cashbook.php';
                      }
                  });
              </script>
              ";
          }else{
            echo "<script>alert('Added failed !!')</script>";
          }
        }
     ?>
      <div class="container mt-5 w-50">
        <div class="card">
          <div class="card-header pt-3 pb-3">
            <h3>ငွေထုတ်မှတ်တမ်းထည့်သွင်းရန်</h3>
          </div>
          <div class="card-body">
            <form class="" action="out.php" method="post">
              <h5><label>ရက်စွဲ</label></h5>
              <input type="date" name="date" value="" class="form-control mb-3">
              <h5><label>အကြောင်းအရာ</label></h5>
              <input type="text" name="des" value="" class="form-control mb-3">
              <div class="d-flex gap-3">
                <div class="col">
                  <h5><label>ပမာဏ</label></h5>
                  <input type="number" name="amount" value="" class="form-control mb-3">
                </div>
                <div class="col">
                  <h5><label>ဝန်ဆောင်ခ( % )</label></h5>
                  <input type="number" name="percentage" value="3" class="form-control mb-3">
                </div>
              </div>
          </div>
          <div class="card-footer pt-3 pb-3">
            <div class="float-end">
              <a href="cashbook.php"><button type="button" name="button" class="btn btn-danger">Back</button></a>
              <button type="submit" name="button" class="btn btn-primary ms-1">Save</button>
              </div>
          </div>
        </form>
        </div>
      </div>
<?php include 'footer.php'; ?>
