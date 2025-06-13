<?php
require_once 'db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['confirmar_senha'])) {
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);
        $confirmar_senha = trim($_POST['confirmar_senha']);

        if (empty($email) || empty($senha) || empty($confirmar_senha)) {
            $mensagem = "Preencha todos os campos.";
        } elseif ($senha !== $confirmar_senha) {
            $mensagem = "As senhas não correspondem.";
        } else {
            $query = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                $mensagem = "Erro na preparação da consulta: " . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    $mensagem = "Este email já está cadastrado.";
                } else {
                    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                    $query = "INSERT INTO usuarios (email, senha) VALUES (?, ?)";
                    $stmt = mysqli_prepare($conn, $query);
                    if (!$stmt) {
                        $mensagem = "Erro na preparação da consulta: " . mysqli_error($conn);
                    } else {
                        mysqli_stmt_bind_param($stmt, "ss", $email, $senhaHash);
                        if (mysqli_stmt_execute($stmt)) {
                            header("Location: login.php");
                            exit();
                        } else {
                            $mensagem = "Erro ao cadastrar usuário: " . mysqli_error($conn);
                        }
                    }
                }
                mysqli_stmt_close($stmt);
            }
        }
    } else {
        $mensagem = "Requisição inválida. Campos ausentes.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-wrapper">
        <form class="login-form" action="" method="POST">
            <h2>Cadastrar</h2>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Digite seu email"
                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required />
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required />
            </div>
            <div class="input-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme sua senha"
                    required />
            </div>

            <?php if (!empty($mensagem)): ?>
                <div class="mensagem <?php echo (strpos($mensagem, 'sucesso') !== false ? 'sucesso' : ''); ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <button type="submit">Cadastrar</button>
            <div class="register-container">
                <span>Já tem uma conta?</span>
                <a href="login.php">
                    <span>Faça login</span>
                </a>
            </div>
        </form>
    </div>
</body>

</html>