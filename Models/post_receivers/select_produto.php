<?php 
include('../../MySql.php');
$produto = \MySql::conectar()->prepare("SELECT * FROM `tb_produtos` WHERE caixa = ? ");
$produto->execute(array($_COOKIE["caixa"]));
$produto = $produto->fetchAll();

print_r(json_encode($produto));
?>