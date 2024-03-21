<?php 
namespace Controllers;
class LoginController
{
    
    public function __construct()
    {

        $this->view = new \View\MainView('login');
    }
    public function executar()
    {
        isset($_COOKIE['negal_ctaide']) ?  $this->view = new \View\MainView('home') :  $this->view = new \View\MainView('login');
        
      
       
        
            $this->view ->render(array('titulo'=>'Painel de Controle'));
    }
}
