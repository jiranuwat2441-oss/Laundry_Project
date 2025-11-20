<?php require_once 'db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Laundry Book</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">

  <div class="card">
    <h2>Laundry Book</h2>
    <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap">
      <form method="get" style="display:flex; gap:8px; flex:1">
        <input name="q" placeholder="Search customer..." value="<?=htmlspecialchars($_GET['q'] ?? '')?>">
        <button type="submit">Search</button>
        <a href="index.php"><button type="button" class="secondary">Clear</button></a>
      </form>
      <a href="add.php"><button>+ Add Order</button></a>
    </div>
    <?php if(isset($_GET['msg'])): ?>
      <p style="margin-top:8px;color:#86efac">✓ Success</p>
    <?php endif; ?>
  </div>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Customer</th>
          <th>Phone</th>
          <th>Items</th>
          <th>Service</th>
          <th>Price (฿)</th>
          <th>Status</th>
          <th>Next Step</th>
          <th>Received At</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $q = trim($_GET['q'] ?? '');
        if ($q !== '') {
          $sql = "SELECT * FROM orders WHERE customer_name LIKE ? ORDER BY id DESC";
          $stmt = $mysqli->prepare($sql);
          $like = "%$q%";
          $stmt->bind_param('s',$like);
        } else {
          $sql = "SELECT * FROM orders ORDER BY id DESC";
          $stmt = $mysqli->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        // แผนที่สำหรับป้ายสถานะและลำดับถัดไป
        $badge = [
          'Received' => 's1',
          'Washing'  => 's2',
          'Finished' => 's3',
          'Delivered'=> 's4'
        ];
        $flowNext = [
          'Received' => 'Washing',
          'Washing'  => 'Finished',
          'Finished' => 'Delivered',
          'Delivered'=> null
        ];

        while($row = $result->fetch_assoc()):
          // ---- ป้องกันค่าว่าง/คีย์ไม่อยู่ในลิสต์ ----
          $status = trim($row['status'] ?? '');
          if ($status === '' || !isset($badge[$status]) || !array_key_exists($status, $flowNext)) {
            $status = 'Received';
          }
          $badgeClass = $badge[$status] ?? '';        // ค่าปลอดภัย ถ้าไม่พบคีย์
          $nextStatus = $flowNext[$status] ?? null;   // ถ้าไปต่อไม่ได้จะเป็น null
          // --------------------------------------------
      ?>
        <tr>
          <td><?= (int)$row['id'] ?></td>
          <td><?= htmlspecialchars($row['customer_name'] ?? '') ?></td>
          <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
          <td><?= (int)($row['item_count'] ?? 0) ?></td>
          <td><?= htmlspecialchars($row['service_type'] ?? '') ?></td>
          <td><?= number_format((float)($row['price'] ?? 0),2) ?></td>

          <td>
            <span class="status <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
          </td>

          <td>
            <?php if($nextStatus): ?>
              <form action="update_status.php" method="post" style="display:flex; gap:6px">
                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                <input type="hidden" name="next_status" value="<?= htmlspecialchars($nextStatus) ?>">
                <button type="submit">Next: <?= htmlspecialchars($nextStatus) ?></button>
              </form>
            <?php else: ?>
              <span style="opacity:.7">Final status</span>
            <?php endif; ?>
          </td>

          <td><?= htmlspecialchars($row['created_at'] ?? '') ?></td>
        </tr>
      <?php endwhile; $stmt->close(); ?>
      </tbody>
    </table>
  </div>

  <div class="card" style="text-align:center;opacity:.7">
    <small>© Laundry Book – Example Project</small>
  </div>

</div>
</body>
</html>
