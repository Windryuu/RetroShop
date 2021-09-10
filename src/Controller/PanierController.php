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
}