<?php
include('../../MySql.php');
require __DIR__ . '/../../vendor/autoload.php';
date_default_timezone_set('America/Sao_Paulo');
try {
    $user = \MySql::conectar()->prepare("SELECT * FROM `tb_colaboradores`  WHERE `codigo` = ?");
    $user->execute(array($_POST['codigo_colaborador_informado_fechamento']));
    $user = $user->fetch();
    $caixa = \MySql::conectar()->prepare("SELECT * FROM `tb_equipamentos` WHERE `caixa` = ?");
    $caixa->execute(array(trim($user['caixa'])));
    $caixa = $caixa->fetch();
    $atualizar_db = \MySql::conectar()->prepare("UPDATE `tb_lojas` SET `valor_no_caixa` = 00 WHERE `tb_lojas`.`caixa` = ? ");
    $atualizar_db->execute(array(trim($caixa['caixa'])));
    $equip = \MySql::conectar()->prepare("INSERT INTO `tb_fechamento` (`id`, `dinheiro`, `cartao`, `moeda`, `pix`, `sangria`, `data`,`caixa`,`colaborador`) VALUES (NULL, ?, ?, ?, ?, ?, ?,?,?)");
    $equip->execute(array(str_replace(',', '.', str_replace('.', '', $_POST['dinheiro_informadas'])), str_replace(',', '.', str_replace('.', '', $_POST['cartao_informadas'])), str_replace(',', '.', str_replace('.', '', $_POST['moedas_informadas'])), str_replace(',', '.', str_replace('.', '', $_POST['pix_informadas'])), str_replace(',', '.', str_replace('.', '', $_POST['sangria_informadas'])), date("Y-m-d"), $caixa["caixa"], $_POST['codigo_colaborador_informado_fechamento']));
    $equip = $equip->fetch();
} catch (Exception $e) {
    echo 'ERRO: Preencha todos os valores!' . $e;
}
