<?php
require_once '../db.php';
require_once '../protege.php';

$categoria = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $categoria = trim($_POST['categoria']);
  if (empty($categoria)) {
    $erro = "Informe o nome da categoria.";
  } else {
    $stmt = mysqli_prepare($conn, "INSERT INTO categorias (categoria) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $categoria);
    if (mysqli_stmt_execute($stmt)) {
      header("Location: listar.php");
      exit;
    } else {
      $erro = "Erro ao cadastrar categoria.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <title>Cadastrar Categoria</title>
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
    <h1>Cadastrar Categoria</h1>
    <p>
      Preencha o formulário abaixo para cadastrar uma nova categoria.
    </p>

    <?php if (!empty($erro)): ?>
      <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form method="POST" class="form">
      <label>Categoria:</label>
      <input type="text" name="categoria" required value="<?= htmlspecialchars($categoria) ?>">
      <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
    <div class="back-button">
      <a href="listar.php" class="btn btn-secondary">Voltar</a>
    </div>
  </div>
</body>

</html>