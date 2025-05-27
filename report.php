<?php include 'connect.php'; ?>
<?php 
if($_GET['report'] == 'cash_in_percentage'){
    $cash_in_percentagestmt = $pdo->prepare("SELECT * FROM cashbook WHERE in_amt != 0");
    $cash_in_percentagestmt->execute();
    $cash_in_percentagedatas = $cash_in_percentagestmt->fetchAll();
}elseif($_GET['report'] == 'cash_out_percentage'){
    // echo "cash_out_percentage";
}elseif($_GET['report'] == 'total_cash_in'){
    // echo "total_cash_in";
}elseif($_GET['report'] == 'total_cash_out'){
    // echo "total_cash_out";
}

// if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
    
// }
?>

<div class="mx-5 py-4">
  <!-- 🔥 Report Header Title -->
  <div class="mb-4">
    <h2 class="mb-0">
      📊 
      <?php 
          if($_GET['report'] == 'cash_in_percentage'){
              echo "ငွေလွှဲဝန်ဆောင်ခ";
          } elseif($_GET['report'] == 'cash_out_percentage'){
              echo "ငွေထုတ်ဝန်ဆောင်ခ";
          } elseif($_GET['report'] == 'total_cash_in'){
              echo "ငွေဝင်စာရင်း";
          } elseif($_GET['report'] == 'total_cash_out'){
              echo "ငွေထုတ်စာရင်း";
          }
      ?>
      Report
    </h2>
  </div>

  <!-- 🔀 2-Column Layout: Table + Filter -->
  <div class="row">
    <!-- 📊 Report Table (Left Side) -->
    <div class="col-lg-9 mb-4">

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
                <th class="text-center">စဥ်</th>
                <th class="text-center">ရက်စွဲ</th>
                <th class="text-center" style="width: 300px;">အကြောင်းအရာ</th>
                <th class="text-center">အဝင်</th>
                <th class="text-center">ဝန်ဆောင်ခ ( % ) </th>
                <th class="text-center">ဝန်ဆောင်ခပမာဏ</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            foreach($cash_in_percentagedatas as $cash_in_percentagedata){
                $id = $cash_in_percentagedata['id'];
                $percentagestmt = $pdo->prepare("SELECT * FROM percentage WHERE cash_id != '$id'");
                $percentagestmt->execute();
                $percentagedatas = $percentagestmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
              <td class="text-center"><?php echo $no; ?></td>
              <td class="text-center"><?php echo date('d-m-Y', strtotime($cash_in_percentagedata['date'])) ?></td>
              <td class="text-center"><?php echo $cash_in_percentagedata['description']; ?></td>
              <td class="text-center"><?php echo $cash_in_percentagedata['in_amt']; ?></td>
              <td class="text-center"><?php echo $percentagedatas['percentage']; ?></td>
              <td class="text-center"><?php echo $percentagedatas['percentage_amt']; ?></td>
            </tr>
            <?php
            $no++;
            }
            ?>
            <!-- More rows -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- 🎛️ Filter Form (Right Side) -->
    <div class="col-lg-3">
      <div class="bg-light rounded-3 p-4 shadow-sm">
        <h5 class="mb-3">Filter Reports</h5>
        <form method="GET" class="d-flex flex-column gap-3">
          <div>
            <label for="startDate" class="form-label">Start Date</label>
            <input type="date" id="startDate" name="start_date" class="form-control">
          </div>
          <div>
            <label for="endDate" class="form-label">End Date</label>
            <input type="date" id="endDate" name="end_date" class="form-control">
          </div>
          <div>
            <input type="hidden" name="report" value="<?php echo $_GET['report']; ?>">
            <button type="submit" class="btn btn-primary w-100">🔍 Filter</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<?php include 'footer.php'; ?>