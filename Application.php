<?php

class Application 
{
    public function executar()
    {
        
     $url = isset($_GET['url'])? $_GET['url'] : 'Home';
     $url = ucfirst($url);
     $url.='Controller';
        $className ='Controllers\\'.$url;
        $controller = new $className();
        $controller->executar();
  
    }
}
/*  for ($i=0; $i < 30; $i++) { 
            echo"(NULL, 'nome', 'descricao', '100.00,00', '0', 'luis', '0', '1'),";
          }*/
?>