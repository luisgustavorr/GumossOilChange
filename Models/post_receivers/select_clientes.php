
<?php
include('../../MySql.php');
if(isset($_POST['id'])){
    $infos = \MySql::conectar()->prepare("SELECT * FROM `tb_clientes` WHERE `id` LIKE  ?");
    $infos->execute(array($_POST['id']));
    $infos = $infos->fetch(PDO::FETCH_ASSOC);
    print_r(json_encode($infos,JSON_UNESCAPED_UNICODE));
}else{
    $caixas = \MySql::conectar()->prepare("SELECT * FROM `tb_clientes`");
    $caixas->execute();
    $caixas = $caixas->fetchAll();
    $relacionadosOrange = true;
    $lastRelacionado = 0;
    foreach ($caixas as $key => $value) {
  


        echo '<tr class =" produto_' . $value['id'] . '" value="' . $value['id'] . '">
                        <td class="nome">'. ucfirst($value['nome']) . '</td>
                        <td class="codigo">' . ucfirst($value['id']) . '</td>
                        <td class="codigo">' . ucfirst($value['telefone']) . '</td>
               
                        <td ><i produto="' . $value['id'] . '" class="fa-solid editar_cliente fa-pen"></i></td>

                        <td><i title="Excluir Permanentemente" produto="' . $value['id'] . '" class="fa-solid apagar_cliente fa-trash-can"></i></td>

                        </tr>';
    }

}
            ?>