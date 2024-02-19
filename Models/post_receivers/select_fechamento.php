<?php 
include('../../MySql.php');

    $fechamento_de_hoje = \MySql::conectar()->prepare("SELECT id,SUM(dinheiro) as dinheiro,SUM(cartao) as cartao, SUM(moeda) as moeda,SUM(pix) as pix,SUM(sangria) as sangria,SUM(moeda_apurada) as moeda_apurada,caixa, colaborador FROM `tb_fechamento` WHERE `data` = ? AND `colaborador` = ? GROUP BY DATE(`data`);");
    $fechamento_de_hoje->execute(array($_POST['data_fechamento'],$_POST['userId']));
    $fechamento_de_hoje = $fechamento_de_hoje->fetch();

    $valores_apurados_de_hoje = \MySql::conectar()->prepare("SELECT 
    (SELECT SUM(valor) FROM `tb_vendas` LEFT JOIN `tb_pedidos` ON `tb_vendas`.`pedido_id` = `tb_pedidos`.`id` WHERE DATE(`tb_vendas`.`data`) = ? AND `tb_vendas`.`forma_pagamento` LIKE 'Cartão%' AND `tb_vendas`.`colaborador` = ? AND ((`tb_vendas`.`produto` LIKE 'Entrada%' AND  `tb_pedidos`.`entregue` = 0) OR (`tb_vendas`.`produto` NOT LIKE 'Entrada%' AND  `tb_pedidos`.`entregue` = 1) OR `tb_vendas`.`pedido_id` = 0)) AS cartaoApurado,
     (SELECT SUM(valor) FROM `tb_vendas` LEFT JOIN `tb_pedidos` ON `tb_vendas`.`pedido_id` = `tb_pedidos`.`id` WHERE DATE(`tb_vendas`.`data`) = ? AND `tb_vendas`.`forma_pagamento` ='Dinheiro' AND `tb_vendas`.`colaborador` = ? AND ((`tb_vendas`.`produto` LIKE 'Entrada%' AND  `tb_pedidos`.`entregue` = 0) OR (`tb_vendas`.`produto` NOT LIKE 'Entrada%' AND  `tb_pedidos`.`entregue` = 1) OR `tb_vendas`.`pedido_id` = 0)) AS dinheiroApurado,
       (SELECT SUM(valor) FROM `tb_vendas` LEFT JOIN `tb_pedidos` ON `tb_vendas`.`pedido_id` = `tb_pedidos`.`id` WHERE DATE(`tb_vendas`.`data`) = ? AND `tb_vendas`.`forma_pagamento` ='Pix' AND `tb_vendas`.`colaborador` = ? AND ((`tb_vendas`.`produto` LIKE 'Entrada%' AND  `tb_pedidos`.`entregue` = 0) OR (`tb_vendas`.`produto` NOT LIKE 'Entrada%' AND  `tb_pedidos`.`entregue` = 1) OR `tb_vendas`.`pedido_id` = 0)) AS pixApurado,
     (SELECT SUM(`valor`)
     FROM `tb_sangrias`
     WHERE DATE(`data`) = ? AND `colaborador` = ? GROUP BY DATE(`data`) 
     ) AS sangriaApurado,

     (SELECT `moeda_apurada` FROM `tb_fechamento` WHERE DATE(`data`) = ? AND `colaborador` = ? GROUP BY DATE(`data`) ) AS moedaApurado");
    $valores_apurados_de_hoje->execute(array($_POST['data_fechamento'],$_POST['userId'],$_POST['data_fechamento'],$_POST['userId'],$_POST['data_fechamento'],$_POST['userId'],$_POST['data_fechamento'],$_POST['userId'],$_POST['data_fechamento'],$_POST['userId']));
    $valores_apurados_de_hoje = $valores_apurados_de_hoje->fetch();
    if(!empty($fechamento_de_hoje) AND !empty($valores_apurados_de_hoje) ){
      $fechamento_de_hoje = array_merge($fechamento_de_hoje,$valores_apurados_de_hoje);

    }
    print_r(json_encode($fechamento_de_hoje))

    ?>