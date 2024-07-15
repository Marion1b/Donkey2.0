<?php

class PageController extends AbstractController{
    private TicketManager $tm;
    private ArtistManager $am;
    private UserManager $um;
    public function __construct()
    {
        parent::__construct();
        $this->tm = new TicketManager();
        $this->am = new ArtistManager();
        $this->um = new UserManager();
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
            $tickets = $this->tm->findByUserId($_SESSION["user"]->getId());
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
        $tickets = $this->tm->findByEmail($_SESSION["post_data"]["email"]);
        $this->render("paiement-valide.html.twig", ["tickets"=>$tickets]);
    }

    public function paymentCancel():void{
        $this->render("paiement-invalide.html.twig", []);
    }

    public function programmation():void{
        $artists = $this->am->findAll();
        if(isset ($_SESSION["user"])){
            // Check if a user is connected to add the button for the personnal artist list
            $favoriteArtistsId = $this->um->getFavoriteArtists();
            $this->render("programmation.html.twig", ["artists"=>$artists, "favoriteArtistsId" => $favoriteArtistsId]);   
        }
        $this->render("programmation.html.twig", ["artists"=>$artists]);
    }

    public function programmationbyDay(string $day):void{
        $artists = $this->am->findByDay($day);
        $this->render("programmation.html.twig", ["artists"=>$artists]);
    }

    public function artist():void{
        $artist = $this->am->findByName($_GET["artiste"]);
        if($artist !== null){
            $this->render("fiche-artiste.html.twig", ["artist"=>$artist]);
        }else{
            $this->error();
        }
        
    }

    public function artistList():void{
        $isFavorite = $this->um->isFavorite();
        if($isFavorite === false){
            $this->um->addFavorite();
        }else{
            $this->um->removeFavorite();
        }
        $this->redirect("index.php?route=programmation");
    }

    public function personnalArtistList():void{
        if(isset($_POST["artist"])){
            $isFavorite = $this->um->isFavorite();
            if($isFavorite === false){
                $this->um->addFavorite();
            }else{
                $this->um->removeFavorite();
            }
        }
        
        $this->redirect("index.php?route=espace-perso&&user-id=" . $_SESSION['user']->getId());
    }

    public function showArtistList():void{
        $isArtist = $this->am->showFavoriteArtists();
        if($isArtist!==null){
            $this->render("espace-perso.html.twig",["favoriteArtists"=>$isArtist]);
        }else{
            $this->render("espace-perso.html.twig", []);
        }
    }

    public function personnalProg(string $day):void{
        $artists = $this->am->getFavArtistByDay($day);
        if($artists !== null){
            $this->render("programmation-perso.html.twig", ["artists"=>$artists]);
        }else{
            $this->render("programmation-perso.html.twig", []);
        }
    }

    public function dys():void{
        if(isset($_COOKIE["dys"]) && $_COOKIE["dys"] === "on"){
            setcookie("dys", "off");
        }else{
            setcookie("dys", "on");
        }
        // To go back to the previous page
        if(isset($_SERVER['HTTP_REFERER'])){
            $this->redirect($_SERVER["HTTP_REFERER"]);
        }else{
            $this->redirect("index.php?route=home");
        }
    }

    public function error():void{
        $this->render("error404.html.twig", []);
    }

}