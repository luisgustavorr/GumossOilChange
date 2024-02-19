<?php 
include('../../MySql.php');
$caixas = \MySql::conectar()->prepare("SELECT * FROM `tb_produtos` WHERE `nome` LIKE ?");
$caixas->execute(array('%'.$_POST['produto'].'%'));
$caixas = $caixas->fetchAll();
foreach ($caixas as $key => $value) {
    $estoque = $value["quantidade"] != 0 ? $value["quantidade"] : '<i title="Produto sem estoque, caso isso seja um erro altere o estoque no campo Quantidade ao editar se esse produto." class="fa-solid fa-triangle-exclamation fa-beat" style="color: #ff0000;"></i>';
    echo '<tr class ="produto_' . $value['id'] . '" value="' . $value['id'] . '">
                    <td class="nome">' . ucfirst($value['nome']) . '</td>
                    <td class="codigo">' . ucfirst($value['codigo']) . '</td>

                    <td class="preco">R$' . $value["valor_venda"] . '</td>
                    <td class="pesado">' . $estoque . '</td>
                    <td ><i produto="' . $value['id'] . '" class="fa-solid editar_produto fa-pen"></i></td>

                    <td><i title="Excluir Permanentemente " produto="' . $value['id'] . '" class="fa-solid apagar_produto fa-trash-can"></i></td>

                    </tr>';
}
?>