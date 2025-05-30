<?php include 'connect.php'; ?>
<?php

$report = $_SESSION['report'];
$start_date = $_SESSION['start_date'];
$end_date = $_SESSION['end_date'];
if(!empty($_SESSION['payment_category'])){
  $payment_category = $_SESSION['payment_category'];
  $paymentstmt = $pdo->prepare("SELECT * FROM payment_categories WHERE id='$payment_category'");
  $paymentstmt->execute();
  $paymentdatas = $paymentstmt->fetch(PDO::FETCH_ASSOC);
  $payment_name = $paymentdatas['name'];
}else{
  $payment_name = '';
}


?>
<div class="mx-5 py-4">
  <!-- 🔥 Report Header Title -->
  <div class="mb-4 d-flex">
    <div class="col-11">
      <h2 class="mb-0">
        📊 
        <?php 
            if($report == 'cash_in_percentage'){
                echo $payment_name . " ငွေသွင်းဝန်ဆောင်ခ" . " From " . date('d-m-Y', strtotime($start_date)) . " To " . date('d-m-Y', strtotime($end_date));
            } elseif($report == 'cash_out_percentage'){
                echo "ငွေထုတ်ဝန်ဆောင်ခ";
            } elseif($report == 'total_cash_in'){
                echo "ငွေဝင်စာရင်း";
            } elseif($report == 'total_cash_out'){
                echo "ထွက်ငွေစာရင်း";
            } elseif($report == 'total_cash_in_out'){
                echo "ငွေအဝင်/အထွက်စာရင်း";
            } elseif($report == 'balance'){
                echo "လက်ကျန်ငွေစာရင်း";
            }
        ?>
      </h2>
    </div>
    <div class="col-1">
      <button onclick="window.print()" class="btn btn-success no-print">Print</button> 
    </div>
  </div>

  <!-- 🔀 2-Column Layout: Table + Filter -->
  <div class="row">
    <div class="col-1"></div>
    <!-- 📊 Report Table (Left Side) -->
    <div class="col-lg-10 mb-4">
    <?php 
    // Cash In Percsntage Report
      if($report == 'cash_in_percentage'){

        // Payment Categories, Start Date, End Date
        if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date']) && !empty($_SESSION['payment_category'])){
          $start_date = $_SESSION['start_date'];
          $end_date = $_SESSION['end_date'];
          $payment_category = $_SESSION['payment_category'];
          $cash_in_percentagestmt = $pdo->prepare("SELECT * FROM cashbook WHERE in_amt != 0 AND date BETWEEN '$start_date' AND '$end_date' AND category_id='$payment_category'");          
        // Start Date, End Date 
        }elseif(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
          $start_date = $_SESSION['start_date'];
          $end_date = $_SESSION['end_date'];
          $cash_in_percentagestmt = $pdo->prepare("SELECT * FROM cashbook WHERE in_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");           
        }
        $cash_in_percentagestmt->execute();
        $cash_in_percentagedatas = $cash_in_percentagestmt->fetchAll();
    ?>
    <div class="table-responsive mb-3">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light sticky-top">
            <tr>
                <th class="text-center">စဥ်</th>
                <th class="text-center">ရက်စွဲ</th>
                <!-- <th class="text-center" style="width: 300px;">အကြောင်းအရာ</th> -->
                <th class="text-center" style="width: 200px;">အမျိုးအစား</th>
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
                $payment_category = $cash_in_percentagedata['category_id'];
                $percentagestmt = $pdo->prepare("SELECT * FROM percentage WHERE cash_id = '$id'");
                $percentagestmt->execute();
                $percentagedatas = $percentagestmt->fetch(PDO::FETCH_ASSOC);
                
                $paymentstmt = $pdo->prepare("SELECT * FROM payment_categories WHERE id='$payment_category'");
                $paymentstmt->execute();
                $paymentdatas = $paymentstmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
              <td class="text-center"><?php echo $no; ?></td>
              <td class="text-center"><?php echo date('d-m-Y', strtotime($cash_in_percentagedata['date'])) ?></td>
              <td class="text-center"><?php echo $paymentdatas['name']; ?></td>
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
      </div>
        <?php 
        // Payment Categories, Start Date, End Date
          if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date']) && !empty($_SESSION['payment_category'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $payment_category = $_SESSION['payment_category'];
            $total_percentagestmt = $pdo->prepare("SELECT SUM(percentage_amt) AS total_percentage_amt FROM percentage WHERE inorout = 'in' AND date BETWEEN '$start_date' AND '$end_date' AND category_id='$payment_category'");
        // Start Date, End Date
          }elseif(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $total_percentagestmt = $pdo->prepare("SELECT SUM(percentage_amt) AS total_percentage_amt FROM percentage WHERE inorout = 'in' AND date BETWEEN '$start_date' AND '$end_date'");
        // Payment Categories
          }elseif(!empty($_SESSION['payment_category'])){
            $payment_category = $_SESSION['payment_category'];
            $total_percentagestmt = $pdo->prepare("SELECT SUM(percentage_amt) AS total_percentage_amt FROM percentage WHERE inorout = 'in' AND category_id='$payment_category'");
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
    
    <?php
    // Cash Out Percentage Report
      }elseif($report == 'cash_out_percentage'){
        
        // Payment Categories, Start Date, End Date
        if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date']) && !empty($_SESSION['payment_category'])){
          $start_date = $_SESSION['start_date'];
          $end_date = $_SESSION['end_date'];
          $payment_category = $_SESSION['payment_category'];
          $cash_out_percentagestmt = $pdo->prepare("SELECT * FROM cashbook WHERE out_amt != 0 AND date BETWEEN '$start_date' AND '$end_date' AND category_id = '$payment_category'");          
          // Start Date, End Date
        }elseif(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
          $start_date = $_SESSION['start_date'];
          $end_date = $_SESSION['end_date'];
          $cash_out_percentagestmt = $pdo->prepare("SELECT * FROM cashbook WHERE out_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");          
         }
        $cash_out_percentagestmt->execute();
        $cash_out_percentagedatas = $cash_out_percentagestmt->fetchAll();
    ?>
    <div class="table-responsive  mb-3">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light sticky-top">
            <tr>
                <th class="text-center">စဥ်</th>
                <th class="text-center">ရက်စွဲ</th>
                <!-- <th class="text-center" style="width: 300px;">အကြောင်းအရာ</th> -->
                <th class="text-center" style="width: 200px;">အမျိုးအစား</th>
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
                $payment_category = $cash_out_percentagedata['category_id'];

                $percentagestmt = $pdo->prepare("SELECT * FROM percentage WHERE cash_id = '$id'");
                $percentagestmt->execute();
                $percentagedatas = $percentagestmt->fetch(PDO::FETCH_ASSOC);

                $paymentstmt = $pdo->prepare("SELECT * FROM payment_categories WHERE id='$payment_category'");
                $paymentstmt->execute();
                $paymentdatas = $paymentstmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
              <td class="text-center"><?php echo $no; ?></td>
              <td class="text-center"><?php echo date('d-m-Y', strtotime($cash_out_percentagedata['date'])) ?></td>
              <td class="text-center"><?php echo $paymentdatas['name']; ?></td>
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
      </div>
        <?php
        // Payment Categories, Start Date, End Date
          if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date']) && !empty($_SESSION['payment_category'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $payment_category = $_SESSION['payment_category'];
            $total_percentagestmt = $pdo->prepare("SELECT SUM(percentage_amt) AS total_percentage_amt FROM percentage WHERE inorout = 'out' AND date BETWEEN '$start_date' AND '$end_date' AND category_id='$payment_category'");
        // Payment Categories, Start Date, End Date
          }elseif(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $total_percentagestmt = $pdo->prepare("SELECT SUM(percentage_amt) AS total_percentage_amt FROM percentage WHERE inorout = 'out' AND date BETWEEN '$start_date' AND '$end_date'");
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
    
    <?php
    // Total Cash In Amount Report
      }elseif($report == 'total_cash_in'){

        // Payment Categories, Start Date, End Date
        if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date']) && !empty($_SESSION['payment_category'])){
          $start_date = $_SESSION['start_date'];
          $end_date = $_SESSION['end_date'];
          $payment_category = $_SESSION['payment_category'];
          $total_cash_instmt = $pdo->prepare("SELECT * FROM cashbook WHERE in_amt != 0 AND date BETWEEN '$start_date' AND '$end_date' AND category_id='$payment_category'");          
        // Start Date, End Date
        }elseif(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
          $start_date = $_SESSION['start_date'];
          $end_date = $_SESSION['end_date'];
          $total_cash_instmt = $pdo->prepare("SELECT * FROM cashbook WHERE in_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");          
        }
        $total_cash_instmt->execute();
        $total_cash_indatas = $total_cash_instmt->fetchAll();
    ?>
    <div class="table-responsive  mb-3">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light sticky-top">
            <tr>
                <th class="text-center">စဥ်</th>
                <th class="text-center">ရက်စွဲ</th>
                <th class="text-center" style="width: 300px;">အကြောင်းအရာ</th>
                <th class="text-center" style="width: 200px;">အမျိုးအစား</th>
                <th class="text-center" style="width: 200px;">ဝင်ငွေပမာဏ</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            foreach($total_cash_indatas as $total_cash_indata){
                $payment_category = $total_cash_indata['category_id'];
                
                $paymentstmt = $pdo->prepare("SELECT * FROM payment_categories WHERE id='$payment_category'");
                $paymentstmt->execute();
                $paymentdatas = $paymentstmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
              <td class="text-center"><?php echo $no; ?></td>
              <td class="text-center"><?php echo date('d-m-Y', strtotime($total_cash_indata['date'])) ?></td>
              <td class="text-center"><?php echo $total_cash_indata['description']; ?></td>
              <td class="text-center"><?php echo $paymentdatas['name']; ?></td>
              <td class="text-center"><?php echo $total_cash_indata['in_amt']; ?></td>
            </tr>
            <?php
            $no++;
            }
            ?>
            <!-- More rows -->
          </tbody>
        </table>
      </div>
        <?php 
        // Payment Categories, Start Date, End Date
          if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date']) && !empty($_SESSION['payment_category'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $payment_category = $_SESSION['payment_category'];
            $total_cash_instmt = $pdo->prepare("SELECT SUM(in_amt) AS total_in_amt FROM cashbook WHERE in_amt != 0 AND date BETWEEN '$start_date' AND '$end_date' AND categorY_id='$payment_category'");
        // Start Date, End Date
          }elseif(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $total_cash_instmt = $pdo->prepare("SELECT SUM(in_amt) AS total_in_amt FROM cashbook WHERE in_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");
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

    <?php
    // Total Cash Out Amount Report
      }elseif($report == 'total_cash_out'){

        // Payment Categories, Start Date, End Date
        if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date']) && !empty($_SESSION['payment_category'])){
          $start_date = $_SESSION['start_date'];
          $end_date = $_SESSION['end_date'];
          $payment_category = $_SESSION['payment_category'];
          $total_cash_outstmt = $pdo->prepare("SELECT * FROM cashbook WHERE out_amt != 0 AND date BETWEEN '$start_date' AND '$end_date' AND category_id='$payment_category'");          
        // Start Date, End Date
        }elseif(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
          $start_date = $_SESSION['start_date'];
          $end_date = $_SESSION['end_date'];
          $total_cash_outstmt = $pdo->prepare("SELECT * FROM cashbook WHERE out_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");          
        }
        $total_cash_outstmt->execute();
        $total_cash_outdatas = $total_cash_outstmt->fetchAll();   
    ?>
    <div class="table-responsive  mb-3">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light sticky-top">
            <tr>
                <th class="text-center">စဥ်</th>
                <th class="text-center">ရက်စွဲ</th>
                <th class="text-center" style="width: 300px;">အကြောင်းအရာ</th>
                <th class="text-center" style="width: 200px;">အမျိုးအစား</th>
                <th class="text-center" style="width: 200px;">ထွက်ငွေပမာဏ</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            foreach($total_cash_outdatas as $total_cash_outdata){
                $payment_category = $total_cash_outdata['category_id'];
                
                $paymentstmt = $pdo->prepare("SELECT * FROM payment_categories WHERE id='$payment_category'");
                $paymentstmt->execute();
                $paymentdatas = $paymentstmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
              <td class="text-center"><?php echo $no; ?></td>
              <td class="text-center"><?php echo date('d-m-Y', strtotime($total_cash_outdata['date'])) ?></td>
              <td class="text-center"><?php echo $total_cash_outdata['description']; ?></td>
              <td class="text-center"><?php echo $paymentdatas['name']; ?></td>
              <td class="text-center"><?php echo $total_cash_outdata['out_amt']; ?></td>
            </tr>
            <?php
            $no++;
            }
            ?>
            <!-- More rows -->
          </tbody>
        </table>
      </div>
        <?php 
        // Payment Categories, Start Date, End Date
          if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date']) && !empty($_SESSION['payment_category'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $payment_category = $_SESSION['payment_category'];
            $total_cash_outstmt = $pdo->prepare("SELECT SUM(out_amt) AS total_out_amt FROM cashbook WHERE out_amt != 0 AND date BETWEEN '$start_date' AND '$end_date' AND category_id='$payment_category'");
        // Start Date, End Date
          }elseif(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $total_cash_outstmt = $pdo->prepare("SELECT SUM(out_amt) AS total_out_amt FROM cashbook WHERE out_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");
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

      <?php
      // Cash In && Out Amount Report
      }elseif($report == 'total_cash_in_out'){
        // Payment Categories, Start Date, End Date
          if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date']) && !empty($_SESSION['payment_category'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $payment_category = $_SESSION['payment_category'];
            $cash_in_outstmt = $pdo->prepare("SELECT * FROM cashbook WHERE date BETWEEN '$start_date' AND '$end_date' AND category_id='$payment_category'");
        // Payment Categories, Start Date, End Date
          }elseif(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $cash_in_outstmt = $pdo->prepare("SELECT * FROM cashbook WHERE date BETWEEN '$start_date' AND '$end_date'");
          }
          $cash_in_outstmt->execute();
          $cash_in_outdatas = $cash_in_outstmt->fetchall();
        ?>
        <div class="table-responsive  mb-2">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light sticky-top">
            <tr>
                <th class="text-center">စဥ်</th>
                <th class="text-center">ရက်စွဲ</th>
                <th class="text-center" style="width: 300px;">အကြောင်းအရာ</th>
                <th class="text-center" style="width: 150px;">အမျိုးအစား</th>
                <th class="text-center" style="width: 150px;">ဝင်ငွေပမာဏ</th>
                <th class="text-center" style="width: 150px;">ထွက်ငွေပမာဏ</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            foreach($cash_in_outdatas as $cash_in_outdata){
                $payment_category = $cash_in_outdata['category_id'];
                
                $paymentstmt = $pdo->prepare("SELECT * FROM payment_categories WHERE id='$payment_category'");
                $paymentstmt->execute();
                $paymentdatas = $paymentstmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
              <td class="text-center"><?php echo $no; ?></td>
              <td class="text-center"><?php echo date('d-m-Y', strtotime($cash_in_outdata['date'])) ?></td>
              <td class="text-center"><?php echo $cash_in_outdata['description']; ?></td>
              <td class="text-center"><?php echo $paymentdatas['name']; ?></td>
              <td class="text-center"><?php echo $cash_in_outdata['in_amt']; ?></td>
              <td class="text-center"><?php echo $cash_in_outdata['out_amt']; ?></td>
            </tr>
            <?php
            $no++;
            }
            ?>
            <!-- More rows -->
          </tbody>
        </table>
      </div>
        <?php 
        // Payment Categories, Start Date, End Date
          if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date']) && !empty($_SESSION['payment_category'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $payment_category = $_SESSION['payment_category'];
            $total_cash_instmt = $pdo->prepare("SELECT SUM(in_amt) AS total_in_amt FROM cashbook WHERE in_amt != 0 AND date BETWEEN '$start_date' AND '$end_date' AND category_id='$payment_category'");
            $total_cash_outstmt = $pdo->prepare("SELECT SUM(out_amt) AS total_out_amt FROM cashbook WHERE out_amt != 0 AND date BETWEEN '$start_date' AND '$end_date' AND category_id='$payment_category'");
        // Start Date, End Date
          }elseif(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $total_cash_instmt = $pdo->prepare("SELECT SUM(in_amt) AS total_in_amt FROM cashbook WHERE in_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");
            $total_cash_outstmt = $pdo->prepare("SELECT SUM(out_amt) AS total_out_amt FROM cashbook WHERE out_amt != 0 AND date BETWEEN '$start_date' AND '$end_date'");
          }
            $total_cash_instmt->execute();
            $total_cash_indatas = $total_cash_instmt->fetch(PDO::FETCH_ASSOC);
            $total_cash_outstmt->execute();
            $total_cash_outdatas = $total_cash_outstmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
                <th class="text-center">စုစုပေါင်းငွေအဝင်/အထွက်စာရင်း</th>
                <th class="text-center" style="width: 150px;"><?php echo $total_cash_indatas['total_in_amt']; ?></th>
                <th class="text-center" style="width: 150px;"><?php echo $total_cash_outdatas['total_out_amt']; ?></th>
            </tr>
          </thead>
        </table>

      <?php
      // Balance Report  
      }elseif($report == 'balance'){
        ?>
        <div class="table-responsive  mb-3">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light sticky-top">
            <tr>
                <th class="text-center" style="width: 150px;">စဥ်</th>
                <th class="text-center">ရက်စွဲ</th>
                <th class="text-center">အမျိုးအစား</th>
                <th class="text-center" style="width: 200px;">လက်ကျန်ငွေပမာဏ</th>
            </tr>
          </thead>
          <tbody>
        <?php
        // Payment Categories, Start Date, End Date
        if(!empty($_SESSION['payment_category']) && !empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
          $start_date = $_SESSION['start_date'];
          $end_date = $_SESSION['end_date'];
          $payment_category = $_SESSION['payment_category'];
          $datestmt = $pdo->prepare("SELECT DISTINCT date FROM cashbook WHERE date BETWEEN '$start_date' AND '$end_date' AND category_id='$payment_category'"); 
          $datestmt->execute();
          $datedatas = $datestmt->fetchAll();
        }

        if(!empty($datedatas)){
            $no = 1;
            foreach($datedatas as $datedata){
                $date = $datedata['date'];
                // $payment_category = $balancedata['category_id'];

                $balancestmt = $pdo->prepare("SELECT * FROM cashbook WHERE date='$date' AND category_id='$payment_category' ORDER BY id DESC");
                $balancestmt->execute();
                $balancedatas = $balancestmt->fetch(PDO::FETCH_ASSOC);
                $totalbalance = 0;
                $totalbalance += $balancedatas['balance'];
              
                $paymentstmt = $pdo->prepare("SELECT * FROM payment_categories WHERE id='$payment_category'");
                $paymentstmt->execute();
                $paymentdatas = $paymentstmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <tr>
                  <td class="text-center"><?php echo $no; ?></td>
                  <td class="text-center"><?php echo date('d-m-Y', strtotime($date)) ?></td>
                  <td class="text-center"><?php echo $paymentdatas['name']; ?></td>
                  <td class="text-center"><?php echo $balancedatas['balance']; ?></td>
                </tr>
            <?php
            $no++;
            }
          }
            ?>
            <!-- More rows -->
          </tbody>
        </table>
      </div>
        <!-- <?php 
        // Payment Categories, Start Date, End Date
          if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date']) && !empty($_SESSION['payment_category'])){
            $start_date = $_SESSION['start_date'];
            $end_date = $_SESSION['end_date'];
            $payment_category = $_SESSION['payment_category'];
            $total_balancestmt = $pdo->prepare("SELECT SUM(balance) AS total_balance FROM cashbook WHERE date BETWEEN '$start_date' AND '$end_date' AND category_id='$payment_category'");
            $total_balancestmt->execute();
            $total_balancedata = $total_balancestmt->fetch(PDO::FETCH_ASSOC);
          }
        ?>
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
                <th class="text-center">လက်ကျန်ငွေစာရင်း</th>
                <th class="text-center" style="width: 200px;"><?php if(!empty($total_balancedata)){ echo $total_balancedata['total_balance']; }else{ echo "-"; } ?></th>
            </tr>
          </thead>
        </table> -->

      <?php
      }
      ?>
    </div>
  </div>
</div>



<?php include 'footer.php'; ?>