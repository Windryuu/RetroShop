<?php

foreach($paniers as $key => $panier) {
    echo 'commande n°' , $panier->getId_commande(),' ';
    echo 'produit n°' , $panier->getId_produit(),' ';
    
    foreach($nomItem as $keyItem => $item){
        if ($key == $keyItem) {
            echo 'produit name :' ,$item ,' ';
        } else {
            continue;
        }
    };

    foreach($refItem as $keyItem => $refitem){
        if ($key == $keyItem) {
            echo 'produit ref :' ,$refitem ,' ';
        } else {
            continue;
        }
    };

    echo 'quantite : ' ,$panier->getQuantite(),' ';
    echo '<br>';
}

?>
<a href="commande/validation"><button>Valider la commande</button></a>