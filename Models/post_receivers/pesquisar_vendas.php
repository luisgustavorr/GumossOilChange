<?php
include('../../MySql.php');
$pesquisa = '`tb_clientes`.`nome` LIKE "' . $_POST['pesquisa'] . '%"';
if (isset($_POST['placa']) ) {
    $pesquisa = '`tb_vendas`.`placa_carro` = "' . $_POST['pesquisa'] . '"';
}
$row_vendas = \MySql::conectar()->prepare("SELECT SUM(tb_produtos.valor_venda * `tb_produtos_vendidos`.`quantidade_produto`) as valor_produtos  ,`tb_vendas`.`valor_servico` as valor_servico,`tb_vendas`.`id`,`tb_vendas`.`forma_pagamento`,`tb_vendas`.`quilometragem`,`tb_clientes`.nome as colaborador, `tb_vendas`.`data`, `tb_vendas`.`valor` as total_valor,`tb_vendas`.`placa_carro` as nomes FROM `tb_vendas` INNER JOIN `tb_clientes` ON `tb_vendas`.`id_cliente` = `tb_clientes`.`id` INNER JOIN `tb_produtos_vendidos` ON `tb_vendas`.`id` = `tb_produtos_vendidos`.`id_venda`INNER JOIN `tb_produtos` ON `tb_produtos`.`id` = `tb_produtos_vendidos`.`id_produto` INNER JOIN  `tb_colaboradores` ON `tb_vendas`.`colaborador` = `tb_colaboradores`.`codigo` WHERE $pesquisa GROUP BY `tb_vendas`.`data`;");
$row_vendas->execute(array());
$row_vendas = $row_vendas->fetchAll();
foreach ($row_vendas as $key => $value) {
    $data_banco = $value['data'];

    // Converte a string de data em um timestamp Unix usando a função strtotime()
    $timestamp = strtotime($data_banco);
    if(isset($value['pedido_id'])){
        $valor_entrada = \MySql::conectar()->prepare("SELECT * FROM `tb_pedidos` WHERE `id` = ? ");
        $valor_entrada->execute(array($value['pedido_id']));
        $valor_entrada = $valor_entrada->fetch();
        $valor_entrada = $valor_entrada["valor_entrada"];
    }else{
        $valor_entrada = 0;
    }
    // Formata o timestamp para o formato desejado usando a função strftime()
    $valor_venda = $value['total_valor'];
    $produto = $value['nomes'];
    if(isset($value['cliente'])){
    $produto = strstr($value['nomes'],'Entrada Pedido')  == false ? 'Pedido '.$value['id'].' do(a) '.$value['cliente']: 'Entrada do Pedido '.$value['id'].' do(a) '.$value['cliente'];
    }
    $valor_venda = $valor_venda >= 0 ? $valor_venda : 0;
    // Formata o timestamp para o formato desejado usando a função strftime()
    $data_formatada = strftime('%Hh%M-%d/%m/%Y', $timestamp);

        echo "
        <tr>
        <td> $data_formatada </td>
         <td title=' R$" . number_format($value['valor_produtos'],2 ). " em produtos e R$". $value["valor_servico"]  ." \n pela mão de obra'> R$" . str_replace('.', ',', number_format($valor_venda,2)) . " </td>
         <td title='" . str_replace('_',' ',$value['nomes']) . "'>  " .$produto. " </td>
         <td> " . $value['quilometragem'] . " KM </td>

         <td> " . $value['colaborador'] . " </td>
         <td> <i produto='" . $value['forma_pagamento'] . "' class='fa-solid fa-print'></i> </td>

         <td> " . $value['forma_pagamento'] . " </td>
         
         </tr>   ";
    

}