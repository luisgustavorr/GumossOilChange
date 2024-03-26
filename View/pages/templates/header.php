
<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="./icons/css/all.css" />

    <link rel="stylesheet"  href="<?php echo INCLUDE_PATH?>View/pages/style/style.css" />
    <title>AutoLub</title>
    <script type="text/javascript" src="<?php echo INCLUDE_PATH?>js/jquery.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src=" https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js "></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js" integrity="sha512-zP5W8791v1A6FToy+viyoyUUyjCzx+4K8XZCKzW28AnCoepPNIXecxh9mvGuy3Rt78OzEsU+VCvcObwAMvBAww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css" integrity="sha512-0V10q+b1Iumz67sVDL8LPFZEEavo6H/nBSyghr7mm9JEQkOAm91HNoZQRvQdjennBb/oEuW+8oZHVpIKq+d25g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>
<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<link rel="apple-touch-icon" sizes="180x180" href="<?php echo INCLUDE_PATH ?>Favicon/apple-touch-icon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo INCLUDE_PATH ?>Favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo INCLUDE_PATH ?>Favicon/favicon-16x16.png">
<link rel="manifest" href="<?php echo INCLUDE_PATH ?>Favicon/site.webmanifest">
<link rel="mask-icon" href="<?php echo INCLUDE_PATH ?>Favicon/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">


  </head>
  <body>
     <script>0</script>
  <header>
      <img onclick=" window.location.reload(true)" src="<?php echo INCLUDE_PATH?>Favicon/android-chrome-192x192.png" style="
object-fit: contain;" />
      <div class="right_side">
        <span><i class="fa-regular fa-clock"></i> <date_now class=" horario_atual_finder">Seg: 10/07/2023 10h40</date_now></span>
        <a target="_blank" href="https://localhost/SistemaAutolub/SETUP.rar" download="OctopusXMLPrinter.rar">Baixar Sistema Impressora</a>
        
        <?php 
          if(isset($_COOKIE["chilgo_zotmassael"])){
            $modo_atual = "Caixa";

            if($_COOKIE["zotmassael_usot"] == 1){
              $modo_atual = "Administrador";

            }

          if($_COOKIE["chilgo_zotmassael"] == 1){
        ?>
        <i title="Trocar Visualização | * Modo Atual : <?php echo   $modo_atual;?> *" id="chilgo_zotmassael" class="fa-solid fa-arrows-rotate"></i>
        <?php }?>
        <i title="Sair" id="logout" class="fa-solid fa-right-from-bracket"></i>
        <?php }?>
      </div>
    </header>
    
   