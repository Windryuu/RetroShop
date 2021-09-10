<?php

namespace App\Services;

use App\Core\Abstract\AbstractController;

class PanierServices extends AbstractController {

    public function __construct()
    {
        
    }

    public function updatePanier(){
        if(isset($_SESSION["user"])) {
            
            $state="panier";
            $user = $this->usr->getUserById(($_SESSION["user"])->getId_user());
            $commId = $this->cmd->getCommandeById_userCheckState($user,$state);
            
            if($_POST){
                
            }
        } else {
            echo 'meep';
        }
        return $commId;
    }
}