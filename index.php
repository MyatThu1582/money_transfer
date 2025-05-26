<?php include 'connect.php'; ?>
<?php

if($_POST){
    $category_id = $_POST['category_id'];
    $_SESSION['category_id'] = $category_id;

    echo "<script>window.location.href='cashbook.php'</script>";
}

?>
  <div class="container mt-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
      <h2 class="mb-0">Choose Your Payment</h2>
      <div class="">
        <button class="btn btn-primary me-2">Reporting</button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal">+ Create New</button>
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
                    <button class="btn btn-light" type="submit">
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
            <form action="" method="post">
                <label for="" class="h6">Mobile Banking အမည်</label>
                <input type="name" name="name" class="form-control">
                <label for="" class="h6 mt-3 mb-2">ပုံရွေးချယ်ရန်</label>
                <input type="file" name="img" class="form-control">
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
  
