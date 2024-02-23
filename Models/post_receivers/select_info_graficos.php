<?php 
require('../../MySql.php');

    $caixa ="";
    $caixa_pedidos ="";

$row = \MySql::conectar()->prepare("SELECT COUNT(placa_carro) as total_vendas,placa_carro FROM `tb_vendas` WHERE pre_venda != 1 AND DATE(data) BETWEEN ? AND ?  GROUP BY placa_carro ORDER BY total_vendas DESC");
$row->execute(array($_POST['data_min'],$_POST['data_max']));
$row = $row->fetchAll();
$row_caixa_podium = \MySql::conectar()->prepare("SELECT COUNT(`tb_vendas`.id_cliente) as total_vendas, `tb_clientes`.nome FROM `tb_vendas` INNER JOIN tb_clientes ON tb_vendas.id_cliente =tb_clientes.id WHERE `tb_clientes`.nome != 'Não Informado' AND DATE(data) BETWEEN ? AND ? GROUP BY `tb_clientes`.nome ORDER BY total_vendas DESC ");
$row_caixa_podium->execute(array($_POST['data_min'],$_POST['data_max']));
$row_caixa_podium = $row_caixa_podium->fetchAll();
 $array = [
 
 "Clientes_Mais_Frequentes"=>[
    "labels"=>"",
    "array_valores"=>[]
 ],"Veículos_Mais_Frequentes"=>[
    "labels"=>"",
    "array_valores"=>[]
 ]
            
 ];

 foreach ($row_caixa_podium as $key => $value) {
     array_push($array['Clientes_Mais_Frequentes']["array_valores"],[$value['nome'],$value["total_vendas"], 'color: red']);
     $array['Clientes_Mais_Frequentes']["labels"]="Veículos Atendidos";



 }
 foreach ($row as $key => $value) {
     array_push($array['Veículos_Mais_Frequentes']["array_valores"],[strtoupper($value['placa_carro']),intval($value['total_vendas']), 'color: red']);
     $array['Veículos_Mais_Frequentes']["labels"]="Unidade de Veículo";



 }
 print_r(json_encode($array));
