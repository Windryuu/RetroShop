<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\PanierRepository;

class PanierController extends AbstractController {
    //params est correspondance URI
    public function nouveauPanier($id_commande,$id_produit,$qte) {
        //dd($params);
        $panier = new PanierRepository();
        $cart = $panier->newPanier($id_commande,$id_produit,$qte);
        //dump($cart);
    }

    public function modificationPanier($params) {
        $qte = $_POST["quantite"];
        $panier = new PanierRepository();
        $cart = $panier->getPanierbyId($params[0]);
        $updatecart = $panier->updatePanier($cart,$qte);
        $oldqte = $cart->getQuantite();
        $this->render("commande/modification.php",[
            'oldqte' =>$oldqte
        ]);
    }
}