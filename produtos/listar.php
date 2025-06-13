<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lista de Produtos</title>
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
    <div class="title-container">
      <h1>Produtos</h1>
      <a href="cadastrar.php" class="btn btn-primary">Adicionar Produto</a>
    </div>

    <p>Lista de produtos cadastrados</p>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Categoria</th>
            <th>Preço</th>
            <th>Disponibilidade</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT p.*, c.categoria FROM produtos p
                  JOIN categorias c ON p.categoria_id = c.id";
          $res = mysqli_query($conn, $sql);

          if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
              echo "<tr>
                    <td class='description-cell'>" . htmlspecialchars($row['nome']) . "</td>
                    <td class='description-cell'>" . htmlspecialchars($row['descricao']) . "</td>
                    <td>" . htmlspecialchars($row['categoria']) . "</td>
                    <td>R$ " . number_format($row['preco'], 2, ',', '.') . "</td>
                    <td>" . htmlspecialchars($row['disponibilidade']) . "</td>
                    <td class='action-cell'>
                      <a href='editar.php?id={$row['id']}' class='btn btn-edit'>Editar</a>
                      <a href='excluir.php?id={$row['id']}' class='btn btn-delete' onclick='return confirm(\"Deseja excluir este produto?\")'>Excluir</a>
                    </td>
                  </tr>";
            }
          } else {
            echo "<tr><td colspan='6' class='no-results'>Nenhum resultado encontrado</td></tr>";
          }
          mysqli_free_result($res);
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>