<?php 
include('../../MySql.php');
$produto = \MySql::conectar()->prepare("SELECT * FROM `tb_clientes` WHERE id != 0 ");
$produto->execute();
$produto = $produto->fetchAll();

print_r(json_encode($produto));
?>