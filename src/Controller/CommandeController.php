<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\ArticleRepository;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use App\Repository\UserRepository;
use App\Services\CommandeServices;

class CommandeController extends AbstractController {

    private CommandeServices $commserv;

public function __construct()
{
    $this->commserv = new CommandeServices();
}
    public function showCommande(){
        $title = 'bleep';
        $verifcommande = $this->commserv->commandeCheck();
        $cart = new PanierRepository();
        $crt = $cart->getPanierbyId_commande($verifcommande);
        $article = new ArticleRepository();
        foreach($crt as $item) {
            $art = $article->getOneArticle($item->getId_produit());
            $nameItem[] = $art->getNom_produit();
            $refItem[] = $art->getRef();
            $prixUnite[] = $art->getPrix_unitaire();
        }
        
        $this->render("commande/maCommande.php", [
            'paniers' => $crt,
            'nomItem' => $nameItem,
            'refItem' => $refItem,
            'prixU' => $prixUnite,
            'title' => $title
        ]);
    }

    public function validationCommande(){
        if(isset($_SESSION["user"])){
            $title = "validation";
            
            $cmd = new CommandeRepository();
            $usr = new UserRepository();
            $user = $usr->getUserById(($_SESSION["user"])->getId_user());
            $commId = $cmd->getCommandeById_userCheckState($user,"panier");
            $upcomm = $cmd->validationCommande($user,$commId);
            $this->render('commande/validation.php',["title"=>$title]);
        }
    }

}