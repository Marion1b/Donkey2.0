<?php

class UserManager extends AbstractManager{
    public function __construct()
    {
        parent::__construct();
    }

    public function findByEmail(string $email):? User{
        $query = $this->db->prepare(
            "SELECT *
            FROM users
            WHERE email = :email"
        );
        $parameters = [
            "email"=>$email
        ];
        $query->execute($parameters);

        if($query->rowCount()===1){
            $user = $query->fetch(PDO::FETCH_ASSOC);
            $userClass = new User($user["last_name"], $user["first_name"], $user["email"], $user["password"]);
            $userClass->setId($user["id"]);
            return $userClass;
        }else{
            return null;
        }
    }

    public function create(User $user):void{
        $query = $this->db->prepare(
            "INSERT INTO
                users(
                    last_name,
                    first_name,
                    email,
                    password)
                VALUES(
                    :last_name,
                    :first_name,
                    :email,
                    :password
                )");
        $parameters = [
            'last_name' => $user->getLastName(),
            'first_name' => $user->getFirstName(),
            'email' =>$user->getEmail(),
            'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT)
        ];
        $query->execute($parameters);
    }
}