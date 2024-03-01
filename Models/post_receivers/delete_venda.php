<?php 
include('../../MySql.php');
    echo $_POST["id"];

    $apagar_venda = \MySql::conectar()->prepare("DELETE FROM tb_vendas WHERE `tb_vendas`.`id` = ?");
    $apagar_venda->execute(array($_POST['id']));
    $apagar_produtos_vendidos = \MySql::conectar()->prepare("DELETE FROM tb_produtos_vendidos WHERE `tb_produtos_vendidos`.`id_venda` = ?");
    $apagar_produtos_vendidos->execute(array($_POST['id']));

?>