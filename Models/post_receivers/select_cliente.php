<?php 
include('../../MySql.php');
$produto = \MySql::conectar()->prepare("SELECT * FROM `tb_clientes` WHERE nome LIKE ?");
$produto->execute(array("%".$_POST['nome']."%"));
$produto = $produto->fetchAll();

print_r(json_encode($produto));
?>