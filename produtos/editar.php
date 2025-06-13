<?php
require_once '../db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
  header("Location: listar.php");
  exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM produtos WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produto = mysqli_fetch_assoc($result);

if (!$produto) {
  header("Location: listar.php");
  exit;
}

$categorias = [];
$cat_result = mysqli_query($conn, "SELECT * FROM categorias");
while ($row = mysqli_fetch_assoc($cat_result)) {
  $categorias[] = $row;
}

$nome = $produto['nome'];
$descricao = $produto['descricao'];
$categoria_id = $produto['categoria_id'];
$preco = $produto['preco'];
$disponibilidade = $produto['disponibilidade'];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = trim($_POST['nome']);
  $descricao = trim($_POST['descricao']);
  $categoria_id = (int) $_POST['categoria_id'];
  $preco = (float) $_POST['preco'];
  $disponibilidade = $_POST['disponibilidade'] ?? '';

  if (empty($nome))
    $erros[] = "O nome é obrigatório.";
  if (empty($categoria_id))
    $erros[] = "Selecione uma categoria.";
  if ($preco <= 0)
    $erros[] = "Preço inválido.";
  if (!in_array($disponibilidade, ['disponível', 'indisponível'])) {
    $erros[] = "Selecione uma disponibilidade válida.";
  }

  if (empty($erros)) {
    $sql = "UPDATE produtos SET nome = ?, descricao = ?, categoria_id = ?, preco = ?, disponibilidade = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssidsi", $nome, $descricao, $categoria_id, $preco, $disponibilidade, $id);

    if (mysqli_stmt_execute($stmt)) {
      header("Location: listar.php");
      exit;
    } else {
      $erros[] = "Erro ao atualizar o produto.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Editar Produto</title>
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
    <h1>Editar Produto</h1>
    <p>Você está editando o produto: <strong><?= htmlspecialchars($nome) ?></strong></p>

    <form method="POST" class="form">
      <label>Nome do Produto:</label>
      <input type="text" name="nome" required value="<?= htmlspecialchars($nome) ?>">

      <label>Descrição:</label>
      <textarea name="descricao"><?= htmlspecialchars($descricao) ?></textarea>

      <label>Categoria:</label>
      <select name="categoria_id" required>
        <option value="">Selecione...</option>
        <?php foreach ($categorias as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $categoria_id ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['categoria']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Preço:</label>
      <input type="number" name="preco" step="0.01" min="0.01" required value="<?= htmlspecialchars($preco) ?>">

      <label>Disponibilidade:</label>
      <div class="radio-group">
        <label><input type="radio" name="disponibilidade" value="disponível" <?= $disponibilidade === 'disponível' ? 'checked' : '' ?>> Disponível</label>
        <label><input type="radio" name="disponibilidade" value="indisponível" <?= $disponibilidade === 'indisponível' ? 'checked' : '' ?>> Indisponível</label>
      </div>

      <?php if (!empty($erros)): ?>
        <div style="color:red">
          <ul>
            <?php foreach ($erros as $erro): ?>
              <li><?= htmlspecialchars($erro) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
    <div class="back-button">
      <a href="listar.php" class="btn btn-secondary">Voltar</a>
    </div>
  </div>
</body>

</html>