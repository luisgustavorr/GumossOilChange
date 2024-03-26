<?php 
include('../../MySql.php');
$codigo = $_POST["codigo"];
$produto = \MySql::conectar()->prepare("SELECT * FROM `tb_colaboradores` WHERE senha = ?");
$produto->execute(array($codigo));
$produto = $produto->fetch();
if(!empty($produto)){
    echo (boolval(1) ? 'true' : 'false');
}else{
    echo (boolval(0) ? 'true' : 'false');
}

?>