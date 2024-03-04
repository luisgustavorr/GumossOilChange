<?php 
require("../../MySql.php");
date_default_timezone_set('America/Sao_Paulo');

$update_venda = \MySql::conectar()->prepare("UPDATE tb_vendas SET fechada = 1,data = ? WHERE id = ?");
$update_venda = $update_venda->execute(array(date("Y-m-d h:i:sa"),$_POST["id_venda"]));
?>