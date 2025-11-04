<?php
session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">       
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Cadastro de Produtos</h1>
    <form action="./../controller/controller_produto.php" method="POST">
        <label>Nome:</label>
        <br>
        <input type="text" id="nome" name="nome" placeholder="Nome do produto..." required>
        <br><br>

        <label>Fornecedor:</label>
        <br>
        <input type="text" id="fornecedor" name="fornecedor" placeholder="Fornecedor..." required>
        <br><br>

        <label>Categoria:</label>
        <br>
        <input type="text" id="categoria" name="categoria" placeholder="Categoria..." required>
        <br><br>

        <label>Potência:</label>
        <br>
        <input type="text" id="potencia" name="potencia" placeholder="Potência (ex: 1200W)..." required>
        <br><br>

        <label>Consumo:</label>
        <br>
        <input type="text" id="consumo" name="consumo" placeholder="Consumo (ex: 45 kWh/mês)..." required>
        <br><br>

        <label>Garantia:</label>
        <br>
        <input type="text" id="garantia" name="garantia" placeholder="Garantia (ex: 12 meses)..." required>
        <br><br>

        <label>Prioridade:</label>
        <br>
        <select id="prioridade" name="prioridade" required>
            <option value="">Selecione...</option>
            <option value="Alta">Alta</option>
            <option value="Média">Média</option>
            <option value="Baixa">Baixa</option>
        </select>
        <br><br>

        <input type="submit" id="cadastrar_produto" name="cadastrar_produto" value="Cadastrar">
    </form>

    <br>
    <a href="inicial.php"><button>Voltar</button></a>
</body>
</html>
