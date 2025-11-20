<?php require_once 'db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Order | Laundry Book</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <div class="card">
    <h2>Add Order</h2>
    <form action="save_order.php" method="post">
      <div class="row">
        <div>
          <label>Customer Name</label>
          <input required name="customer_name" placeholder="e.g. John Smith">
        </div>
        <div>
          <label>Phone Number</label>
          <input required name="phone" placeholder="e.g. 08x-xxx-xxxx">
        </div>
      </div>
      <div class="row">
        <div>
          <label>Item Count</label>
          <input required type="number" min="1" name="item_count" value="1">
        </div>
        <div>
          <label>Service Type</label>
          <select name="service_type" required>
            <option value="Wash">Wash</option>
            <option value="Dry">Dry</option>
            <option value="Iron">Iron</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div>
          <label>Price (THB)</label>
          <input required type="number" step="0.01" min="0" name="price" placeholder="e.g. 120">
        </div>
        <div>
          <label>Initial Status</label>
          <input value="Received" disabled>
        </div>
      </div>
      <br>
      <button type="submit">Save</button>
      <a href="index.php"><button type="button" class="secondary">Back</button></a>
    </form>
  </div>
</div>
</body>
</html>
