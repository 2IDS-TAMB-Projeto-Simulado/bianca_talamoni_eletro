<?php
require_once "../config/db.php";
require_once "model_logs.php";

class Estoque {

    // Adicionar item no estoque
    public function adicionar_estoque($quantidade, $fk_usu_id, $fk_produto_id) {
        $conn = Database::getConnection();
        $insert = $conn->prepare("INSERT INTO ESTOQUE (ESTOQUE_QUANTIDADE, FK_PRODUTO_ID) VALUES (?, ?)");
        $insert->bind_param("ii", $quantidade, $fk_produto_id);
        $success = $insert->execute(); 
        $insert->close();
        return $success;
    }

    // Atualizar quantidade no estoque
    public function atualizar_estoque($quantidade, $fk_produto_id, $fk_usu_id) {
        $conn = Database::getConnection();
        $update = $conn->prepare("UPDATE ESTOQUE SET ESTOQUE_QUANTIDADE = ? WHERE FK_PRODUTO_ID = ?");
        $update->bind_param("ii", $quantidade, $fk_produto_id);
        $success = $update->execute();

        if($success){
            $logs = new Logs();
            $logs->cadastrar_logs("PRODUTO <br> ID: ".$fk_produto_id." <br> AÇÃO: Estoque atualizado <br> NOVA QTD: ".$quantidade."<br> ID USUÁRIO: ".$fk_usu_id);
        }

        $update->close();
        return $success;
    }
}
?>
