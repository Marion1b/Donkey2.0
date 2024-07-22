<?php

class Ticket{
    private ? int $id = null;

    public function __construct(
        private string $content,
        private string $tarif,
        private string $code,
        private string $email
    )
    {
        
    }

    public function getId():? int{
        return $this->id;
    }

    public function setId(int $id):void{
        $this->id=$id;
    }

    public function getContent():string{
        return $this->content;
    }

    public function setContent(string $content):void{
        $this->content=$content;
    }

    public function getTarif():string{
        return $this->tarif;
    }

    public function setTarif(string $tarif):void{
        $this->tarif=$tarif;
    }

    public function getCode():string{
        return $this->code;
    }

    public function setCode(string $code):void{
        $this->code=$code;
    }

    public function getEmail():string{
        return $this->email;
    }

    public function setEmail(string $email):void{
        $this->email = $email;
    }

}