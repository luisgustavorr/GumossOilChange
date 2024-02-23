
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
$produto_relacionado_id = $_POST["produto_relacionado"];


$codigo = \MySql::conectar()->prepare("SELECT * FROM `tb_produtos` WHERE `codigo` = ?");
$codigo->execute(array($cod));
$codigo = $codigo->fetch();


if (!empty($codigo)) {
    print_r($codigo);
    echo "Codigo_barras_repetido";
    $aumentarEstoque = \MySql::conectar()->prepare("UPDATE tb_produtos SET quantidade = quantidade + ?  WHERE id = ?");
    $aumentarEstoque->execute(array($quantidade_prod,$cod));
} else {
    $insertProdutos = \MySql::conectar()->prepare("INSERT INTO `tb_produtos` (`id`, `nome`, `valor_venda`, `codigo`, `ncm`, `CST_ICMS`, `pICMS`, `quantidade`, `pCOFINS`, `pPIS`, `CFOP`, `CST_COFINS`, `CST_PIS`, `valor_compra`, `valor_atacado`, `unid_comercial`, `unid_tributavel`,`relacionado`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
    $insertProdutos->execute(array($cod,$nome,$preco_prod,$cod,$ncm,$cst_icms,$icms_prod,$quantidade_prod,$cofins,$pis,$cfop,$cst_cofins,$cst_pis,$custo_prod,$preco_atacado_prod,$unid_venda,$cst_unid_tributavel,$produto_relacionado_id));
    $atualizar_relacionado = \MySql::conectar()->prepare("UPDATE tb_produtos SET relacionado = ? WHERE id = ?");
    $atualizar_relacionado->execute(array($produto_relacionado_id,$produto_relacionado_id));
}




?>