<?php 
include('../../MySql.php');
$produto = \MySql::conectar()->prepare("SELECT * FROM `tb_clientes` WHERE nome LIKE ? OR id LIKE ? ");
$produto->execute(array("%".$_POST['nome']."%",$_POST['nome']."%"));
$produto = $produto->fetchAll();

print_r(json_encode($produto));
?>