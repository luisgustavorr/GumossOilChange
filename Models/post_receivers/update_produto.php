<?php 
include('../../MySql.php');

$cfop = $_POST["cfop"];
$nome = $_POST["nome"];
$cod = $_POST["cod"];
$unid_venda = $_POST["unid_venda"];
$ncm = $_POST["ncm"];
$cst_pis = $_POST["cst_pis"];
$cst_cofins = $_POST["cst_cofins"];
$cst_icms = $_POST["cst_icms"];
$cst_unid_tributavel = $_POST["cst_unid_tributavel"];
$custo_prod = $_POST["custo_prod"];
$pis = str_replace("%","",$_POST["pPIS"]);
$cofins = str_replace("%","",$_POST["pCOFINS"]);
$preco_prod = $_POST["preco_prod"];
$quantidade_prod = $_POST["quantidade_prod"];
$preco_atacado_prod = $_POST["preco_atacado_prod"];
$icms_prod = str_replace("%","",$_POST["icms_prod"]);
echo $cfop;
$aumentarEstoque = \MySql::conectar()->prepare("UPDATE `tb_produtos` SET `id` = ?, `nome` = ?, `valor_venda` = ?, `codigo` = ?, `ncm` = ?, `CST_ICMS` = ?, `pICMS` = ?, `quantidade` = ?, `pCOFINS` = ?, `pPIS` = ?, `CFOP` = ?, `CST_COFINS` = ?, `CST_PIS` = ?, `valor_compra` = ?, `valor_atacado` = ?, `unid_comercial` = ?, `unid_tributavel` = ? WHERE `tb_produtos`.`id` = ?; ");
$aumentarEstoque->execute(array($cod,$nome,$preco_prod,$cod,$ncm,$cst_icms,$icms_prod,$quantidade_prod,$cofins,$pis,$cfop,$cst_cofins,$cst_pis,$custo_prod,$preco_atacado_prod,$unid_venda,$cst_unid_tributavel,$_POST["id_antigo"]));
?>