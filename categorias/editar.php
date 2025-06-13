<?php
require_once '../db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
  header("Location: listar.php");
  exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM categorias WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$categoria = mysqli_fetch_assoc($result);

if (!$categoria) {
  header("Location: listar.php");
  exit;
}

$erro = '';
$novaCategoria = $categoria['categoria'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $novaCategoria = trim($_POST['categoria']);
  if (empty($novaCategoria)) {
    $erro = "Informe a categoria.";
  } else {
    $stmt = mysqli_prepare($conn, "UPDATE categorias SET categoria = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $novaCategoria, $id);
    if (mysqli_stmt_execute($stmt)) {
      header("Location: listar.php");
      exit;
    } else {
      $erro = "Erro ao atualizar.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <title>Editar Categoria</title>
  <link rel="stylesheet" href="../private.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<header class="header">
  <div class="header-container">
    <div class="logo">
      <span>ProdCat </span> System
    </div>
    <nav>
      <a href="../produtos/listar.php">Produtos</a>
      <a href="../categorias/listar.php">Categorias</a>
      <a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
    </nav>
  </div>
</header>

<body>
  <div class="container">
    <h1>Editar Categoria</h1>
    <p>Você está editando a categoria: <strong><?= htmlspecialchars($categoria['categoria']) ?></strong></p>
    <form method="POST" class="form">
      <label>Categoria:</label>
      <input type="text" name="categoria" required value="<?= htmlspecialchars($novaCategoria) ?>">
      <?php if (!empty($erro)): ?>
        <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
      <?php endif; ?>
      <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
    <div class="back-button">
      <a href="listar.php" class="btn btn-secondary">Voltar</a>
    </div>
  </div>
</body>

</html>