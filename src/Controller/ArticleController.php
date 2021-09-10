<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Entity\Panier;
use App\Repository\ArticleRepository;
use App\Entity\User;
use App\Repository\CommandeRepository;
use App\Services\CommandeServices;

class ArticleController extends AbstractController {

    private CommandeServices $commserv;

    public function __construct()
    {
        $this->commserv = new CommandeServices();
    }



    public function index () {
        $title = "articles";
        // Récupérer les objets et les stockent dans une variable sous forme de tableau 
        $repo = new ArticleRepository();
        $articles = $repo->getArticles();
        //dump($articles);
        $this->render("articles/Suggestion.php", [
            'articles' => $articles,
            'title'=> $title
        ]);
    }

    public function show($params)
    {
        $title = "gtfo";
        $repo = new ArticleRepository();
        
        $articles = $repo->getOneArticle($params[0]);
        $avisClients = $repo->showRemarkAndNote($params[0]);
        //dump($articles);
        //$commserv = new CommandeServices();
        $verifcommande = $this->commserv->commandeCheck();
        // dd($avisClients);
        if(!empty($_POST)){
            $cart = new PanierController();
            $crt = $cart->nouveauPanier(($verifcommande)->getId_commande(),$articles->getId_produit(),$_POST["quantite"]);
        }
        $this->render("articles/FicheProduit.php", [
            'articles' => $articles,
            'avisClients' => $avisClients,
            'title' => $title
        ]);
    }
     
    public function search()
    {
        $repo = new ArticleRepository();
        $this->render("articles/recherche.php", [
            'articles' => $repo->searchArticle(htmlspecialchars($_POST["terme"]))
        ]);
        
    }
}

