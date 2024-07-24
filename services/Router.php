<?php

class Router{
    private AuthController $ac;
    private PageController $pc;
    private PayController $pay;
    public function __construct()
    {
        $this->ac = new AuthController();
        $this->pc = new PageController();
        $this->pay = new PayController();
    }

    public function handleRequest(array $get):void{
        if(!isset($get["route"])){
            $this->pc->home();
        }else if(isset($get["route"]) && $get["route"]==="connexion"){
            $this->pc->connexion();
        }else if(isset($get["route"]) && $get["route"]==="checkLogin"){
            $isLoginCorrect = $this->ac->checkLogin($_POST);
            if($isLoginCorrect !== null){
                $_SESSION["user"]=$isLoginCorrect;
            }
        }else if(isset($get["route"]) && $get["route"] === "inscription"){
            $this->pc->inscription();
        }
        else if(isset($get["route"]) && $get["route"]==="checkSignUp"){
            $isSignUpCorrect = $this->ac->checkSignUp($_POST);
            if($isSignUpCorrect !== null){
                $_SESSION["user"]=$isSignUpCorrect;
            }
        }else if(isset($get["route"]) && $get["route"] === "logout"){
            $this->pc->logout();
        }else if(isset($get["route"]) && $get["route"] === "espace-perso"){
            $this->pc->personnalSpace();
        }else if(isset($get["route"]) && $get["route"] === "billets"){
            $this->pc->getTicket();
        }else if(isset($get["route"]) && $get["route"] === "download"){
            if(isset($get["file"]) && isset($get["user"]) && (int) $get["user"] === $_SESSION["user"]->getId()){
                $this->pc->download();
            }else{
                var_dump($get["user"]);
            }
        }else if(isset($get["route"]) && $get["route"] === "espace-admin"){
            if((int) $get["admin-id"] === $_SESSION["user"]->getId() && $_SESSION["user"]->getAdmin() === "ADMIN"){
                $this->pc->adminSpace();
            }
        }else if(isset($get["route"]) && $get["route"] === "search-user"){
            $this->pc->searchUser($_POST["user-search"]);
        }else if(isset($get["route"]) && $get["route"] === "billetterie"){
            $this->pc->ticketing();
        }else if(isset($get["route"]) && $get["route"] === "achat-billets"){
            $this->pc->buyTickets();
        }else if(isset($get["route"]) && $get["route"] === "paiement"){
            $_SESSION["post_data"]=$_POST;
            $this->pay->checkPay();
        }else if(isset($get["route"]) && $get["route"]=== "paiement-valide"){
            $randomId = (int) $get["id"];
            if(isset($get["id"]) && isset($_SESSION['randomId']) && $randomId === $_SESSION['randomId']){
                $post = $_SESSION['post_data'];
                $this->pay->generateTicket($post);
                $this->pc->paymentSuccess();
                $_SESSION['post_data']="";
                $_SESSION['randomId']="";
            }
        }else if(isset($get["route"]) && $get["route"] === "paiement-invalide"){
            $this->pc->paymentCancel();
        }else if(isset($get["route"]) && $get["route"] === "programmation"){
            if(isset($get["jour"]) && $get["jour"] === "vendredi"){
                $this->pc->programmationbyDay("2024-07-05");
            }else if(isset($get["jour"]) && $get["jour"] === "samedi"){
                $this->pc->programmationbyDay("2024-07-06");
            }else if(isset($get["jour"]) && $get["jour"] === "dimanche"){
                $this->pc->programmationbyDay("2024-07-07");
            }else{
                $this->pc->programmation();
            }
        }else if(isset($get["route"]) && $get["route"]==="fiche-artiste"){
            $this->pc->artist();
        }else if(isset($get["route"]) && $get["route"] === "liste-artiste"){
            $this->pc->artistList();
        }else if(isset($get["route"]) && $get["route"] === "liste-artiste-fav"){
            $this->pc->personnalArtistList();
        }else if(isset($get["route"]) && $get["route"] === "programmation-perso"){
            if(isset($get["jour"]) && $get["jour"] === "vendredi"){
                $this->pc->personnalProg("2024-07-05");
            }else if(isset($get["jour"]) && $get["jour"] === "samedi"){
                $this->pc->personnalProg("2024-07-06");
            }else if(isset($get["jour"]) && $get["jour"] === "dimanche"){
                $this->pc->personnalProg("2024-07-07");
            }else{
                $this->pc->personnalProg($get["jour"]);
            }
        }
        else if(isset($get["route"]) && $get["route"] === "dys"){
            $this->pc->dys();
        }else if(isset($get["route"]) && $get["route"] === "contact"){
            $this->pc->contact();
        }else if(isset($get["route"]) && $get["route"] === "infos_pratiques"){
            $this->pc->information();
        }else if(isset($get["route"]) && $get["route"] === "rgpd"){
            $this->pc->rgpd();
        }else if(isset($get["route"]) && $get["route"] === "mentions_legales"){
            $this->pc->mentionsLegales();
        }
        else{
            $this->pc->error();
        }
    }
}