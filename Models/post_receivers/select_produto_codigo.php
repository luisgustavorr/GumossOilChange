<?php 
include('../../MySql.php');
$produto = \MySql::conectar()->prepare("SELECT * FROM `tb_produtos` WHERE id = ?");
$produto->execute(array($_POST['codigo']));
$produto = $produto->fetch();
if($produto != ""){
    print_r(json_encode($produto));

}
?>