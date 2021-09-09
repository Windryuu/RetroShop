<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use App\Repository\UserRepository;

class CommandeController extends AbstractController {
public function __construct()
{
    
}
    

    public function updateCommande(){
        if(isset($_SESSION["user"])){
            $title = "validation";
            $this->render('commande/validation.php',["title"=>$title]);
            $cmd = new CommandeRepository();
            $usr = new UserRepository();
            $user = $usr->getUserById(($_SESSION["user"])->getId_user());
            $commId = $cmd->getCommandeById_userCheckState($user,"panier");
            $upcomm = $cmd->updateCommande($user,$commId);
            
        }
    }

}