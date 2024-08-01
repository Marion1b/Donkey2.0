<?php

use Dompdf\Dompdf;

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
        $artists = $this->am->findAll();
        $this->render("home.html.twig", ["artists" => $artists]);
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

    public function contact():void{
        $this->render("contact.html.twig", []);
    }

    public function information():void{
        $this->render("infos-pratiques.html.twig", []);
    }

    public function rgpd():void{
        $this->render("rgpd.html.twig", []);
    }

    public function mentionsLegales():void{
        $this->render("mentions-legales.html.twig", []);
    }

    public function personnalSpace():void{
        if(isset($_SESSION["user"]) && (string) $_SESSION["user"]->getId() === $_GET["user-id"]){
            $this->showArtistList();
        }else{
            $this->connexion();
        }
    }

    public function adminSpace():void{
        $csrft = new CSRFTokenManager();
        if(isset($_POST["user-search"])){
            if (!empty($_POST['csrf-token']) && $csrft->validateCSRFToken($_SESSION["csrf_token"])){
                $usersFind = $this->um->findUser($_POST["user-search"]);
                $this->render("espace-admin.html.twig", ["usersFind"=>$usersFind]);
            }
        }else if(isset($_POST["ticket-search"])){
            if (!empty($_POST['csrf-token']) && $csrft->validateCSRFToken($_SESSION["csrf_token"])){
                $ticketsFind = $this->tm->findTicket($_POST["ticket-search"]);
                $this->render("espace-admin.html.twig", ["ticketsFind"=>$ticketsFind]);
            }
        }else if(isset($_GET["utilisateur-id"])){
            $user = $this->um->findById((int) $_GET["utilisateur-id"]);
            $this->render("modifier-user.html.twig", ["user"=> $user]);
        }else{
            $this->render("espace-admin.html.twig", []);
        }
    }

    public function checkModifUser():void{
        $csrft = new CSRFTokenManager();
        if (!empty($_POST['csrf-token']) && $csrft->validateCSRFToken($_SESSION["csrf_token"])){
            $this->um->modifyUser();
            $this->redirect("index.php?route=espace-admin&&admin-id=" . $_SESSION["user"]->getId());
        }
    }

    public function checkDeleteUser():void{
        $csrft = new CSRFTokenManager();
        if (!empty($_POST['csrf-token']) && $csrft->validateCSRFToken($_SESSION["csrf_token"])){
            if(isset($_POST["delete-user-tickets"])){
                $this->um->deleteUserWithTickets($_POST["modal-user-email"]);
                $this->redirect("index.php?route=espace-admin&&admin-id=" . $_SESSION["user"]->getId());
            }else{
                $this->um->deleteUser($_POST["modal-user-email"]);
                $this->redirect("index.php?route=espace-admin&&admin-id=" . $_SESSION["user"]->getId());
            }
        }
    }

    public function checkDeleteTicket():void{
        $csrft = new CSRFTokenManager();
        if (!empty($_POST['csrf-token']) && $csrft->validateCSRFToken($_SESSION["csrf_token"])){
            $this->tm->deleteTicket($_POST["modal-ticket-id"]);
            $this->redirect("index.php?route=espace-admin&&admin-id=" . $_SESSION["user"]->getId());
        }
    }

    public function getTicket():void{
        if(isset($_SESSION["user"]) && (string) $_SESSION["user"]->getId() === $_GET["user-id"]){
            $tickets = $this->tm->findByUserId($_SESSION["user"]->getId());
            if($tickets !== null){
                $this->render("tickets.html.twig", ["tickets"=>$tickets]);
            }else{
                $this->render("tickets.html.twig", []);
            }
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
        }else{
            $this->render("programmation.html.twig", ["artists"=>$artists]);
        }
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
        $this->render("espace-perso.html.twig",["favoriteArtists"=>$isArtist]);
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

    public function download(){
        $ticket = $this->tm->findById($_GET["file"]);
        if($ticket !== null){
            $filename = $_GET["file"];
            $code = base64_decode($ticket->getCode());
            $dompdf = new Dompdf();
            $html = "<h1>Donkey</h1><h2>Du 05 au 07 juillet 2024</h2><p>Vendredi à partir de 18h <br> Samedi et dimanche à partir de 14h</p><p>Parc du Thabor, 35000 Rennes</p><h3>" . $ticket->getContent() . "</h3><h3>" .$ticket->getTarif() . "</h3></p>". $ticket->getId() ."</p><br><br>" . $code . "<br><br><p>En achetant ce billet, vous acceptez les conditions générales suivantes : Ce billet est non transférable et non remboursable. L'entrée au festival est strictement réservée aux personnes munies d'un billet valide. Le festival se réserve le droit de refuser l'entrée à toute personne présentant un comportement inapproprié ou dangereux. La consommation d'alcool et de drogues est interdite sur le site du festival. Les mineurs doivent être accompagnés d'un adulte responsable. Le festival décline toute responsabilité en cas de perte ou de vol du billet. En cas de problème, veuillez contacter l'organisateur du festival.</p>";
            $dompdf->loadHtml($html);
            $dompdf->render();
            $dompdf->stream($filename . '.pdf');
        }else{
            echo "Pas de billet";
        }
    }

    public function error():void{
        $this->render("error404.html.twig", []);
    }

}