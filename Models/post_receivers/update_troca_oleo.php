<?php
include('../../MySql.php');
$prod_vendidos = \MySql::conectar()->prepare("SELECT tb_produtos.id,tb_produtos_vendidos.quantidade_produto as quantidade,tb_produtos.valor_venda as preco FROM `tb_produtos_vendidos` INNER JOIN tb_produtos ON tb_produtos_vendidos.id_produto = tb_produtos.id WHERE id_venda =  ?");
$prod_vendidos->execute(array($_POST["id_venda"]));
$prod_vendidos = $prod_vendidos->fetchAll(PDO::FETCH_ASSOC);

$select_venda = \MySql::conectar()->prepare("SELECT pre_venda FROM tb_vendas WHERE iD =  ?");
$select_venda->execute(array($_POST["id_venda"]));
$select_venda = $select_venda->fetch(PDO::FETCH_ASSOC);

foreach ($prod_vendidos as $key => $value) {
    $restaurarEstoque = \MySql::conectar()->prepare("UPDATE tb_produtos SET quantidade = quantidade + ? WHERE id = ?");
    $restaurarEstoque->execute(array($value["quantidade"], $value['id']));
}
$delete_prod = \MySql::conectar()->prepare("DELETE FROM  tb_produtos_vendidos WHERE id_venda = ?");
$delete_prod->execute(array($_POST["id_venda"]));
$index = 0;
foreach ($_POST["produtos"] as $key => $value) {
    $prod = \MySql::conectar()->prepare("SELECT * FROM tb_produtos WHERE id = ?");
    $prod->execute(array($value["id"]));
    $prod = $prod->fetch();

    $diminuirEstoque = \MySql::conectar()->prepare("UPDATE tb_produtos SET quantidade = quantidade - ?  WHERE id = ?");
    $diminuirEstoque->execute(array($value["quantidade"],$value['id']));

    $insert_produtos_vendidos = \MySql::conectar()->prepare("INSERT INTO `tb_produtos_vendidos` (`id`, `id_venda`, `id_produto`, `quantidade_produto`,`desconto`,`acrescimo`) VALUES (NULL, ?, ?, ?,0,0);");
    $insert_produtos_vendidos->execute(array($_POST["id_venda"], $value["id"], $value["quantidade"]));

    $desconto_acrescimo = $value["preco"] - $prod["valor_venda"];

    if ($desconto_acrescimo >= 0) {
        $alteracao = "acrescimo";
    } else {
        $desconto_acrescimo = $desconto_acrescimo * -1;
        $alteracao = "desconto";
    }
    $diminuirEstoque = \MySql::conectar()->prepare("UPDATE tb_produtos_vendidos SET quantidade_produto = ?, $alteracao = ? WHERE id_produto = ? AND id_venda = ?");
    $diminuirEstoque->execute(array($value['quantidade'], $desconto_acrescimo,$value["id"] ,$_POST['id_venda']));

    $insert_vendas = \MySql::conectar()->prepare("UPDATE `tb_vendas` SET  `colaborador` = ?,`data` = ?,`valor` = ?,`placa_carro` = ?,`forma_pagamento` = ?,`troco` = ?,`id_cliente` = ?,`valor_servico` = ?,`pre_venda` = ?,`prazo` = ? ");
    $insert_vendas->execute(array($_POST['codigo_colaborador'], $_POST['data_revisao'], floatval(str_replace(",", ".", $_POST["valor_mao_obra"])) + $_POST['valor_produtos'], $_POST["placa_veiculo"], $_POST["metodo_pagamento"], 0, $_POST["id_cliente"], str_replace(",", ".", $_POST["valor_mao_obra"]), $select_venda["pre_venda"],$_POST["prazo"]));
}


// if ($_POST['pre_venda'] == "true") {
//     $prevenda = 1;
// } else {
//     $prevenda = 0;
// }
// $valorProdutos = 0;
// $select_info_colaborador = \MySql::conectar()->prepare("SELECT * FROM tb_colaboradores WHERE codigo = ?");
// $select_info_colaborador->execute(array($_POST["codigo_colaborador"]));
// $select_info_colaborador = $select_info_colaborador->fetch();
// $select_info_veiculo = \MySql::conectar()->prepare("SELECT * FROM tb_veiculos WHERE placa_carro = ?");
// $select_info_veiculo->execute(array($_POST["placa_veiculo"]));
// $select_info_veiculo = $select_info_veiculo->fetch();
// if (empty($select_info_veiculo)) {
//     $insert_veiculo = \MySql::conectar()->prepare("INSERT INTO `tb_veiculos` (`id`, `placa_carro`, `modelo`, `marca`, `quilometragem`) VALUES (NULL, ?, ?, ?, ?);");
//     $insert_veiculo->execute(array($_POST["placa_veiculo"],$_POST["modelo_veiculo"],$_POST["marca_veiculo"],$_POST["quilometragem"]));
//     $insert_veiculo = $insert_veiculo->fetch();
// }else{
//     $atualizar_veiculo = \MySql::conectar()->prepare("UPDATE tb_veiculos SET quilometragem = ? WHERE placa_carro = ?");
//     $atualizar_veiculo->execute(array($_POST["quilometragem"],$_POST["placa_veiculo"]));
//     $atualizar_veiculo = $atualizar_veiculo->fetch();
// }
