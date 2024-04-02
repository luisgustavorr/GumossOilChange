<?php 
include('../../MySql.php');
    
    $apagar_anterior = \MySql::conectar()->prepare("UPDATE `tb_produtos` SET deletado = 1 WHERE id = ?");
    $apagar_anterior->execute(array($_POST['id']));
?>