<?php
require_once "./../controller/controller_produto.php";

if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

$produto = new Produto();

if (isset($_POST['botao_pesquisar'])) {
    $resultados = $produto->filtrar_produto($_POST['pesquisar']);
} else {
    $resultados = $produto->listar_produtos();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">       
    <title>Gestão de Estoque</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            border-collapse: collapse;
        }
        tr, td, th {
            padding: 12px;
        }
    </style>
</head>
<body>
    <h1>Gestão de Estoque</h1>

    <form method="POST">
        <input type="search" id="pesquisar" name="pesquisar" placeholder="Pesquisar produto...">
        <input type="submit" id="botao_pesquisar" name="botao_pesquisar" value="Filtrar">
    </form>

    <br>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Fornecedor</th>
            <th>Categoria</th>
            <th>Potência</th>
            <th>Consumo</th>
            <th>Garantia</th>
            <th>Prioridade</th>
            <th>Quantidade em Estoque</th>
            <th>Ação</th>
            <th>Quantidade</th>
            <th>Atualizar</th>
        </tr>
        <?php
        if (count($resultados) > 0) {
            foreach ($resultados as $r) {
                echo "<form method='POST' action='./../controller/controller_estoque.php'>";
                echo "<tr>";  
                echo "<td><input type='number' name='produto_id' id='produto_id' value='".$r['PRODUTO_ID']."' readonly></td>";
                echo "<td>".$r['PRODUTO_NOME']."</td>";
                echo "<td>".$r['PRODUTO_FORNECEDOR']."</td>";
                echo "<td>".$r['PRODUTO_CATEGORIA']."</td>";
                echo "<td>".$r['PRODUTO_POTENCIA']."</td>";
                echo "<td>".$r['PRODUTO_CONSUMO']."</td>";
                echo "<td>".$r['PRODUTO_GARANTIA']."</td>";
                echo "<td>".$r['PRODUTO_PRIORIDADE']."</td>";
                echo "<td><input type='number' name='estoque_qtd' id='estoque_qtd' value='".$r['ESTOQUE_QUANTIDADE']."' readonly></td>";
                echo "<td>
                        <select id='acao_estoque' name='acao_estoque' required>
                            <option value=''>Selecione...</option>
                            <option value='entrada'>Entrada</option>
                            <option value='saida'>Saída</option>
                        </select>
                      </td>";
                echo "<td><input type='number' id='qtd_aumentar_diminuir' name='qtd_aumentar_diminuir' min='0'></td>";
                echo "<td><input type='submit' id='botao_atualizar' name='botao_atualizar' value='Atualizar Estoque'></td>";
                echo "</tr>";    
                echo "</form>";                        
            }
        } else {
            echo "<tr>";  
            echo "<th colspan='12'>Nenhum produto cadastrado!</th>";
            echo "</tr>";       
        }
        ?>
    </table>

    <br><br>
    <a href="inicial.php"><button>Voltar</button></a>
</body>
</html>
