<?php 
include('../../MySql.php');
$produto = \MySql::conectar()->prepare("SELECT * FROM `tb_produtos` ");
$produto->execute();
$produto = $produto->fetchAll();

print_r(json_encode($produto));
?>