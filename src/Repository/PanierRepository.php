<?php

namespace App\Repository;

use App\Core\Database\AccesseurDB;
//use App\Entity\Article;
use App\Entity\Commande;
use App\Entity\Panier;
use PDO;

class PanierRepository {
    private PDO $pdo;

    public function __construct()
    {
        $userbd = new AccesseurDB();
        $this->pdo = $userbd->getPdo();
    }

    public function newPanier($id_commande,$id_produit,$qte){
        $req = $this->pdo->prepare(
        "INSERT INTO panier (id_commande,id_produit,quantite) 
                        VALUES ( :id_commande,:id_produit,:quantite) ");
        $req->execute([
            ":id_commande" => $id_commande,
            ":id_produit" => $id_produit,
            ":quantite" => $qte
        ]);
    }

    public function getPanierbyId_commande(Commande $commande) {
        $req = $this->pdo->prepare("SELECT * FROM panier WHERE id_commande = :id_commande ORDER BY id ASC");
        $req->execute([
            ":id_commande" => $commande->getId_commande()
        ]);
        //$req->setFetchMode(PDO::FETCH_CLASS,Panier::class);
        
        $result = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $key => $articlePanier){
            $result[$key] = (new Panier())
            ->setId($articlePanier["id"])
            ->setId_commande($articlePanier["id_commande"])
            ->setId_produit($articlePanier["id_produit"])
            ->setQuantite($articlePanier["quantite"]);
        }
        //$result['commande'] = $commande;
        return $result;
        
    }

    public function getPanierbyId($id) {
        $req = $this->pdo->prepare("SELECT * FROM panier WHERE id= :id");
        $req->execute([
            ":id" => $id
        ]);
        $req->setFetchMode(PDO::FETCH_CLASS,Panier::class);
        
        $result = $req->fetch();
        return $result;
        
    }

    public function updatePanier(Panier $panier,$qte){
        //$article = new Article();
        $req = $this->pdo->prepare("UPDATE panier SET
        quantite = :quantite 
        WHERE id = :id ");
        $req->execute([
            ":id" => $panier->getId(),
            ":quantite" => $qte
        ]);
    }

    public function deletePanier(Panier $panier){
        $req = $this->pdo->prepare("DELETE * FROM panier WHERE ...");
        $req->execute([
            ":id" => $panier->getId()
        ]);
    }

}