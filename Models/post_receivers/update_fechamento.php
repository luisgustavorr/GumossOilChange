<?php 
include('../../MySql.php');

$produto = \MySql::conectar()->prepare("DELETE FROM `tb_fechamento`  WHERE `data` = ? AND caixa = ? ");
$produto->execute(array($_POST['data_fechamento'],$_POST['caixa']));
  echo str_replace(',','.',$_POST['moedas']);
  $equip = \MySql::conectar()->prepare("INSERT INTO `tb_fechamento` (`id`, `dinheiro`, `cartao`, `moeda`, `pix`, `sangria`, `data`,`moeda_apurada`,`caixa`,`colaborador`) VALUES (NULL, ?, ?, ?, ?, ?,?,?,?,?)");
  $equip->execute(array(str_replace(',','.',$_POST['dinheiro_informadas']),str_replace(',','.',$_POST['cartao_informadas']),str_replace(',','.',$_POST['moedas_informadas']),str_replace(',','.',$_POST['pix_informadas']),str_replace(',','.',$_POST['sangria_informadas']),date("Y-m-d"),str_replace(',','.',$_POST['moedas']),$_POST["caixa"],$_POST['funcionario']));

?>