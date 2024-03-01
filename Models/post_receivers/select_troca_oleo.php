<?php
require("../../MySql.php");
$selecionar_troca_oleo = \MySql::conectar()->prepare("SELECT ROUND(SUM(tb_produtos.valor_venda * `tb_produtos_vendidos`.`quantidade_produto`),2) as valor_produtos,tb_veiculos.modelo,tb_veiculos.marca,tb_veiculos.quilometragem,tb_clientes.tel,tb_vendas.*,tb_clientes.nome as nome_cliente FROM tb_vendas INNER JOIN tb_clientes ON tb_vendas.id_cliente = tb_clientes.id INNER JOIN tb_veiculos ON tb_veiculos.placa_carro = tb_vendas.placa_carro INNER JOIN tb_produtos_vendidos ON tb_produtos_vendidos.id_venda = tb_vendas.id INNER JOIN tb_produtos ON tb_produtos.id = tb_produtos_vendidos.id_produto WHERE tb_vendas.id = ?");
$selecionar_troca_oleo->execute(array($_POST["id_venda"]));
$selecionar_troca_oleo = $selecionar_troca_oleo->fetch();
$selecionar_produtos = \MySql::conectar()->prepare("SELECT * FROM tb_produtos_vendidos WHERE id_venda = ?");
$selecionar_produtos->execute(array($_POST["id_venda"]));
$selecionar_produtos = $selecionar_produtos->fetchAll();
$produtos = [];
foreach ($selecionar_produtos as $key => $value) {
    $selecionar_produto = \MySql::conectar()->prepare("SELECT valor_venda,id,nome FROM tb_produtos WHERE id = ?");
    $selecionar_produto->execute(array($value["id_produto"]));
    $selecionar_produto = $selecionar_produto->fetch();
    $selecionar_produto["quantidade"] = $value["quantidade_produto"];
    array_unshift($produtos, $selecionar_produto);
}
$selecionar_troca_oleo["produtos_vendidos"] = $produtos;
print_r(json_encode($selecionar_troca_oleo,true));
