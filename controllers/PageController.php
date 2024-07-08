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
        $this->render("espace-perso.html.twig", []);
    }

    public function error():void{
        $this->render("error404.html.twig", []);
    }

}