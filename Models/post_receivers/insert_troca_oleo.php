<?php
echo "aljshdl";
include('../../MySql.php');
// [data_revisao] => 2024-01-24T11:45
//     [nome_cliente] => 
//     [tel_cliente] => (37) 9-8410-3402
//     [codigo_colaborador] => 1
//     [pre_venda] => 0
//     [quilometragem] => 100.000.000.000
//     [metodo_pagamento] => Cartão Crédito
//     [valor_mao_obra] => 123,45
//     [placa_veiculo] => PXZ-7185
//     [produtos] => Array
//         (
//             [1] => Array
//                 (
//                     [id] => Teste
//                     [quantidade] => 1
//                     [preco] => 3.00
//                 )

//         )
date_default_timezone_set('America/Sao_Paulo');
$data_pedido = date("Y-m-d h:i:sa");
if ($_POST['pre_venda'] == "true") {
    $prevenda = 1;
} else {
    $prevenda = 0;
}
$valorProdutos = 0;
$select_info_colaborador = \MySql::conectar()->prepare("SELECT * FROM tb_colaboradores WHERE codigo = ?");
$select_info_colaborador->execute(array($_POST["codigo_colaborador"]));
$select_info_colaborador = $select_info_colaborador->fetch();
$select_info_veiculo = \MySql::conectar()->prepare("SELECT * FROM tb_veiculos WHERE placa_carro = ?");
$select_info_veiculo->execute(array($_POST["placa_veiculo"]));
$select_info_veiculo = $select_info_veiculo->fetch();
if (empty($select_info_veiculo)) {
    $insert_veiculo = \MySql::conectar()->prepare("INSERT INTO `tb_veiculos` (`id`, `placa_carro`, `modelo`, `marca`, `quilometragem`) VALUES (NULL, ?, ?, ?, ?);");
    $insert_veiculo->execute(array($_POST["placa_veiculo"], $_POST["modelo_veiculo"], $_POST["marca_veiculo"], $_POST["quilometragem"]));
    $insert_veiculo = $insert_veiculo->fetch();
} else {
    $atualizar_veiculo = \MySql::conectar()->prepare("UPDATE tb_veiculos SET quilometragem = ? WHERE placa_carro = ?");
    $atualizar_veiculo->execute(array($_POST["quilometragem"], $_POST["placa_veiculo"]));
    $atualizar_veiculo = $atualizar_veiculo->fetch();
}
if ($_POST["nome_cliente"] != '') {
    $nome_cliente = $_POST["nome_cliente"];
} else {
    $nome_cliente = "Não informado";
}

$id_cliente  = $_POST["id_cliente"];
$insert_vendas = \MySql::conectar()->prepare("INSERT INTO `tb_vendas` (`id`, `colaborador`, `data`, `valor`, `placa_carro`, `forma_pagamento`, `troco`, `id_cliente`,
`valor_servico`,`pre_venda`,`prazo`,fechada,tel_cliente) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?);");
$insert_vendas->execute(array($_POST['codigo_colaborador'], $_POST['data_revisao'], floatval(str_replace(",", ".", $_POST["valor_mao_obra"])) + $_POST['valor_produtos'], $_POST["placa_veiculo"], $_POST["metodo_pagamento"], 0, $id_cliente, str_replace(",", ".", $_POST["valor_mao_obra"]), $prevenda, $_POST["prazo"],0,$_POST["tel_cliente"]));
$id_venda =  \MySql::conectar()->lastInsertId();

if (!isset($_POST["produtos"])) {
    $insert_produtos_vendidos = \MySql::conectar()->prepare("INSERT INTO `tb_produtos_vendidos` (`id`, `id_venda`, `id_produto`, `quantidade_produto`,`desconto`,`acrescimo`) VALUES (NULL, ?, ?, ?,0,0);");
    $insert_produtos_vendidos->execute(array($id_venda, 3, 0));
    exit();
}
$arrayProdutos = $_POST["produtos"];

foreach ($arrayProdutos as $key => $value) {
    $valorProdutos += $value["preco"] * $value["quantidade"];
    $insert_produtos_vendidos = \MySql::conectar()->prepare("INSERT INTO `tb_produtos_vendidos` (`id`, `id_venda`, `id_produto`, `quantidade_produto`,`desconto`,`acrescimo`) VALUES (NULL, ?, ?, ?,0,0);");
    $insert_produtos_vendidos->execute(array($id_venda, $value["id"], $value["quantidade"]));
    $diminuirEstoque = \MySql::conectar()->prepare("UPDATE tb_produtos SET quantidade = quantidade - ?  WHERE id = ?");
    $diminuirEstoque->execute(array($value['quantidade'], $value['id']));
}
