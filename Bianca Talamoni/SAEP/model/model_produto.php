<?php
require_once "../config/db.php";
require_once "model_estoque.php";
require_once "model_logs.php";

class Produto {

    // Cadastrar novo produto
    public function cadastrar_produto($nome, $fornecedor, $categoria, $potencia, $consumo, $garantia, $prioridade, $fk_usu_id) {
        $conn = Database::getConnection();
        $insert = $conn->prepare("INSERT INTO PRODUTOS 
            (PRODUTO_NOME, PRODUTO_FORNECEDOR, PRODUTO_CATEGORIA, PRODUTO_POTENCIA, PRODUTO_CONSUMO, PRODUTO_GARANTIA, PRODUTO_PRIORIDADE, FK_USU_ID) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("sssssssi", $nome, $fornecedor, $categoria, $potencia, $consumo, $garantia, $prioridade, $fk_usu_id);
        $success = $insert->execute();

        if ($success) {
            $produto_id = $conn->insert_id;

            // Adiciona o produto ao estoque com quantidade inicial 0
            $estoque = new Estoque();
            $estoque->adicionar_estoque(0, $fk_usu_id, $produto_id);

            // Registrar log
            $logs = new Logs();
            $logs->cadastrar_logs("PRODUTO <br> ID: ".$produto_id." <br> NOME: ".$nome." <br> AÇÃO: Cadastrado! <br> ID USUÁRIO: ".$fk_usu_id);
        }

        $insert->close();
        return $success;
    }

    // Listar todos os produtos
    public function listar_produtos() {
        $conn = Database::getConnection();
        $sql = "SELECT 
                    P.PRODUTO_ID,
                    P.PRODUTO_NOME,
                    P.PRODUTO_FORNECEDOR,
                    P.PRODUTO_CATEGORIA,
                    P.PRODUTO_POTENCIA,
                    P.PRODUTO_CONSUMO,
                    P.PRODUTO_GARANTIA,
                    P.PRODUTO_PRIORIDADE,
                    E.ESTOQUE_QUANTIDADE,
                    U.USU_NOME,
                    U.USU_EMAIL
                FROM PRODUTOS P
                JOIN USUARIO U ON P.FK_USU_ID = U.USU_ID
                JOIN ESTOQUE E ON P.PRODUTO_ID = E.FK_PRODUTO_ID
                ORDER BY P.PRODUTO_NOME";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Excluir produto
    public function excluir_produto($produto_id, $fk_usu_id) {
        $conn = Database::getConnection();

        // Excluir estoque vinculado
        $deleteEstoque = $conn->prepare("DELETE FROM ESTOQUE WHERE FK_PRODUTO_ID = ?");
        $deleteEstoque->bind_param("i", $produto_id);
        $deleteEstoque->execute();
        $deleteEstoque->close();

        // Excluir produto
        $delete = $conn->prepare("DELETE FROM PRODUTOS WHERE PRODUTO_ID = ?");
        $delete->bind_param("i", $produto_id);

        $logs = new Logs();
        $logs->cadastrar_logs("PRODUTO <br> ID: ".$produto_id." <br> AÇÃO: Excluído! <br> ID USUÁRIO: ".$fk_usu_id);

        $success = $delete->execute();
        $delete->close();

        return $success;
    }

    // Buscar produto pelo ID
    public function buscar_produto_pelo_id($id) {
        $conn = Database::getConnection();
        $select = $conn->prepare("SELECT 
                                    P.PRODUTO_ID,
                                    P.PRODUTO_NOME,
                                    P.PRODUTO_FORNECEDOR,
                                    P.PRODUTO_CATEGORIA,
                                    P.PRODUTO_POTENCIA,
                                    P.PRODUTO_CONSUMO,
                                    P.PRODUTO_GARANTIA,
                                    P.PRODUTO_PRIORIDADE,
                                    E.ESTOQUE_QUANTIDADE,
                                    U.USU_NOME,
                                    U.USU_EMAIL
                                FROM PRODUTOS P
                                JOIN USUARIO U ON P.FK_USU_ID = U.USU_ID
                                JOIN ESTOQUE E ON P.PRODUTO_ID = E.FK_PRODUTO_ID
                                WHERE P.PRODUTO_ID = ?
                                ORDER BY P.PRODUTO_NOME");
        $select->bind_param("i", $id);
        $select->execute();
        $result = $select->get_result();
        $produto = $result->fetch_all(MYSQLI_ASSOC);
        $select->close();
        return $produto;
    }

    // Editar produto
    public function editar_produto($nome, $fornecedor, $categoria, $potencia, $consumo, $garantia, $prioridade, $produto_id, $fk_usu_id) {
        $conn = Database::getConnection();
        $update = $conn->prepare("UPDATE PRODUTOS 
            SET PRODUTO_NOME = ?, PRODUTO_FORNECEDOR = ?, PRODUTO_CATEGORIA = ?, 
                PRODUTO_POTENCIA = ?, PRODUTO_CONSUMO = ?, PRODUTO_GARANTIA = ?, PRODUTO_PRIORIDADE = ?
            WHERE PRODUTO_ID = ?");
        $update->bind_param("sssssssi", $nome, $fornecedor, $categoria, $potencia, $consumo, $garantia, $prioridade, $produto_id);
        $success = $update->execute();

        if ($success) {
            $logs = new Logs();
            $logs->cadastrar_logs("PRODUTO <br> ID: ".$produto_id." <br> NOME: ".$nome." <br> AÇÃO: Editado! <br> ID USUÁRIO: ".$fk_usu_id);
        }

        $update->close();
        return $success;
    }

    // Filtrar produtos por nome
    public function filtrar_produto($campo) {
        $conn = Database::getConnection();
        $select = $conn->prepare("SELECT 
                                    P.PRODUTO_ID,
                                    P.PRODUTO_NOME,
                                    P.PRODUTO_FORNECEDOR,
                                    P.PRODUTO_CATEGORIA,
                                    P.PRODUTO_POTENCIA,
                                    P.PRODUTO_CONSUMO,
                                    P.PRODUTO_GARANTIA,
                                    P.PRODUTO_PRIORIDADE,
                                    E.ESTOQUE_QUANTIDADE,
                                    U.USU_NOME,
                                    U.USU_EMAIL
                                FROM PRODUTOS P
                                JOIN USUARIO U ON P.FK_USU_ID = U.USU_ID
                                JOIN ESTOQUE E ON P.PRODUTO_ID = E.FK_PRODUTO_ID
                                WHERE P.PRODUTO_NOME LIKE ?
                                ORDER BY P.PRODUTO_NOME");
        $termo = "%" . $campo . "%";
        $select->bind_param("s", $termo);
        $select->execute();
        $result = $select->get_result();
        $produtos = $result->fetch_all(MYSQLI_ASSOC);
        $select->close();
        return $produtos;
    }
}
?>
