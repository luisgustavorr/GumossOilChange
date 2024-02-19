<?php 
include('../../MySql.php');

    $apagar_anterior = \MySql::conectar()->prepare("DELETE FROM `tb_produtos` WHERE id = ?");
    $apagar_anterior->execute(array($_POST['id']));
?>