<?php 
include('../../MySql.php');

    $equip = \MySql::conectar()->prepare("INSERT INTO `tb_caixas` (`id`, `caixa`, `valor_atual`, `troco_inicial`, `valor_no_caixa`, `IE`, `CNPJ`, `CEP`, `numero`, `cUF`, `cMunicipio`) VALUES (NULL,?,?,?,?,?,?,?,?,?,?)");
    $equip->execute(array($_POST['nome_loja'],0,100,0,$_POST['IE_loja'],$_POST['CNPJ_loja'],$_POST['cep_loja'],$_POST['numero_loja'],$_POST['cUF'],$_POST['cMun_loja']));

    ?>