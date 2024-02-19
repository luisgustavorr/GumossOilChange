
        <?php 
        $prefix = '<div class="tabela_father">
            <div class="tabela_header">
            <i id="voltar_semana" onclick="mudarTempo(this)"class="fa-solid fa-angle-left modificadores_tempo "></i> <span><i class="fa-solid fa-chart-line" id="show_graphs"></i> Vendas no dia: <yellow>20/07/2023</yellow> <i class="gerar_pdf fa-regular fa-file-pdf"></i></span><i id="adiantar_semana"onclick="mudarTempo(this)" class="fa-solid fa-angle-right modificadores_tempo adiantar_semana"></i>
            </div>
            <table id="table_tabela">
                <thead>
                    <tr>
                    <th>Data Venda</th>
                        <th>Valor Total</th>
                        <th>Produtos na Venda</th>
                        <th>Funcionário</th>
                        <th>Método de Pagamento</th>
                    </tr>
                </thead>
                <tbody>';
include('../../MySql.php');

    $caixa ="";

    $row_vendas = \MySql::conectar()->prepare("SELECT SUM(tb_produtos.valor_venda * `tb_produtos_vendidos`.`quantidade_produto`) as valor_produtos  ,`tb_vendas`.`id`,`tb_vendas`.`forma_pagamento`,`tb_vendas`.`quilometragem`,`tb_clientes`.nome as colaborador, `tb_vendas`.`data`, `tb_vendas`.`valor` as total_valor,`tb_vendas`.`placa_carro` as nomes FROM `tb_vendas` INNER JOIN `tb_clientes` ON `tb_vendas`.`id_cliente` = `tb_clientes`.`id` INNER JOIN `tb_produtos_vendidos` ON `tb_vendas`.`id` = `tb_produtos_vendidos`.`id_venda`INNER JOIN `tb_produtos` ON `tb_produtos`.`id` = `tb_produtos_vendidos`.`id_produto` INNER JOIN  `tb_colaboradores` ON `tb_vendas`.`colaborador` = `tb_colaboradores`.`codigo` WHERE DATE(`tb_vendas`.`data`) BETWEEN ? AND ? GROUP BY `tb_vendas`.`data`;");

$row_vendas->execute(array($_POST['data_min'],$_POST['data_max']));
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
    $valor_venda = strstr($value['nomes'],'Entrada Pedido')  == false ? number_format($value['total_valor']) - number_format($valor_entrada): number_format($value['total_valor']);
    $produto = $value['nomes'];
    if(isset($value['cliente'])){
    $produto = strstr($value['nomes'],'Entrada Pedido')  == false ? 'Pedido '.$value['id'].' do(a) '.$value['cliente']: 'Entrada do Pedido '.$value['id'].' do(a) '.$value['cliente'];
    }
    $valor_venda = $valor_venda >= 0 ? $valor_venda : 0;
    // Formata o timestamp para o formato desejado usando a função strftime()
    $data_formatada = strftime('%Hh%M-%d/%m/%Y', $timestamp);
    if(isset($_POST['switch'])){
        $prefix .="
        <tr>
        <td>$data_formatada</td>
         <td>R$".str_replace('.',',',number_format($valor_venda, 2))."</td>
         <td title='".str_replace('_',' ',$value['nomes']) ."'>".$produto."</td>
         <td> " . $value['quilometragem'] . " KM </td>
         <td>".$value['colaborador']."</td>

         <td>".$value['forma_pagamento']."</td>
         </tr>";
    }else{
        echo "
        <tr>
        <td>$data_formatada</td>
         <td>R$".str_replace('.',',',number_format($valor_venda, 2))."</td>
         <td title='".str_replace('_',' ',$value['nomes']) ."'>".$produto."</td>
         <td> " . $value['quilometragem'] . " KM </td>

         <td>".$value['colaborador']."</td>

         <td>".$value['forma_pagamento']."</td>
         </tr>";
    }

}
   if(isset($_POST['switch'])) {echo $prefix.'          </tr>
    </tbody>
</table>
</div>
';}
?>

  