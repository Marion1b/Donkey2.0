<?php

class PageController extends AbstractController{
    public function __construct()
    {
        parent::__construct();
    }

    public function home():void{
        $this->render("home.html.twig", []);
    }

    public function connexion():void{
        $this->render("connexion.html.twig", []);
    }

    public function inscription():void{
        $this->render("inscription.html.twig", []);
    }

    public function logout():void{
        session_destroy();
        $this->redirect("index.php?route=connexion");
    }

    public function personnalSpace():void{
        if(isset($_SESSION["user"]) && (string) $_SESSION["user"]->getId() === $_GET["user-id"]){
            $this->showArtistList();
        }else{
            $this->connexion();
        }
    }

    public function getTicket():void{
        if(isset($_SESSION["user"]) && (string) $_SESSION["user"]->getId() === $_GET["user-id"]){
            $tm = new TicketManager();
            $tickets = $tm->findByUserId($_SESSION["user"]->getId());
            $this->render("tickets.html.twig", ["tickets"=>$tickets]);
        }else{
            $this->ticketing();
        }
    }

    public function ticketing():void{
        $this->render("billetterie.html.twig",[]);
    }

    public function buyTickets():void{
        $this->render("achat-billets.html.twig", []);
    }

    public function paymentSuccess():void{
        $tm = new TicketManager();
        $tickets = $tm->findByEmail($_SESSION["post_data"]["email"]);
        $this->render("paiement-valide.html.twig", ["tickets"=>$tickets]);
    }

    public function paymentCancel():void{
        $this->render("paiement-invalide.html.twig", []);
    }

    public function programmation():void{
        $am = new ArtistManager();
        $artists = $am->findAll();
        if(isset ($_SESSION["user"])){
            $um = new UserManager();
            $favoriteArtistsId = $um->getFavoriteArtists();
            $this->render("programmation.html.twig", ["artists"=>$artists, "favoriteArtistsId" => $favoriteArtistsId]);   
        }
        $this->render("programmation.html.twig", ["artists"=>$artists]);
    }

    public function programmationbyDay(string $day):void{
        $am = new ArtistManager();
        $artists = $am->findByDay($day);
        $this->render("programmation.html.twig", ["artists"=>$artists]);
    }

    public function artist():void{
        $am = new ArtistManager();
        $artist = $am->findByName($_GET["artiste"]);
        if($artist !== null){
            $this->render("fiche-artiste.html.twig", ["artist"=>$artist]);
        }else{
            $this->error();
        }
        
    }

    public function artistList():void{
        $um = new UserManager();
        $isFavorite = $um->isFavorite();
        if($isFavorite === false){
            $um->addFavorite();
        }else{
            $um->removeFavorite();
        }
        $this->redirect("index.php?route=programmation");
    }

    public function personnalArtistList():void{
        $um = new UserManager();
        $isFavorite = $um->isFavorite();
        if($isFavorite === false){
            $um->addFavorite();
        }else{
            $um->removeFavorite();
        }
        $this->redirect("index.php?route=espace-perso&&user-id=" . $_SESSION['user']->getId());
    }

    public function showArtistList():void{
        $am = new ArtistManager();
        $isArtist = $am->showFavoriteArtists();
        if($isArtist!==null){
            $this->render("espace-perso.html.twig",["favoriteArtists"=>$isArtist]);
        }else{
            $this->render("espace-perso.html.twig", []);
        }
    }

    public function error():void{
        $this->render("error404.html.twig", []);
    }

}