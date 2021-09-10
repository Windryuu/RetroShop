<?php
$prixTot=0;

?>

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

    foreach($prixU as $keyItem => $item){
        if ($key == $keyItem) {
            echo 'prix unitaire:' ,$item ,' ';
            $prixUnit = $item;
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
    ?>
    <form action="commande/modification/<?=$panier->getId()?>" method="post">
    <label for="quantite">Quantité</label>
                    <select name="quantite" id="quantite" value="">
                        <option selected value="<?=$panier->getQuantite()?>"><?=$panier->getQuantite()?></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                    <button type="submit">ModifierQte</button>
    </form>
    <?php
    echo 'quantite : ' ,$panier->getQuantite(),' ';
    
    $prixLot = $prixUnit * $panier->getQuantite();
    echo 'prix de ensemble : ', $prixLot;
    $prixTot += $prixLot;
    echo '<br>';
    
    
}
echo 'prix du panier : ',$prixTot;
?>


<?php

?>
<a href="commande/validation"><button>Valider la commande</button></a>