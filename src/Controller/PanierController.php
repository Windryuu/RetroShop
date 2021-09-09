<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\PanierRepository;

class PanierController extends AbstractController {
    //params est correspondance URI
    public function nouveauPanier($params) {
        //dd($params);
        $panier = new PanierRepository();
        $cart = $panier->newPanier($params);
        dump($cart);
    }
}