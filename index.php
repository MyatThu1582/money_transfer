<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
  <script src="bootstrap/js/bootstrap.js"></script>
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous"> -->
  <body>
      <?php

      if(isset($_POST['addbalance'])){
          $date = $_POST['date'];
          $desc = $_POST['desc'];
          $amt = $_POST['amt'];

          $stmt = $pdo->prepare("INSERT INTO opening_balances (date,description,opening_amt) VALUES ('$date','$desc','$amt')");
          $query = $stmt->execute();
      }

        if(isset($_POST['search'])){
          $search = $_POST['search'];

          $stmt = $pdo->prepare("SELECT * FROM cashbook WHERE date LIKE '%$search%'");
          $stmt->execute();
          $datas = $stmt->fetchall();
        }else{
          // $date = date('Y-m-d');
          $date = '2025-05-25';

          $balancestmt = $pdo->prepare("SELECT * FROM opening_balances WHERE date='$date'");
          $balancestmt->execute();
          $balancedatas = $balancestmt->fetch(PDO::FETCH_ASSOC);

          $stmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date'");
          $stmt->execute();
          $datas = $stmt->fetchall();
        }

       ?>
    <div class="container mt-5">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <?php 
              if(!empty($balancedatas)){
                ?>
              <div class="col-9">
                <h2>Cash Book (<?php echo $date; ?>)</h2>
              </div>
            <?php
              }else{
                ?>
              <div class="col-10">
                <h2>Cash Book (<?php echo $date; ?>)</h2>
              </div>
                <?php
              }
            ?>
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
              if(!empty($balancedatas)){
                ?>
                <div class="col-3 ps-5">
                  <a href="in.php"><button type="button" name="button" class="btn btn-primary me-2 mt-1">Cash In</button></a>
                  <a href="out.php"><button type="button" name="button" class="btn btn-success mt-1">Cash Out</button></a>
                </div>
                <?php
              }else{
                ?>
                <div class="col-2">
                  <button type="button" name="button" class="btn btn-info text-light mt-1 ms-2" data-bs-toggle="modal" data-bs-target="#exampleModal">Opening Balance</button>
                </div>
                <?php
              }
              ?>
          </div>
        </div>
        <div class="card-body">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Id</th>
                <th>Date</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php if(!empty($balancedatas)){ echo "1"; } ?></td>
                <td><?php if(!empty($balancedatas)){ echo $balancedatas['date']; } ?></td>
                <td><?php if(!empty($balancedatas)){ echo $balancedatas['description']; } ?></td>
                <td></td>
                <td></td>
                <td><?php if(!empty($balancedatas)){ echo $balancedatas['opening_amt']; } ?></td>
                <td></td>
              </tr>
              <?php
              $id = 2;
              foreach ($datas as $data)
               {
               ?>
              <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo $data['date']; ?></td>
                <td><?php echo $data['description']; ?></td>
                <td><?php echo $data['in_amt']; ?></td>
                <td><?php echo $data['out_amt']; ?></td>
                <td><?php echo $data['balance']; ?></td>
                <td>
                  <a href="update.php?id=<?php echo $data['id'] ?>"><button type="button" name="button" class="btn btn-warning btn-sm">Edit</button></a>
                  <a href="delete.php?id=<?php echo $data['id'] ?>"><button type="button" name="button" class="btn btn-danger btn-sm">Delete</button></a>
                </td>
              </tr>
            </tbody>
            <?php
              $id++;
                }
             ?>
          </table>
        </div>


        <!-- modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Opening Balance</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="" method="post">
                <label for="" class="h6">Date</label>
                <input type="date" name="date" class="form-control">
                <label for="" class="h6">Description</label>
                <input type="text" name="desc" class="form-control">
                <label for="" class="h6">Opening Balance</label>
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

        <div class="card-footer">
            <!-- <table class="table">
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total Cash In</th>
                <th>Total Cash Out</th>
                <th>Balance</th>
              </tr>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>

                <td>
                  <?php

                    if($_POST){

                      if(strlen($search) >= 4){
                        if($_POST){
                          $stmt = $pdo->prepare("SELECT SUM(in_amt) AS cashIn FROM cashbook WHERE date LIKE '%$search%'");
                          $stmt->execute();
                          $data = $stmt->fetch(PDO::FETCH_ASSOC);

                          echo $data['cashIn'] . " Kyats";
                        }else{
                          $stmt = $pdo->prepare("SELECT SUM(in_amt) AS cashIn FROM cashbook");
                          $stmt->execute();
                          $data = $stmt->fetch(PDO::FETCH_ASSOC);

                          echo $data['cashIn'] . " Kyats";
                        }
                        }

                    }else{
                      $stmt = $pdo->prepare("SELECT SUM(in_amt) AS cashIn FROM cashbook");
                      $stmt->execute();
                      $data = $stmt->fetch(PDO::FETCH_ASSOC);

                      echo $data['cashIn'] . " Kyats";
                    }




                   ?>
                </td>
                <td>
                  <?php

                  if($_POST){

                    if(strlen($search) >= 4){
                      if($_POST){
                        $stmt = $pdo->prepare("SELECT SUM(out_amt) AS cashOut FROM cashbook WHERE date LIKE '%$search%'");
                        $stmt->execute();
                        $data = $stmt->fetch(PDO::FETCH_ASSOC);

                        echo $data['cashOut'] . " Kyats";

                      }else{

                        $stmt = $pdo->prepare("SELECT SUM(out_amt) AS cashOut FROM cashbook");
                        $stmt->execute();
                        $data = $stmt->fetch(PDO::FETCH_ASSOC);

                        echo $data['cashOut'] . " Kyats";
                      }
                      }
                    }else{
                      $stmt = $pdo->prepare("SELECT SUM(out_amt) AS cashOut FROM cashbook");
                      $stmt->execute();
                      $data = $stmt->fetch(PDO::FETCH_ASSOC);

                      echo $data['cashOut'] . " Kyats";
                    }

                   ?>
                </td>
                <td>
                    <?php

                    if($_POST){

                      if(strlen($search) >= 4){
                        if($_POST){
                          $stmt = $pdo->prepare("SELECT SUM(in_amt) AS cashIn FROM cashbook WHERE date LIKE '%$search%'");
                          $stmt->execute();
                          $data = $stmt->fetch(PDO::FETCH_ASSOC);
                          $totalIn = $data['cashIn'];

                          $stmt = $pdo->prepare("SELECT SUM(in_amt) AS cashOut FROM cashbook WHERE date LIKE '%$search%'");
                          $stmt->execute();
                          $data = $stmt->fetch(PDO::FETCH_ASSOC);
                          $totalOut = $data['cashOut'];

                          $balance = $totalIn - $totalOut;

                          if($balance < 0){
                            echo "<span class = text-danger>$balance Kyats</text>";
                          }else{
                            echo $balance . " Kyats";
                          }

                        }else{
                          $stmt = $pdo->prepare("SELECT SUM(in_amt) AS cashIn FROM cashbook");
                          $stmt->execute();
                          $data = $stmt->fetch(PDO::FETCH_ASSOC);

                          $totalin = $data['cashIn'];

                          $stmt = $pdo->prepare("SELECT SUM(out_amt) AS cashOut FROM cashbook");
                          $stmt->execute();
                          $data = $stmt->fetch(PDO::FETCH_ASSOC);

                          $totalout = $data['cashOut'];

                          $balance = $totalin - $totalout;
                          if($balance < 0){
                            echo "<span class = text-danger>$balance Kyats</text>";
                          }else{
                            echo $balance . " Kyats";
                          }
                        }
                        }


                    }else{
                      $stmt = $pdo->prepare("SELECT SUM(in_amt) AS cashIn FROM cashbook");
                      $stmt->execute();
                      $data = $stmt->fetch(PDO::FETCH_ASSOC);

                      $totalin = $data['cashIn'];

                      $stmt = $pdo->prepare("SELECT SUM(out_amt) AS cashOut FROM cashbook");
                      $stmt->execute();
                      $data = $stmt->fetch(PDO::FETCH_ASSOC);

                      $totalout = $data['cashOut'];

                      $balance = $totalin - $totalout;
                      if($balance < 0){
                        echo "<span class = text-danger>$balance Kyats</text>";
                      }else{
                        echo $balance . " Kyats";
                      }
                    }


                     ?>
                </td>
              </tr>
            </table> -->
        </div>
      </div>
    </div>
  </body>
</html>
