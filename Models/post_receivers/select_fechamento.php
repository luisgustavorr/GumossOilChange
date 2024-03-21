<?php
include('../../MySql.php');

$fechamento_de_hoje = \MySql::conectar()->prepare("SELECT id,SUM(dinheiro) as dinheiro,SUM(cartao) as cartao, SUM(moeda) as moeda,SUM(pix) as pix,SUM(sangria) as sangria,SUM(moeda_apurada) as moeda_apurada,caixa, colaborador FROM `tb_fechamento` WHERE `data` = ? AND `colaborador` = ? GROUP BY DATE(`data`);");
$fechamento_de_hoje->execute(array($_POST['data_fechamento'], $_POST['userId']));
$fechamento_de_hoje = $fechamento_de_hoje->fetch();

$valores_apurados_de_hoje = \MySql::conectar()->prepare("SELECT 
    (SELECT SUM(valor) FROM `tb_vendas`  WHERE DATE(`tb_vendas`.`data`) = ? AND `tb_vendas`.`forma_pagamento` LIKE 'Cartão%' AND `tb_vendas`.`colaborador` = ?) AS cartaoApurado,
     (SELECT SUM(valor) FROM `tb_vendas`  WHERE DATE(`tb_vendas`.`data`) = ? AND `tb_vendas`.`forma_pagamento` ='Dinheiro' AND `tb_vendas`.`colaborador` = ?) AS dinheiroApurado,
       (SELECT SUM(valor) FROM `tb_vendas`  WHERE DATE(`tb_vendas`.`data`) = ? AND `tb_vendas`.`forma_pagamento` ='Pix' AND `tb_vendas`.`colaborador` = ?) AS pixApurado,
     (SELECT SUM(`valor`)
     FROM `tb_sangrias`
     WHERE DATE(`data`) = ? AND `colaborador` = ? GROUP BY DATE(`data`) 
     ) AS sangriaApurado
     ");
$valores_apurados_de_hoje->execute(array($_POST['data_fechamento'], $_POST['userId'], $_POST['data_fechamento'], $_POST['userId'], $_POST['data_fechamento'], $_POST['userId'], $_POST['data_fechamento'], $_POST['userId']));
$valores_apurados_de_hoje = $valores_apurados_de_hoje->fetch();
if ($fechamento_de_hoje != false and $valores_apurados_de_hoje !=false) {
  $fechamento_de_hoje = array_merge($fechamento_de_hoje, $valores_apurados_de_hoje);
}
if($fechamento_de_hoje == false ){
  print_r(json_encode($valores_apurados_de_hoje));
}else{
  print_r(json_encode($fechamento_de_hoje));
}
