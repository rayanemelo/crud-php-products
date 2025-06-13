<?php
require_once 'db.php';

session_start();
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['senha'])) {
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);

        if (empty($email) || empty($senha)) {
            $mensagem = "Preencha todos os campos.";
        } else {
            $query = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                $mensagem = "Erro na preparação da consulta: " . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($row = mysqli_fetch_assoc($result)) {
                    if (password_verify($senha, $row['senha'])) {
                        $_SESSION['usuario'] = $row['email'];
                        header("Location: produtos/listar.php");
                        exit;
                    } else {
                        $mensagem = "Senha incorreta.";
                    }
                } else {
                    $mensagem = "Usuário não encontrado.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    } else {
        $mensagem = "Requisição inválida.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crud</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-wrapper">
        <form class="login-form" action="" method="POST">
            <h2>Entrar</h2>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Digite seu email"
                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required />
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required />
            </div>
            <?php if (!empty($mensagem)): ?>
                <div class="mensagem">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>
            <button type="submit">Acessar</button>
            <div class="register-container">
                <span>Não possui conta?</span>
                <a href="cadastro.php">
                    <span class="register-button">Cadastre-se</span>
                </a>
            </div>

        </form>
    </div>
</body>

</html>