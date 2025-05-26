<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <body>
    <?php
        if($_POST){
          $date = $_POST['date'];
          $des = $_POST['des'];
          $amount = $_POST['amount'];
          
          $opening_balancestmt = $pdo->prepare("SELECT * FROM opening_balances WHERE date='$date'");
          $opening_balancestmt->execute();
          $opening_balancedatas = $opening_balancestmt->fetch(PDO::FETCH_ASSOC);

          $balancestmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' ORDER BY id DESC");
          $balancestmt->execute();
          $balancedatas = $balancestmt->fetch(PDO::FETCH_ASSOC);

          if(!empty($balancedatas)){
            $balance = $balancedatas['balance'] + $amount;
          }else{
            if (!empty($opening_balancedatas)) {
              $balance = $opening_balancedatas['opening_amt'] + $amount;
            }else{
              $balance = $amount;
            }
          }


          $stmt = $pdo->prepare("INSERT INTO cashbook (date,description,in_amt,balance) VALUES ('$date','$des','$amount','$balance')");
          $query = $stmt->execute();
          if ($query) {
              echo "<script>alert('Added successfully !'); window.location.href='index.php'</script>";
          }else{
            echo "<script>alert('Added failed !!')</script>";
          }
        }
     ?>
      <div class="container mt-5 w-50">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-10">
                <h2>Add Your Cash In Bill </h2>
              </div>
              <div class="col-1">
                <a href="index.php"><button type="button" name="button" class="btn btn-danger">Back</button></a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <form class="" action="in.php" method="post">
              <h4><label>Date</label></h4>
              <input type="date" name="date" value="" class="form-control mb-3">
              <h4><label>Description</label></h4>
              <input type="text" name="des" value="" class="form-control mb-3">
              <h4><label>Amount</label></h4>
              <input type="number" name="amount" value="" class="form-control mb-3">
          </div>
          <div class="card-footer">
            <button type="submit" name="button" class="btn btn-primary form-control mt-1 mb-1">Save</button>
          </div>
        </form>
        </div>
      </div>
  </body>
</html>
