<?php
require_once 'db.php';

$customer_name = trim($_POST['customer_name'] ?? '');
$phone         = trim($_POST['phone'] ?? '');
$item_count    = (int)($_POST['item_count'] ?? 0);
$service_type  = $_POST['service_type'] ?? '';
$price         = (float)($_POST['price'] ?? 0);

if ($customer_name === '' || $phone === '' || $item_count <= 0 || $price < 0) {
  die('Missing information');
}

$sql = "INSERT INTO orders (customer_name, phone, item_count, service_type, price, status)
        VALUES (?, ?, ?, ?, ?, 'Received')";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ssisd', $customer_name, $phone, $item_count, $service_type, $price);
$stmt->execute();
$stmt->close();

header('Location: index.php?msg=created');
exit;
