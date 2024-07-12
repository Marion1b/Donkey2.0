<?php

class ArtistManager extends AbstractManager{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll():array{
        $query = $this->db->prepare(
            "SELECT *
            FROM artists"
        );
        $query->execute();

        $artists = $query->fetchAll(PDO::FETCH_ASSOC);
        $artistsClass = [];
        foreach($artists as $artist){
            $dateString = $artist["date"];
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
            $artistObj = new Artist($artist["name"], $artist["picture"], $date, $artist["biography"], $artist["playlist"]);
            $artistObj->setId($artist["id"]);
            $artistsClass[] = $artistObj;
        }
        return $artistsClass;
    }

    public function findByDay(string $date):array{
        $query = $this->db->prepare(
            "SELECT *
            FROM artists
            WHERE date LIKE :date"
        );
        $parameters = [
            "date" => $date . "%"
        ];
        $query->execute($parameters);
        $artists = $query->fetchAll(PDO::FETCH_ASSOC);
        $artistsClass = [];
        foreach($artists as $artist){
            $dateString = $artist["date"];
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
            $artistObj = new Artist($artist["name"], $artist["picture"], $date, $artist["biography"], $artist["playlist"]);
            $artistObj->setId($artist["id"]);
            $artistsClass[] = $artistObj;
        }
        return $artistsClass;
    }

    public function findByName(string $name):? Artist{
        $query = $this->db->prepare(
            "SELECT *
            FROM artists
            WHERE name = :name"
        );
        $parameters=[
            "name"=>$name
        ];
        $query->execute($parameters);
        if($query->rowCount()===1){
            $artist = $query->fetch(PDO::FETCH_ASSOC);
            $dateString = $artist["date"];
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
            $artistClass = new Artist($artist["name"], $artist["picture"], $date, $artist["biography"], $artist["playlist"]);
            $artistClass->setId($artist["id"]);
            return $artistClass;
        }else{
            return null;
        }
        
    }

    public function showFavoriteArtists():? array{
        $query=$this->db->prepare(
            "SELECT 
                artists.id,
                artists.name,
                artists.picture,
                artists.date,
                artists.biography,
                artists.playlist
            FROM artists
            JOIN artists_users ON artists.id = artists_users.artist_id
            WHERE artists_users.user_id = :user_id
            ");
        $parameters = [
            'user_id' => $_SESSION["user"]->getId()
        ];
        $query->execute($parameters);
        if($query->rowCount()>=1){
            $artists = $query->fetchAll(PDO::FETCH_ASSOC);
            $artistsClass = [];
            foreach($artists as $artist){
                $dateString = $artist["date"];
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
                $artistObj = new Artist($artist["name"], $artist["picture"], $date, $artist["biography"], $artist["playlist"]);
                $artistObj->setId($artist["id"]);
                $artistsClass[]=$artistObj;
            }
            return $artistsClass;
        }else{
            return null;
        }
        
    }

    public function getFavArtistByDay(string $day):? array{
        $query = $this->db->prepare(
            "SELECT 
                artists.id,
                artists.name,
                artists.picture,
                artists.date,
                artists.biography,
                artists.playlist
            FROM artists
            JOIN artists_users ON artists.id = artists_users.artist_id
            WHERE date LIKE :date AND artists_users.user_id = :user_id
            ORDER BY artists.date ASC"
        );
        $parameters = [
            "date" => $day . "%",
            'user_id' => $_SESSION["user"]->getId()
        ];
        $query->execute($parameters);
        $artists = $query->fetchAll(PDO::FETCH_ASSOC);
        $artistsClass = [];
        if($query->rowCount()>=1){
            foreach($artists as $artist){
                $dateString = $artist["date"];
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
                $artistObj = new Artist($artist["name"], $artist["picture"], $date, $artist["biography"], $artist["playlist"]);
                $artistObj->setId($artist["id"]);
                $artistsClass[] = $artistObj;
            }
            return $artistsClass;
        }else{
            return null;
        }
        
    }
}