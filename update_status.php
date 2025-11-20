<?php
require_once 'db.php';

$id     = (int)($_POST['id'] ?? 0);
$next   = $_POST['next_status'] ?? '';

$allowed = ['Received','Washing','Finished','Delivered'];
if (!$id || !in_array($next, $allowed, true)) { die('Invalid data'); }

// Get current status
$res = $mysqli->prepare("SELECT status FROM orders WHERE id=?");
$res->bind_param('i',$id);
$res->execute();
$res->bind_result($current);
if(!$res->fetch()){ die('Order not found'); }
$res->close();

$flow = ['Received','Washing','Finished','Delivered'];
$currIdx = array_search($current,$flow,true);
$nextIdx = array_search($next,$flow,true);

if ($nextIdx === false || $currIdx === false || $nextIdx - $currIdx !== 1) {
  die('Can only move one step at a time');
}

$upd = $mysqli->prepare("UPDATE orders SET status=? WHERE id=?");
$upd->bind_param('si',$next,$id);
$upd->execute();
$upd->close();

header('Location: index.php?msg=updated');
exit;
