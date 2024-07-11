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
        }
        else{
            $this->pc->error();
        }
    }
}