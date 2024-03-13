
            <?php
include('../../MySql.php');
$infos = \MySql::conectar()->prepare("SELECT 
(SELECT `forma_pagamento`
 FROM `tb_vendas`
 WHERE DATE(`data`) BETWEEN ? AND ? AND fechada = 1
 GROUP BY `forma_pagamento`
 ORDER BY COUNT(*) DESC
 LIMIT 1) AS formaPagamentoMaisRepetida,
 
(SELECT COUNT(*)
 FROM `tb_vendas`
 WHERE DATE(`data`) BETWEEN ? AND ? AND fechada = 1) AS quantidadeVendas,
 
(SELECT SUM(`valor`) 
 FROM `tb_vendas`
 WHERE DATE(`data`) BETWEEN ? AND ? AND fechada = 1) AS totalValor,
 
(SELECT `tb_produtos`.`nome`
 FROM `tb_produtos` INNER JOIN `tb_produtos_vendidos` ON `tb_produtos_vendidos`.`id_produto` = `tb_produtos`.`id` INNER JOIN `tb_vendas` ON `tb_vendas`.`id` = `tb_produtos_vendidos`.`id_venda`

 WHERE DATE(`tb_vendas`.`data`) BETWEEN ? AND ? AND fechada = 1
 GROUP BY `tb_produtos`.`id`
 ORDER BY SUM(tb_produtos_vendidos.quantidade_produto) DESC
 LIMIT 1) AS produtoMaisVendido;
");
$infos->execute(array($_POST["data_min"],$_POST["data_max"],$_POST["data_min"],$_POST["data_max"],$_POST["data_min"],$_POST["data_max"],$_POST["data_min"],$_POST["data_max"]));
$infos = $infos->fetch();
print_r(json_encode($infos));

            ?>
