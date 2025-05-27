<?php include 'connect.php'; ?>
<?php

if(isset($_POST['tocashbook'])){
    $category_id = $_POST['category_id'];
    $_SESSION['category_id'] = $category_id;

    echo "<script>window.location.href='cashbook.php'</script>";
}


$nameError = '';
$imageError = '';

if(isset($_POST['addpayment'])){
    
    if(empty($_POST['name']) || empty($_FILES['image'])){
        
        if (empty($_POST['name'])) {
            $nameError = "The name is invalid !!";
        }
        if (empty($_FILES['image'])) {
            $imageError = "The image is invalid !!";
        }
        
    }else{
        $file = 'images/'.($_FILES['image']['name']);
        $imageType = pathinfo($file,PATHINFO_EXTENSION);
        if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
            echo "<script>alert('Image must be PNG, JPG, JPEG');</script>";
        }else{
              move_uploaded_file($_FILES['image']['tmp_name'],$file);
              $name = $_POST['name'];
              $image = $_FILES['image']['name'];
              $stmt = $pdo->prepare("INSERT INTO payment_categories (name,image) VALUES (:name,:image)");
              $stmt->execute(
             array(':name'=> $name, ':image'=> $image)
              );
         
              if ($stmt) {
                echo "
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Added !',
                        text: 'Payment Added Successfully',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.php';
                        }
                    });
                </script>
                ";
            }else{
              echo "<script>alert('Added failed !!')</script>";
            }
            }
        }
}



?>
  <div class="container mt-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
      <h2 class="mb-0">Choose Your Payment</h2>
      <div class="d-flex col-3">
      <div class="dropdown col">
        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
            Reporting
        </button>
        <ul class="dropdown-menu shadow mt-2 p-2">
            <li>
            <a class="dropdown-item rounded mb-1" href="report.php?report=cash_in_percentage">ငွေလွှဲဝန်ဆောင်ခ</a>
            </li>
            <li>
            <a class="dropdown-item rounded mb-1 border-top pt-2" href="report.php?report=cash_out_percentage">ငွေထုတ်ဝန်ဆောင်ခ</a>
            </li>
            <li>
            <a class="dropdown-item rounded mb-1 border-top pt-2" href="report.php?report=total_cash_in">ငွေဝင်စာရင်း</a>
            </li>
            <li>
            <a class="dropdown-item rounded border-top pt-2" href="report.php?report=total_cash_out">ငွေထုတ်စာရင်း</a>
            </li>
        </ul>
        </div>

        <div class="col">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal">+ Create New</button>
        </div>
      </div>
    </div>

    <!-- Cards -->
    <div class="row g-4">
        <?php 
            $stmt = $pdo->prepare("SELECT * FROM payment_categories ORDER BY id DESC");
            $stmt->execute();
            $datas = $stmt->fetchall();

            foreach($datas as $data){
        ?> 
            <div class="col-md-6 col-lg-3">
                <form action="" method="post">
                    <input type="hidden" value="<?php echo $data['id']; ?>" name="category_id">
                    <button class="btn btn-light" type="submit" name="tocashbook">
                        <div class="card shadow-sm text-center p-3">
                        <div class="card-body">
                        <h5 class="card-title"><?php echo $data['name']; ?></h5>
                        <p class="card-text text-muted">This is the first card.</p>
                        </div>
                        </div>
                    </button>
                </form>
            </div>
        <?php
            }
        ?>
    </div>

    <!-- modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Mobile Banking ထည့်သွင်းရန်</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="" method="post" enctype="multipart/form-data">
                <label for="" class="h6">Mobile Banking အမည်</label>
                <input type="text" name="name" class="form-control">
                <span class="text-danger"><?php echo $nameError; ?></span>
                <label for="" class="h6 mt-3 mb-2">ပုံရွေးချယ်ရန်</label>
                <input type="file" name="image" class="form-control">
                <span class="text-danger"><?php echo $imageError; ?></span>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="addpayment" class="btn btn-primary">Save changes</button>
            </form>
            </div>
        </div>
        </div>
    </div>
        
<?php include 'footer.php'; ?>
  
