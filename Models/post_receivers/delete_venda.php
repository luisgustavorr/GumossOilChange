<?php 
include('../../MySql.php');
    echo $_POST["id"];
    $venda = \MySql::conectar()->prepare("SELECT * FROM tb_vendas WHERE `tb_vendas`.`id` = ?");
    $venda->execute(array($_POST['id']));
    $venda = $venda->fetch();
    $apagar_venda = \MySql::conectar()->prepare("DELETE FROM tb_vendas WHERE `tb_vendas`.`id` = ?");
    $apagar_venda->execute(array($_POST['id']));
    $apagar_produtos_vendidos = \MySql::conectar()->prepare("DELETE FROM tb_produtos_vendidos WHERE `tb_produtos_vendidos`.`id_venda` = ?");
    $apagar_produtos_vendidos->execute(array($_POST['id']));
    $apagar_clientes = \MySql::conectar()->prepare("DELETE FROM tb_clientes WHERE `id` = ?");
    $apagar_clientes->execute(array($venda['id_cliente']));
?>