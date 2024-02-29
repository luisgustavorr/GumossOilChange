<?php

namespace Models;

class PainelControleModel
{
    public static function formarTabela()
    {
        $row_vendas = \MySql::conectar()->prepare("SELECT tb_vendas.id as id_venda, SUM(tb_produtos.valor_venda * `tb_produtos_vendidos`.`quantidade_produto`) as valor_produtos,`tb_veiculos`.`marca`,`tb_veiculos`.`modelo`,`tb_veiculos`.`marca`,`tb_vendas`.`valor_servico` as valor_servico, `tb_vendas`.`id`,`tb_vendas`.`forma_pagamento`,`tb_veiculos`.`quilometragem` as quilometragem,`tb_clientes`.nome as colaborador, `tb_vendas`.`data`, `tb_vendas`.`valor` as total_valor,`tb_vendas`.`placa_carro` as nomes FROM `tb_vendas` INNER JOIN `tb_clientes` ON `tb_vendas`.`id_cliente` = `tb_clientes`.`id` INNER JOIN `tb_produtos_vendidos` ON `tb_vendas`.`id` = `tb_produtos_vendidos`.`id_venda`INNER JOIN `tb_produtos` ON `tb_produtos`.`id` = `tb_produtos_vendidos`.`id_produto` INNER JOIN `tb_colaboradores` ON `tb_vendas`.`colaborador` = `tb_colaboradores`.`codigo` INNER JOIN `tb_veiculos` ON tb_vendas.placa_carro = tb_veiculos.placa_carro WHERE DATE(`tb_vendas`.`data`) = ? GROUP BY `tb_vendas`.`id`; ");

        $row_vendas->execute(array(date('Y-m-d')));
        $row_vendas = $row_vendas->fetchAll();


        foreach ($row_vendas as $key => $value) {

            $data_banco = $value['data'];

            // Converte a string de data em um timestamp Unix usando a função strtotime()
            $timestamp = strtotime($data_banco);
     
            // Formata o timestamp para o formato desejado usando a função strftime()
            $valor_venda = $value['total_valor'];
            $produto = $value['nomes'] != "" ? strtoupper($value['nomes']) : "Pré Venda";
            $quilometragem = $value['quilometragem'] != "" ? $value['quilometragem']."KM" : "Pré Venda";
            if($value["nomes"] !=""){
                $subtitle ="title='Marca: ".$value['marca'].", Modelo: ".$value["modelo"]." e Placa: " . str_replace('_',' ',$value['nomes']) . "'";
            }else{
                $subtitle ="";
            }
            $data_formatada = strftime('%Hh%M-%d/%m/%Y', $timestamp);
            echo "
               <tr>
               <td> $data_formatada </td>
                <td title=' R$" . number_format($value['valor_produtos'],2 ). " em produtos e R$".  $value["valor_servico"]  ." \n pela mão de obra'> R$" . str_replace('.', ',', number_format($valor_venda,2)) . " </td>
                <td ".$subtitle.">  " .$produto. " </td>
                <td> " . $quilometragem . "  </td>

                <td> " . $value['colaborador'] . " </td>
                <td> <i onClick='gerarNotas(".$value["id_venda"].")' produto='" . $value['forma_pagamento'] . "' class='fa-regular fa-file-lines'></i> </td>

                <td> " . $value['forma_pagamento'] . " </td>
                <td><i id_venda='".$value["id_venda"]."'onClick='editarPreVenda(this)'class='fa-regular fa-pen-to-square'></i></td>
                <td><i id_venda='".$value["id_venda"]."' onClick='deletaPreVenda(this)'class='fa-regular fa-trash-can'></i></td>

                
                </tr>   ";
        }
    }
    public static function buscarDados($request)
    {
        $infos = \MySql::conectar()->prepare("SELECT 
            (SELECT `forma_pagamento`
             FROM `tb_vendas`
             WHERE DATE(`data`) = CURDATE()
             GROUP BY `forma_pagamento`
             ORDER BY COUNT(*) DESC
             LIMIT 1) AS formaPagamentoMaisRepetida,
             
            (SELECT COUNT(*)
             FROM `tb_vendas`
             WHERE DATE(`data`) = CURDATE()) AS quantidadeVendas,
             
            (SELECT SUM(`valor`) 
             FROM `tb_vendas`
             WHERE DATE(`data`) = CURDATE()) AS totalValor,
             
            (SELECT `tb_produtos`.`nome`
             FROM `tb_produtos` INNER JOIN `tb_produtos_vendidos` ON `tb_produtos_vendidos`.`id_produto` = `tb_produtos`.`id` INNER JOIN `tb_vendas` ON `tb_vendas`.`id` = `tb_produtos_vendidos`.`id_venda`
        
             WHERE DATE(`tb_vendas`.`data`) = CURDATE()
             GROUP BY `tb_produtos`.`id`
             ORDER BY SUM(tb_produtos_vendidos.quantidade_produto) DESC
             LIMIT 1) AS produtoMaisVendido;");
        $infos->execute();
        $infos = $infos->fetch();
        if ($request == 'totalValor') {
            print_r(number_format($infos[$request], 2, ',', '.'));
        } else {
            print_r($infos[$request]);
        }
    }
}
