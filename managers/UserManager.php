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

    public function addFavorite():void{
        $query = $this->db->prepare(
            "INSERT INTO
                artists_users(
                    artist_id,
                    user_id
                )VALUES(
                    :artist_id,
                    :user_id
                )
        ");
        $artist = "";
        if(isset($_POST["artist"])){
            $artist = $_POST["artist"];
        }
        $parameters = [
            'artist_id' => $artist,
            'user_id' => $_SESSION["user"]->getId()
        ];
        $query->execute($parameters);
    }

    public function removeFavorite():void{
        $query = $this->db->prepare(
            "DELETE FROM artists_users
            WHERE artist_id = :artist_id AND user_id = :user_id
        ");
        $parameters = [
            'artist_id' => $_POST["artist"],
            'user_id' => $_SESSION["user"]->getId()
        ];
        $query->execute($parameters);
    }

    public function isFavorite():bool{
        $query = $this->db->prepare(
            "SELECT 
                artist_id
            FROM artists_users
            WHERE artist_id = :artist_id AND user_id = :user_id"
        );
        $artist = "";
        if(isset($_POST["artist"])){
            $artist = $_POST["artist"];
        }
        $parameters = [
            'artist_id' => $artist,
            'user_id' => $_SESSION["user"]->getId()
        ];
        $query->execute($parameters);
        if($query->rowCount()>=1){
            return true;
        }else{
            return false;
        }
    }

    public function getFavoriteArtists():array{
        $query = $this->db->prepare(
            "SELECT artist_id
            FROM artists_users
            WHERE user_id = :user_id"
        );
        $parameters = [
            'user_id' => $_SESSION["user"]->getId()
        ];
        $query->execute($parameters);
        $artistsId = $query->fetchAll(PDO::FETCH_ASSOC);
        return $artistsId;
    }
}