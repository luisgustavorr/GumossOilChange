<?php 
include('../../MySql.php');

$add_cliente = \MySql::conectar()->prepare("INSERT INTO `tb_clientes` (`id`, `nome`, `loja`, `telefone`, `CEP`, `estado`, `municipio`, `rua`, `numero`, `CPF`, `tel`, `email`,`nome_fantasia`,`bairro`) VALUES (NULL, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?,?)");
$add_cliente->execute(array($_POST['nome'],"Principal",$_POST['tel'],$_POST['CEP'],$_POST['UF'],$_POST['municipio'],$_POST["rua"],$_POST["numero"],$_POST["CPF"],$_POST["tel"],$_POST["email"],$_POST["nome_fantasia"],$_POST["bairro"]));

?>