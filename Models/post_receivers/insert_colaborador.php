<?php 
include('../../MySql.php');
        $apagar_anterior = \MySql::conectar()->prepare("SELECT * FROM `tb_colaboradores` WHERE `codigo` = ?  ");
        $apagar_anterior->execute(array($_POST['codigo']));
        $apagar_anterior = $apagar_anterior->fetch();
        print_r($apagar_anterior);
        if(empty($apagar_anterior)){
            $apagar_anterior = \MySql::conectar()->prepare("INSERT INTO `tb_colaboradores` (`id`, `nome`, `codigo`,`administrador`,`caixa`,`senha`) VALUES (NULL, ?, ?,?,?,?);");
            $apagar_anterior->execute(array($_POST['nome'],$_POST['codigo'],$_POST['adm'],$_POST['caixa'],$_POST["senha"]));
        }else{
            echo 'ERROR';
        }
    
        
    
  
    ?>