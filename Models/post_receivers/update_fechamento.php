<?php 
include('../../MySql.php');

$produto = \MySql::conectar()->prepare("DELETE FROM `tb_fechamento`  WHERE `data` = ? AND caixa = ? ");
$produto->execute(array($_POST['data_fechamento'],$_COOKIE['caixa']));
  $equip = \MySql::conectar()->prepare("INSERT INTO `tb_fechamento` (`id`, `dinheiro`, `cartao`, `moeda`, `pix`, `sangria`, `data`,`moeda_apurada`,`caixa`,`colaborador`) VALUES (NULL, ?, ?, ?, ?, ?,?,?,?,?)");
  $equip->execute(array(str_replace(',','.',$_POST['dinheiro_informadas']),str_replace(',','.',$_POST['cartao_informadas']),0,str_replace(',','.',$_POST['pix_informadas']),str_replace(',','.',$_POST['sangria_informadas']),date("Y-m-d"),0,$_COOKIE["caixa"],$_POST['funcionario']));

?>