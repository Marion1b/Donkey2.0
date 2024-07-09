<?php

class Router{
    private AuthController $ac;
    private PageController $pc;
    public function __construct()
    {
        $this->ac = new AuthController();
        $this->pc = new PageController();
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
        }else if(isset($get["route"]) && $get["route"] === "billetterie"){
            $this->pc->ticketing();
        }else if(isset($get["route"]) && $get["route"] === "achat-billets"){
            $this->pc->buyTickets();
        }
        else{
            $this->pc->error();
        }
    }
}