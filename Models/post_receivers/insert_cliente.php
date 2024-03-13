<?php 
include('../../MySql.php');
$nome_fantasia = $_POST["nome_fantasia"];
if($nome_fantasia == ""){
    $nome_fantasia = " ";
}
$add_cliente = \MySql::conectar()->prepare("INSERT INTO `tb_clientes` (`id`, `nome`, `loja`, `telefone`, `CEP`, `estado`, `municipio`, `rua`, `numero`, `CPF`, `ie`,`tel`, `email`,`nome_fantasia`,`bairro`) VALUES (NULL, ?, ?, ?, ?, ?,?, ?, ?, ?,?, ?, ?, ?,?)");
$add_cliente->execute(array($_POST['nome'],"Principal",$_POST['tel'],$_POST['CEP'],$_POST['UF'],$_POST['municipio'],$_POST["rua"],$_POST["numero"],$_POST["CPF"],$_POST["IE"],$_POST["tel"],$_POST["email"], $nome_fantasia,$_POST["bairro"]));

?>