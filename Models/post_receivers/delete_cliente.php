<?php
include('../../MySql.php');
$update_vendas = \MySql::conectar()->prepare("UPDATE tb_vendas SET id_cliente = 0 WHERE id_cliente = ?");
$update_vendas->execute(array($_POST['id']));
$delete_cliente = \MySql::conectar()->prepare("DELETE FROM `tb_clientes` WHERE id = ?");
$delete_cliente->execute(array($_POST['id']));
