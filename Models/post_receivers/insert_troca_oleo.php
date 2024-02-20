<?php

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
if ($_POST['pre_venda'] == 1) {
    $prevenda = 'Sim';
} else {
    $prevenda = 'Não';
}
$valorProdutos = 0;
$select_info_colaborador = \MySql::conectar()->prepare("SELECT * FROM tb_colaboradores WHERE codigo = ?");
$select_info_colaborador->execute(array( $_POST["codigo_colaborador"]));
$select_info_colaborador = $select_info_colaborador->fetch();
if($_POST["nome_cliente"] !=''){
    $nome_cliente = $_POST["nome_cliente"];
}else{
    $nome_cliente = "Não informado";
}
$insert_cliente = \MySql::conectar()->prepare("INSERT INTO tb_clientes (`id`,`nome`,`loja`,`telefone`) VALUES (NULL,?,?,?)");
$insert_cliente ->execute(array(   $nome_cliente,$select_info_colaborador['caixa'],$_POST["tel_cliente"]));
$id_cliente =  \MySql::conectar()->lastInsertId();
$insert_vendas = \MySql::conectar()->prepare("INSERT INTO `tb_vendas` (`id`, `colaborador`, `data`, `valor`, `placa_carro`, `forma_pagamento`, `troco`, `quilometragem`, `id_cliente`,
`valor_servico`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?,?);");
$insert_vendas->execute(array($_POST['codigo_colaborador'],$_POST['data_revisao'],floatval(str_replace(",",".",$_POST["valor_mao_obra"]))+$_POST['valor_produtos'],$_POST["placa_veiculo"],$_POST["metodo_pagamento"],0,$_POST["quilometragem"],$id_cliente,str_replace(",",".",$_POST["valor_mao_obra"])));
$id_venda =  \MySql::conectar()->lastInsertId();
if(!isset($_POST["produtos"])){
    $insert_produtos_vendidos = \MySql::conectar()->prepare("INSERT INTO `tb_produtos_vendidos` (`id`, `id_venda`, `id_produto`, `quantidade_produto`) VALUES (NULL, ?, ?, ?);");
    $insert_produtos_vendidos->execute(array( $id_venda,3,0));
    exit();
}
$arrayProdutos = $_POST["produtos"];

foreach ($arrayProdutos as $key => $value) {

    $valorProdutos += $value["preco"] * $value["quantidade"];
    $insert_produtos_vendidos = \MySql::conectar()->prepare("INSERT INTO `tb_produtos_vendidos` (`id`, `id_venda`, `id_produto`, `quantidade_produto`) VALUES (NULL, ?, ?, ?);");
    $insert_produtos_vendidos->execute(array( $id_venda,$value["id"],$value["quantidade"]));
    $diminuirEstoque = \MySql::conectar()->prepare("UPDATE tb_produtos SET quantidade = quantidade - ?  WHERE id = ?");
    $diminuirEstoque->execute(array($value['quantidade'],$value['id']));
}

