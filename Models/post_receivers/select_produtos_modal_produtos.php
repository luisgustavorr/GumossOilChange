<?php 
include('../../MySql.php');
$caixas = \MySql::conectar()->prepare("SELECT * FROM `tb_produtos` WHERE `nome` LIKE ? AND caixa = ? ORDER BY relacionado DESC;");
$caixas->execute(array('%'.$_POST['produto'].'%',$_COOKIE["caixa"]));
$caixas = $caixas->fetchAll();
$relacionadosOrange = true;
$lastRelacionado = 0;
foreach ($caixas as $key => $value) {
    if($value["relacionado"] != ""){
        if($relacionadosOrange){
        
            $icon = '<i class="fa-solid fa-link" style="color: #F53F00;"></i>';
        }else{
            $icon = '<i class="fa-solid fa-link" style="color: #242424;"></i>';

        }
        if($lastRelacionado == $value["relacionado"]){
             $relacionadosOrange = !$relacionadosOrange;
        }
    }else{
        $icon = "";
    }

    $lastRelacionado = $value["relacionado"];
    $estoque = $value["quantidade"] != 0 ? $value["quantidade"] : '<i title="Produto sem estoque, caso isso seja um erro altere o estoque no campo Quantidade ao editar se esse produto." class="fa-solid fa-triangle-exclamation fa-beat" style="color: #ff0000;"></i>';
    echo '<tr class =" produto_' . $value['id'] . '" value="' . $value['id'] . '">
                    <td class="nome">'.$icon.' ' . ucfirst($value['nome']) . '</td>
                    <td class="codigo">' . ucfirst($value['codigo']) . '</td>
                    <td class="preco">R$' . $value["valor_compra"] . '</td>

                    <td class="preco">R$' . $value["valor_venda"] . '</td>
                    <td class="preco">R$' . $value["valor_atacado"] . '</td>

                    <td class="pesado">' . $estoque . '</td>
                    <td ><i produto="' . $value['id'] . '" class="fa-solid editar_produto fa-pen"></i></td>

                    <td><i title="Excluir Permanentemente" produto="' . $value['id'] . '" class="fa-solid apagar_produto fa-trash-can"></i></td>

                    </tr>';
}
?>