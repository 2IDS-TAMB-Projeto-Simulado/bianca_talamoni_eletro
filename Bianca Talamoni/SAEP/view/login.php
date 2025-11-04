<?php
session_start();

// Só destrói a sessão se for logout (não sempre que abrir a página)
if (isset($_GET['logout'])) {
    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">       
    <title>Login - Gestão de Produtos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Login - Sistema de Eletrodomésticos</h1>

    <form action="./../controller/controller_usuario.php" method="POST">
        <label>Email:</label>
        <br>
        <input type="email" id="email" name="email" placeholder="Email..." required>

        <br><br>

        <label>Senha:</label>
        <br>
        <input type="password" id="senha" name="senha" placeholder="Senha..." required>

        <br><br>

        <input type="submit" id="login" name="login" value="Acessar">
    </form>

    <?php
    // Exibe alerta se houver erro de login
    if (isset($_SESSION['erro_login'])) {
        echo "<script>alert('" . $_SESSION['erro_login'] . "');</script>";
        unset($_SESSION['erro_login']); 
    }
    ?>
</body>
</html>
