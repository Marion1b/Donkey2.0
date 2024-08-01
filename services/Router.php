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
            if(isset($get["error_connexion"]) && $get["error_connexion"]=="true"){
                $this->pc->connexion(true);
            }else{
                $this->pc->connexion(false);
            }
        }else if(isset($get["route"]) && $get["route"]==="checkLogin"){
            if(!empty($_POST)){
                $isLoginCorrect = $this->ac->checkLogin($_POST);
                if($isLoginCorrect !== null){
                    $_SESSION["user"]=$isLoginCorrect;
                }
            }else{
                $this->pc->connexion(false);
            }
        }else if(isset($get["route"]) && $get["route"] === "inscription"){
            if(isset($get["isError"]) && $get["isError"] === "1"){
                $this->pc->inscription(1);
            }else if(isset($get["isError"]) && $get["isError"] === "2"){
                $this->pc->inscription(2);
            }else if(isset($get["isError"]) && $get["isError"]==="3"){
                $this->pc->inscription(3);
            }else{
                $this->pc->inscription(0);
            }
        }
        else if(isset($get["route"]) && $get["route"]==="checkSignUp"){
            if(!empty($_POST)){
                $isSignUpCorrect = $this->ac->checkSignUp($_POST);
                if($isSignUpCorrect !== null){
                    $_SESSION["user"]=$isSignUpCorrect;
                }
            }else{
                $this->pc->inscription(0);
            }
        }else if(isset($get["route"]) && $get["route"] === "logout"){
            $this->pc->logout();
        }else if(isset($get["route"]) && $get["route"] === "espace-perso"){
            $this->pc->personnalSpace();
        }else if(isset($get["route"]) && $get["route"] === "billets"){
            $this->pc->getTicket();
        }else if(isset($get["route"]) && $get["route"] === "download"){
            if(isset($get["file"]) && isset($get["user"]) && isset($_SESSION["user"]) && (int) $get["user"] === $_SESSION["user"]->getId() || isset($get["file"]) && (int) $get["user"] === $_SESSION["id"]){
                $this->pc->download();
            }else{
                $this->pc->error();
            }
        }else if(isset($get["route"]) && $get["route"] === "espace-admin"){
            if(isset($_SESSION["user"]) && !empty($_SESSION["user"])){
                if((int) $get["admin-id"] === $_SESSION["user"]->getId() && $_SESSION["user"]->getAdmin() === "ADMIN"){
                    $this->pc->adminSpace();
                }else{
                    $this->pc->error();
                }
            }else{
                $this->pc->error();
            }
        }else if(isset($get["route"]) && $get["route"]==="check-modif"){
            if(!empty($_POST)){
                $this->pc->checkModifUser();
            }else{
                $this->pc->error();
            }
        }else if(isset($get["route"]) && $get["route"]==="checkDelete"){
            if(!empty($_POST)){
                $this->pc->checkDeleteUser();
            }else{
                $this->pc->error();
            }
        }else if(isset($get["route"]) && $get["route"]==="checkDeleteTicket"){
            if(!empty($POST)){
                $this->pc->checkDeleteTicket();
            }else{
                $this->pc->error();
            }
        }else if(isset($get["route"]) && $get["route"] === "billetterie"){
            $this->pc->ticketing();
        }else if(isset($get["route"]) && $get["route"] === "achat-billets"){
            $this->pc->buyTickets();
        }else if(isset($get["route"]) && $get["route"] === "paiement"){
            if(!empty($_POST)){
                $_SESSION["post_data"]=$_POST;
                $this->pay->checkPay();
            }else{
                $this->pc->ticketing();
            }
        }else if(isset($get["route"]) && $get["route"]=== "paiement-valide"){
            if(isset($_SESSION["post_data"]) && !empty($_SESSION["post_data"])){
                $randomId = (int) $get["id"];
                if(isset($get["id"]) && isset($_SESSION['randomId']) && $randomId === $_SESSION['randomId']){
                    $post = $_SESSION['post_data'];
                    $this->pay->generateTicket($post);
                    $this->pc->paymentSuccess();
                    $_SESSION['post_data']="";
                    $_SESSION['randomId']="";
                }
            }else{
                $this->pc->paymentCancel();
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
            if(isset($get["artiste"]) && !empty($get["artiste"])){
                $this->pc->artist();
            }else{
                $this->pc->error();
            }
        }else if(isset($get["route"]) && $get["route"] === "liste-artiste"){
            if(isset($_SESSION["user"]) && !empty($_SESSION["user"])){
                $this->pc->artistList();
            }else{
                $this->pc->error();
            }
        }else if(isset($get["route"]) && $get["route"] === "liste-artiste-fav"){
            if(isset($_SESSION["user"]) && !empty($_SESSION["user"])){
                $this->pc->personnalArtistList();
            }else{
                $this->pc->error();
            }
        }else if(isset($get["route"]) && $get["route"] === "programmation-perso"){
            if(isset($_SESSION["user"]) && !empty($_SESSION["user"])){
                if(isset($get["jour"]) && $get["jour"] === "vendredi"){
                    $this->pc->personnalProg("2024-07-05");
                }else if(isset($get["jour"]) && $get["jour"] === "samedi"){
                    $this->pc->personnalProg("2024-07-06");
                }else if(isset($get["jour"]) && $get["jour"] === "dimanche"){
                    $this->pc->personnalProg("2024-07-07");
                }else if(isset($get["jour"]) && !empty($get["jour"])){
                    $this->pc->personnalProg($get["jour"]);
                }else{
                    $this->pc->error();
                }
            }else{
                $this->pc->error();
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