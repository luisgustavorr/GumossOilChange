<?php 
include('../../MySql.php');

  $produto = \MySql::conectar()->prepare("SELECT * FROM `tb_produtos` WHERE nome LIKE ? ");
  $produto->execute(array("%".$_POST['produto']."%"));
  $produto = $produto->fetch();
  print_r(json_encode($produto,true))
?>