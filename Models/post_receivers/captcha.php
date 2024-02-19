<?php 
include('../../MySql.php');
$caixas = \MySql::conectar()->prepare("SELECT * FROM `tb_colaboradores`");
$caixas->execute();
$caixas = $caixas->fetchAll();
foreach ($caixas as $key => $value) {
    $value['administrador'] == 1 ? $adm = 'Sim' : $adm = 'Não';
    echo '<tr value="' . $value['codigo'] . '">
                    <td>' . ucfirst($value['codigo']) . '</td>

                    <td>' . ucfirst($value['nome']) . '</td>
                    <td>' . $adm . '</td>
                    <td>' . $value["caixa"] . '</td>

                    <td><i pessoa="' . $value['id'] . '" class="fa-solid fa-trash-can"></i></td>

                    </tr>';
}
?>