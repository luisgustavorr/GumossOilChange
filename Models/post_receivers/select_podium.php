
<?php
            if($_POST['caixa'] != 'todos'){
                $caixa =  "AND `tb_vendas`.`caixa` = '".$_POST['caixa']."'";
                $caixa_pedidos =  "AND `tb_pedidos`.`caixa` = '".$_POST['caixa']."'";
            
            }else{
                $caixa ="";
                $caixa_pedidos ="";
            }
include('../../MySql.php');
            $prefix = '<div class="tabela_father">
            <div class="tabela_header">
            <i id="voltar_semana" onclick="mudarTempo(this)"  class="fa-solid fa-angle-left modificadores_tempo "></i><span><i class="fa-solid fa-chart-line" id="show_graphs"></i> Vendas no dia: <yellow>20/07/2023</yellow> <i   class="gerar_pdf fa-regular fa-file-pdf"></i></span><i id="adiantar_semana"onclick="mudarTempo(this)"  class="fa-solid fa-angle-right modificadores_tempo adiantar_semana"></i>
            </div>
            <table id="table_tabela">
                <thead>
                    <tr>
                        <th>Podium</th>
                        <th>Produto</th>
                        <th>Quantidade de Vendas</th>
                        <th>Valor Uni</th>
                        <th>Valor total de Vendas</th>
                    </tr>
                </thead>
                <tbody>';
            $row = \MySql::conectar()->prepare("SELECT
            nome,
            valor_unidade,
            valor_total,
            total_vendas
        FROM (
            -- Primeira consulta
            SELECT
                jt.id AS nome,
                jt.preco AS valor_unidade,
                SUM(jt.preco * jt.quantidade) AS valor_total,
                COUNT(jt.quantidade) AS total_vendas
            FROM
                tb_pedidos,
                JSON_TABLE(produtos, '$[*]' COLUMNS (
                    id VARCHAR(255) PATH '$.id',
                    quantidade INT PATH '$.quantidade',
                    preco DECIMAL(10, 2) PATH '$.preco'
                )) AS jt
            WHERE
                DATE(tb_pedidos.data_pedido) BETWEEN ? AND ? ".$caixa_pedidos." AND `tb_pedidos`.`entregue` = 1
            GROUP BY
                jt.id
        
            UNION
        
            -- Segunda consulta
            SELECT
                p.nome,
                p.preco AS valor_unidade,
                valor_vendas.valor_vendas_produto AS valor_total,
                vendas_contadas.total_vendas
            FROM
                tb_produtos p
            INNER JOIN (
                SELECT
                    produto,
                    total_vendas
                FROM (
                    SELECT
                        produto,
                        COUNT(id) AS total_vendas
                    FROM
                        tb_vendas
                    WHERE
                        DATE(data) BETWEEN ? AND ? ".$caixa."
                    GROUP BY
                        produto
                ) vendas_contadas,
                (SELECT @row_number := 0) AS rn_init
            ) vendas_contadas ON p.id = vendas_contadas.produto
            LEFT JOIN (
                SELECT
                    produto,
                    SUM(valor) AS valor_vendas_produto
                FROM
                    tb_vendas
                WHERE
                    DATE(data) BETWEEN ? AND ? ".$caixa."
                GROUP BY
                    produto
            ) valor_vendas ON p.id = valor_vendas.produto
        ) AS combined_data
        ORDER BY
            total_vendas DESC,
            valor_total DESC;");
            $row->execute(array($_POST['data_min'],$_POST['data_max'],$_POST['data_min'],$_POST['data_max'],$_POST['data_min'],$_POST['data_max']));
            $row = $row->fetchAll();
            $ordem = 1;
            $array = [
            
        ];
            foreach ($row as $key => $value) {
            array_push($array,[$value['nome'],intval($value['total_vendas']), 'color: #76A7FA']);


                if(isset($_POST['switch'])){
                    $prefix .="
                    <tr>
                    <td>".$ordem."#</td>
                     <td>".$value['nome']."</td>
                     <td >".$value['total_vendas']."</td>
                     <td>R$".number_format(str_replace(",",".",$value['valor_unidade']),2)."</td>
                     <td>R$".number_format($value['valor_total'],2) ."</td>

                     </tr>";
                }else{
                    echo "
                    <tr>
                    <td>".$ordem."#</td>
                     <td>".$value['nome']."</td>
                     <td >".$value['total_vendas']."</td>
                     <td>R$".number_format(str_replace(",",".",$value['valor_unidade']),2)."</td>
                     <td>R$".number_format($value['valor_total'],2) ."</td>

                     </tr>";
                }
                  
                $ordem +=1;
            
            }
          
            if(isset($_POST['switch'])){
                echo $prefix."            </tr>
                </tbody>
            </table>
        </div>";
            }
            echo "<div id='chart_info_id' chart_info='".json_encode($array)."'></div>";
            ?>
