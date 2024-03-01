<?php 
require("../../MySql.php");
$update_venda = \MySql::conectar()->prepare("UPDATE tb_vendas SET fechada = 1 WHERE id = ?");
$update_venda = $update_venda->execute(array($_POST["id_venda"]));
?>