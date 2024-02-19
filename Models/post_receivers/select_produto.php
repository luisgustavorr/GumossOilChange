<?php 
include('../../MySql.php');
$produto = \MySql::conectar()->prepare("SELECT * FROM `tb_produtos` WHERE nome LIKE ?");
$produto->execute(array("%".$_POST['nome']."%"));
$produto = $produto->fetchAll();

print_r(json_encode($produto));
?>