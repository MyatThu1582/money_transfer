<?php include 'connect.php'; ?>
<div class="mx-5 py-4">
  <!-- 🔥 Report Header Title -->
  <div class="mb-4 d-flex">
    <div class="col-11">
      <h2 class="mb-0">
        📊 
        <?php 
            if($_GET['report'] == 'cash_in_percentage'){
                echo "ငွေသွင်းဝန်ဆောင်ခ";
            } elseif($_GET['report'] == 'cash_out_percentage'){
                echo "ငွေထုတ်ဝန်ဆောင်ခ";
            } elseif($_GET['report'] == 'total_cash_in'){
                echo "ငွေဝင်စာရင်း";
            } elseif($_GET['report'] == 'total_cash_out'){
                echo "ထွက်ငွေစာရင်း";
            }
        ?>
        Report
      </h2>
    </div>
    <div class="col">
      <a href="index.php"><button type="button" name="button" class="btn btn-secondary mt-1 ms-2">နောက်သို့</button></a>  
    </div>
  </div>

  <!-- 🔀 2-Column Layout: Table + Filter -->
  <div class="row">
    <!-- 📊 Report Table (Left Side) -->
    <div class="col-lg-9 mb-4">
    <?php 
    // Cash In Percsntage Report
      if($_GET['report'] == 'cash_in_percentage'){
        if(isset($_POST['filter']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])){
          $start_date = $_POST['start_date'];
          $end_date = $_POST['end_date'];
          $cash_in_percentagestmt = $pdo->prepare("SELECT * FROM cashbook WHERE in_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");          
        }else{
          $cash_in_percentagestmt = $pdo->prepare("SELECT * FROM cashbook WHERE in_amt != 0");
        }
        $cash_in_percentagestmt->execute();
        $cash_in_percentagedatas = $cash_in_percentagestmt->fetchAll();
    ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
                <th class="text-center">စဥ်</th>
                <th class="text-center">ရက်စွဲ</th>
                <th class="text-center" style="width: 300px;">အကြောင်းအရာ</th>
                <th class="text-center">အဝင်ငွေပမာဏ</th>
                <th class="text-center">ဝန်ဆောင်ခ ( % ) </th>
                <th class="text-center">ဝန်ဆောင်ခပမာဏ</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            foreach($cash_in_percentagedatas as $cash_in_percentagedata){
                $id = $cash_in_percentagedata['id'];
                $percentagestmt = $pdo->prepare("SELECT * FROM percentage WHERE cash_id = '$id'");
                $percentagestmt->execute();
                $percentagedatas = $percentagestmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
              <td class="text-center"><?php echo $no; ?></td>
              <td class="text-center"><?php echo date('d-m-Y', strtotime($cash_in_percentagedata['date'])) ?></td>
              <td class="text-center"><?php echo $cash_in_percentagedata['description']; ?></td>
              <td class="text-center"><?php echo $cash_in_percentagedata['in_amt']; ?></td>
              <td class="text-center"><?php echo $percentagedatas['percentage']; ?></td>
              <td class="text-center" style="width: 150px;"><?php echo $percentagedatas['percentage_amt']; ?></td>
            </tr>
            <?php
            $no++;
            }
            ?>
            <!-- More rows -->
          </tbody>
        </table>
        <?php 
          if(isset($_POST['filter']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])){
            $total_percentagestmt = $pdo->prepare("SELECT SUM(percentage_amt) AS total_percentage_amt FROM percentage WHERE inorout = 'in' AND date BETWEEN '$start_date' AND '$end_date'");
          }else{
            $total_percentagestmt = $pdo->prepare("SELECT SUM(percentage_amt) AS total_percentage_amt FROM percentage WHERE inorout = 'in'");
          }
            $total_percentagestmt->execute();
            $total_percentagedatas = $total_percentagestmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
                <th class="text-center">စုစုပေါင်းငွေသွင်းဝန်ဆောင်ခ</th>
                <th class="text-center" style="width: 150px;"><?php echo $total_percentagedatas['total_percentage_amt']; ?></th>
            </tr>
          </thead>
        </table>
      </div>
    <?php
    // Cash Out Percentage Report
      }elseif($_GET['report'] == 'cash_out_percentage'){
        if(isset($_POST['filter']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])){
          $start_date = $_POST['start_date'];
          $end_date = $_POST['end_date'];
          $cash_out_percentagestmt = $pdo->prepare("SELECT * FROM cashbook WHERE out_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");          
        }else{
          $cash_out_percentagestmt = $pdo->prepare("SELECT * FROM cashbook WHERE out_amt != 0");
        }
        $cash_out_percentagestmt->execute();
        $cash_out_percentagedatas = $cash_out_percentagestmt->fetchAll();
    ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
                <th class="text-center">စဥ်</th>
                <th class="text-center">ရက်စွဲ</th>
                <th class="text-center" style="width: 300px;">အကြောင်းအရာ</th>
                <th class="text-center">အထွက်ငွေပမာဏ</th>
                <th class="text-center">ဝန်ဆောင်ခ ( % ) </th>
                <th class="text-center">ဝန်ဆောင်ခပမာဏ</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            foreach($cash_out_percentagedatas as $cash_out_percentagedata){
                $id = $cash_out_percentagedata['id'];
                $percentagestmt = $pdo->prepare("SELECT * FROM percentage WHERE cash_id = '$id'");
                $percentagestmt->execute();
                $percentagedatas = $percentagestmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
              <td class="text-center"><?php echo $no; ?></td>
              <td class="text-center"><?php echo date('d-m-Y', strtotime($cash_out_percentagedata['date'])) ?></td>
              <td class="text-center"><?php echo $cash_out_percentagedata['description']; ?></td>
              <td class="text-center"><?php echo $cash_out_percentagedata['out_amt']; ?></td>
              <td class="text-center"><?php echo $percentagedatas['percentage']; ?></td>
              <td class="text-center" style="width: 150px;"><?php echo $percentagedatas['percentage_amt']; ?></td>
            </tr>
            <?php
            $no++;
            }
            ?>
            <!-- More rows -->
          </tbody>
        </table>
        <?php 
          if(isset($_POST['filter']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])){
            $total_percentagestmt = $pdo->prepare("SELECT SUM(percentage_amt) AS total_percentage_amt FROM percentage WHERE inorout = 'out' AND date BETWEEN '$start_date' AND '$end_date'");
          }else{
            $total_percentagestmt = $pdo->prepare("SELECT SUM(percentage_amt) AS total_percentage_amt FROM percentage WHERE inorout = 'out'");
          }
            $total_percentagestmt->execute();
            $total_percentagedatas = $total_percentagestmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
                <th class="text-center">စုစုပေါင်းငွေထုတ်ဝန်ဆောင်ခ</th>
                <th class="text-center" style="width: 150px;"><?php echo $total_percentagedatas['total_percentage_amt']; ?></th>
            </tr>
          </thead>
        </table>
      </div>
    <?php
    // Total Cash In Amount Report
      }elseif($_GET['report'] == 'total_cash_in'){
        if(isset($_POST['filter']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])){
          $start_date = $_POST['start_date'];
          $end_date = $_POST['end_date'];
          $total_cash_instmt = $pdo->prepare("SELECT * FROM cashbook WHERE in_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");          
        }else{
          $total_cash_instmt = $pdo->prepare("SELECT * FROM cashbook WHERE in_amt != 0");
        }
        $total_cash_instmt->execute();
        $total_cash_indatas = $total_cash_instmt->fetchAll();
    ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
                <th class="text-center">စဥ်</th>
                <th class="text-center">ရက်စွဲ</th>
                <th class="text-center" style="width: 300px;">အကြောင်းအရာ</th>
                <th class="text-center" style="width: 200px;">ဝင်ငွေပမာဏ</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            foreach($total_cash_indatas as $total_cash_indata){
            ?>
            <tr>
              <td class="text-center"><?php echo $no; ?></td>
              <td class="text-center"><?php echo date('d-m-Y', strtotime($total_cash_indata['date'])) ?></td>
              <td class="text-center"><?php echo $total_cash_indata['description']; ?></td>
              <td class="text-center"><?php echo $total_cash_indata['in_amt']; ?></td>
            </tr>
            <?php
            $no++;
            }
            ?>
            <!-- More rows -->
          </tbody>
        </table>
        <?php 
          if(isset($_POST['filter']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])){
            $total_cash_instmt = $pdo->prepare("SELECT SUM(in_amt) AS total_in_amt FROM cashbook WHERE in_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");
          }else{
            $total_cash_instmt = $pdo->prepare("SELECT SUM(in_amt) AS total_in_amt FROM cashbook WHERE in_amt != 0");
          }
            $total_cash_instmt->execute();
            $total_cash_indatas = $total_cash_instmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
                <th class="text-center">စုစုပေါင်းငွေဝင်စာရင်း</th>
                <th class="text-center" style="width: 200px;"><?php echo $total_cash_indatas['total_in_amt']; ?></th>
            </tr>
          </thead>
        </table>
      </div>
    <?php
    // Total Cash Out Amount Report
      }elseif($_GET['report'] == 'total_cash_out'){
        if(isset($_POST['filter']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])){
          $start_date = $_POST['start_date'];
          $end_date = $_POST['end_date'];
          $total_cash_outstmt = $pdo->prepare("SELECT * FROM cashbook WHERE out_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");          
        }else{
          $total_cash_outstmt = $pdo->prepare("SELECT * FROM cashbook WHERE out_amt != 0");
        }
        $total_cash_outstmt->execute();
        $total_cash_outdatas = $total_cash_outstmt->fetchAll();   
    ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
                <th class="text-center">စဥ်</th>
                <th class="text-center">ရက်စွဲ</th>
                <th class="text-center" style="width: 300px;">အကြောင်းအရာ</th>
                <th class="text-center" style="width: 200px;">ထွက်ငွေပမာဏ</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            foreach($total_cash_outdatas as $total_cash_outdata){
            ?>
            <tr>
              <td class="text-center"><?php echo $no; ?></td>
              <td class="text-center"><?php echo date('d-m-Y', strtotime($total_cash_outdata['date'])) ?></td>
              <td class="text-center"><?php echo $total_cash_outdata['description']; ?></td>
              <td class="text-center"><?php echo $total_cash_outdata['out_amt']; ?></td>
            </tr>
            <?php
            $no++;
            }
            ?>
            <!-- More rows -->
          </tbody>
        </table>
        <?php 
          if(isset($_POST['filter']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])){
            $total_cash_outstmt = $pdo->prepare("SELECT SUM(out_amt) AS total_out_amt FROM cashbook WHERE out_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");
          }else{
            $total_cash_outstmt = $pdo->prepare("SELECT SUM(out_amt) AS total_out_amt FROM cashbook WHERE out_amt != 0");
          }
            $total_cash_outstmt->execute();
            $total_cash_outdatas = $total_cash_outstmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
                <th class="text-center">စုစုပေါင်းထွက်ငွေစာရင်း</th>
                <th class="text-center" style="width: 200px;"><?php echo $total_cash_outdatas['total_out_amt']; ?></th>
            </tr>
          </thead>
        </table>
      </div>
      <?php
      }
      ?>
    </div>

    <!-- 🎛️ Filter Form (Right Side) -->
    <div class="col-lg-3">
      <div class="bg-light rounded-3 p-4 shadow-sm">
        <h5 class="mb-3">Filter Reports</h5>
        <form method="post" class="d-flex flex-column gap-3">
          <div>
            <label for="startDate" class="form-label">Start Date</label>
            <input type="date" id="startDate" name="start_date" class="form-control">
          </div>
          <div>
            <label for="endDate" class="form-label">End Date</label>
            <input type="date" id="endDate" name="end_date" class="form-control">
          </div>
          <div>
            <button type="submit" class="btn btn-primary w-100" name="filter">🔍 Filter</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<?php include 'footer.php'; ?>