<?php
require_once '../db.php';

$id = $_GET['id'] ?? null;

if ($id) {
  $stmt = mysqli_prepare($conn, "DELETE FROM produtos WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
}

header("Location: listar.php");
exit;
