<?php

namespace App\Services;


use App\Core\Abstract\AbstractController;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use App\Repository\UserRepository;

class CommandeServices extends AbstractController {

    private CommandeRepository $cmd;
    private UserRepository $usr;
    private PanierRepository $panier;

public function __construct()
{
    $this->cmd = new CommandeRepository();
    $this->usr = new UserRepository();
    $this->panier = new PanierRepository();
}
    
    public function commandeCheck(){
        if(isset($_SESSION["user"])) {
            
            $state="panier";
            $user = $this->usr->getUserById(($_SESSION["user"])->getId_user());
            $commId = $this->cmd->getCommandeById_userCheckState($user,$state);
            if (!$commId){
                $comm = $this->cmd->createCommande($user);
            }
        } else {
            echo 'meep';
            
        }
        return $commId;
    }

    public function getQteTotalePanier(){
        if(isset($_SESSION["user"])) {
            
            $state="panier";
            $user = $this->usr->getUserById(($_SESSION["user"])->getId_user());
            $commId = $this->cmd->getCommandeById_userCheckState($user,$state);
            if (!$commId){
                $comm = $this->cmd->createCommande($user);
            } else {
                $carts = $this->panier->getPanierbyId_commande($commId);
                $qtetotal = 0;
                foreach ($carts  as $cart ) {
                    $qtetotal += $cart->getQuantite();
                }
            }
        } else {
            echo 'meep';
            
        }
        return $qtetotal;
    }
}