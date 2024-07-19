<?php

class TicketManager extends AbstractManager{
    public function __construct()
    {
        parent::__construct();
    }

    public function create (Ticket $ticket):void{
        $query= $this->db->prepare(
            "INSERT INTO
                tickets(
                    content,
                    tarif,
                    pdf,
                    email
                )VALUES(
                    :content,
                    :tarif,
                    :pdf,
                    :email
                )
            "
        );
        $parameters = [
            "content" => $ticket->getContent(),
            "tarif" => $ticket->getTarif(),
            "pdf" => $ticket->getPdf(),
            "email" => $ticket->getEmail()
        ];
        $query->execute($parameters);
    }

    public function createTicketUser():void{
        $last_id = $this->db->lastInsertId();
        $query = $this->db->prepare(
            "INSERT INTO
                users_tickets(
                    user_id,
                    ticket_id
                )VALUES(
                    :user_id,
                    :ticket_id
                )"
        );
        $parameters = [
            "user_id" => $_SESSION["user"]->getId(),
            "ticket_id" => $last_id
        ];
        $query->execute($parameters);
    }

    public function findByEmail(string $email):? array{
        $query = $this->db->prepare(
            "SELECT *
            FROM tickets
            WHERE email = :email"
        );
        $parameters = [
            "email" => $email
        ];
        $query->execute($parameters);
        if($query->rowCount()>=1){
            $tickets = $query->fetchAll(PDO::FETCH_ASSOC);
            $ticketsClass = [];
            foreach($tickets as $ticket){
                $ticketObj = new Ticket($ticket["content"], $ticket["tarif"], $ticket["pdf"], $ticket["email"]);
                $ticketObj->setId($ticket["id"]);
                $ticketsClass[] = $ticketObj;
            }
            return $ticketsClass;
        }else{
            return null;
        }
    }

    public function findByUserId(int $userId):? array{
        $query=$this->db->prepare(
            "SELECT 
                tickets.id,
                tickets.content,
                tickets.tarif,
                tickets.pdf,
                tickets.email
            FROM tickets
            JOIN users_tickets ON tickets.id = users_tickets.ticket_id
            JOIN users ON users_tickets.user_id = users.id
            WHERE users.id = :userId"
        );
        $parameters=[
            "userId" => $userId
        ];
        $query->execute($parameters);

        if($query->rowCount() >=1){
            $tickets = $query->fetchAll(PDO::FETCH_ASSOC);
            $ticketsClass = [];
            foreach($tickets as $ticket){
                $ticketObj = new Ticket($ticket["content"], $ticket["tarif"], $ticket["pdf"], $ticket["email"]);
                $ticketObj->setId($ticket["id"]);
                $ticketsClass[] = $ticketObj;
            }
            return $ticketsClass;
        }else{
            return null;
        }

    }
}