<?php
ob_start();
include('https://mixpvd.mixsalgados.com/Views/pages/home.php');
$conteudo = ob_get_contents();
ob_end_clean();
echo $conteudo;

?>
