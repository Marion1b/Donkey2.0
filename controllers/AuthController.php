<?php

class AuthController extends AbstractController{

    private UserManager $um;

    public function __construct()
    {
        parent::__construct();
        $this->um = new UserManager();
    }

    public function checkLogin($post):? User{
        $user = $this->um->findByEmail($post["email"]);
        $csrft = new CSRFTokenManager();
        if (!empty($_POST['csrf-token']) && $csrft->validateCSRFToken($_SESSION["csrf_token"])){
            if($user !== null){
            $password = $post["password"];
            $isPasswordCorrect = password_verify($password, $user->getPassword());
                if($isPasswordCorrect === true){
                    $this->redirect("index.php?route=espace-perso&&user-id=" . $user->getId());
                    // return to router.php
                    return $user;
                }else{
                    $this->redirect("index.php?route=connexion");
                    return null;
                }
            }else{
                $this->redirect("index.php?route=connexion&&error_connexion=true");
                return null;
            }
        }
        
    }

    public function checkSignUp($post):? User{
        $csrft = new CSRFTokenManager();
        if (!empty($_POST['csrf-token']) && $csrft->validateCSRFToken($_SESSION["csrf_token"])){
            $user = new User($post["last_name"], $post["first_name"], $post["email"], $post["password"]);
            $isUser = $this->um->findByEmail($post["email"]);
            $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
            $password = $user->getPassword();
            if($isUser === null){
                // check if the two passwords are the same
                if(preg_match($pattern,$password)){
                    if($post["password"] === $post["checkPassword"]){
                        $this->um->create($user);
                        $this->redirect("index.php?route=espace-perso&&user-id=" . $user->getId());
                        // return to router.php
                        return $user;
                    }else{
                        $this->redirect("index.php?route=inscription&&isError=2");
                        return null;
                    }
                }else{
                    $this->redirect("index.php?route=inscription&&isError=1");
                    return null;
                }
                
            }else{
                $this->redirect("index.php?route=inscription&&isError=3");
                return null;
            }
        }else{
            $this->render("index.php?route=error", []);
        }
    }
}