<?php
include('../../MySql.php');
if (isset($_POST["nome_fantasia"])) {
    $nome_fantasia = $_POST["nome_fantasia"];
} else {
    $nome_fantasia = "";
}
if ($nome_fantasia == "") {
    $nome_fantasia = " ";
}
$_POST["id"] = 93;
$add_cliente = \MySql::conectar()->prepare("UPDATE `tb_clientes` SET `nome` = ?, `loja` = ?, `telefone` = ?, `CEP` = ?, `estado` = ?, `municipio` = ?, `rua` = ?, `numero` = ?, `CPF` = ?, `ie` = ?, `tel` = ?, `email` = ?, `nome_fantasia` = ?, `bairro` = ? WHERE `tb_clientes`.`id` = ?");
$add_cliente->execute(array($_POST['nome'], "Principal", $_POST['tel'], $_POST['CEP'], $_POST['UF'], $_POST['municipio'], $_POST["rua"], $_POST["numero"], $_POST["CPF"], $_POST["IE"], $_POST["tel"], $_POST["email"], $nome_fantasia, $_POST["bairro"], $_POST["id"]));
