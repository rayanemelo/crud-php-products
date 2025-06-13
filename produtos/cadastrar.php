<?php
require_once '../db.php';

$categorias = [];
$result = mysqli_query($conn, "SELECT * FROM categorias");
while ($row = mysqli_fetch_assoc($result)) {
  $categorias[] = $row;
}

$nome = $descricao = $preco = $disponibilidade = '';
$categoria_id = '';
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = trim($_POST['nome']);
  $descricao = trim($_POST['descricao']);
  $categoria_id = isset($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null;
  $preco = isset($_POST['preco']) ? (float) $_POST['preco'] : null;
  $disponibilidade = $_POST['disponibilidade'] ?? '';

  if (empty($nome))
    $erros[] = "O nome do produto é obrigatório.";
  if (empty($categoria_id))
    $erros[] = "Selecione uma categoria.";
  if ($preco === null || $preco <= 0)
    $erros[] = "Informe um preço válido.";
  if (!in_array($disponibilidade, ['disponível', 'indisponível'])) {
    $erros[] = "Informe a disponibilidade corretamente.";
  }

  if (empty($erros)) {
    $sql = "INSERT INTO produtos (nome, descricao, categoria_id, preco, disponibilidade)
                VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssids", $nome, $descricao, $categoria_id, $preco, $disponibilidade);

    if (mysqli_stmt_execute($stmt)) {
      header("Location: listar.php");
      exit;
    } else {
      $erros[] = "Erro ao cadastrar produto.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastrar Produto</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../private.css">
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
    <h1>Cadastrar Produto</h1>
    <p><b>Preencha os detalhes do produto abaixo:</b></p>

    <form method="POST" class="form" novalidate>
      <label>Nome do Produto:</label>
      <input type="text" name="nome" value="<?= htmlspecialchars($nome) ?>" required>

      <label>Descrição:</label>
      <textarea name="descricao" rows="4"><?= htmlspecialchars($descricao) ?></textarea>

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
      <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($preco) ?>" required min="0.01">

      <label>Disponibilidade:</label>
      <div class="radio-group">
        <label><input type="radio" name="disponibilidade" value="disponível" <?= $disponibilidade === 'disponível' ? 'checked' : '' ?>> Disponível</label>
        <label><input type="radio" name="disponibilidade" value="indisponível" <?= $disponibilidade === 'indisponível' ? 'checked' : '' ?>> Indisponível</label>
      </div>

      <?php if (!empty($erros)): ?>
        <div style="color: red; margin-top: 10px;">
          <ul>
            <?php foreach ($erros as $erro): ?>
              <li><?= htmlspecialchars($erro) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <button type="submit" class="btn btn-primary">Salvar Produto</button>
    </form>
    <div class="back-button">
      <a href="listar.php" class="btn btn-secondary">Voltar</a>
    </div>
  </div>
</body>

</html>