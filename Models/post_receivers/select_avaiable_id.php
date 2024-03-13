 <?php 
include('../../MySql.php');
$codigo = $_POST["codigo"];
$codigoDisponível = false;
while (!$codigoDisponível) {
    $produto = \MySql::conectar()->prepare("SELECT * FROM `tb_produtos` WHERE id = ?");
    $produto->execute(array($codigo));
    $produto = $produto->fetch();
    if($produto == ""){
        $codigoDisponível = true;
        break;
    }
    $codigo +=1; 
  
}
print_r($codigo);

 ?>