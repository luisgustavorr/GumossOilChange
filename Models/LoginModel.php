<?php 

    namespace Models;

    class LoginModel
    {
        public static function enviarFormulario(){
//             $senha = "galo1313";

// // Criar o hash da senha
// $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
// $hashDaSenhaNoBanco = $senhaCriptografada;
@session_start();
if( \Painel::logado()==false){
    if(isset($_POST['logar'])){
    $user = $_POST['login'];
    $senha = $_POST['senha'];
    $logar = \MySql::conectar()->prepare('SELECT * FROM  `tb_colaboradores` WHERE `nome` = ? AND `senha` = ?');
    $logar->execute(array($user,$senha ));
    $logar = $logar->fetch();
    if($logar != false){
            setcookie("negal_ctaide", "true", time()+20*24*60*60,'/');
            setcookie("chilgo_zotmassael", $logar["administrador"], time()+20*24*60*60,'/');
            setcookie("zotmassael_usot", $logar["administrador"], time()+20*24*60*60,'/');
            setcookie("caixa", $logar["caixa"], time()+20*24*60*60,'/');
            setcookie("last_codigo_colaborador", $logar["codigo"], time()+20*24*60*60,'/');
            //zenit_polar
            $_SESSION['user'] = $user;
            $_SESSION['password'] = $senha;
            
            echo"<script>location.href='Home'</script>";
    
    }
  
    }
}else{
    echo"<script>location.href='Home'</script>";
}

        }
        }
    

?>