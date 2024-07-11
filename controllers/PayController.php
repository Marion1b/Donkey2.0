<?php

class PayController extends AbstractController{
    private array $post = [];

    public function __construct()
    {
        
    }

    public function getPost():array{
        return $this->post;
    }

    public function setPost(array $post):void{
        $this->post = $post;
    }

    public function checkPay():void{
        \Stripe\Stripe::setApiKey($_ENV["API_KEY"]);
        header('Content-Type: application/json');


        $YOUR_DOMAIN = 'http://donkey/index.php';
        // $product = \Stripe\Product::create([
        //   'name' => 'Total Tickets',
        //   'id' => 'total_tickets'
        // ]);

        $price = \Stripe\Price::create([
            'unit_amount' => (int) $_POST["ticket-total-input"] * 100,
            'currency' => 'eur',
            'product' => 'total_tickets'
        ]);

        $randomId = rand(10000, 99999);
        $_SESSION['randomId'] = $randomId;

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                'price' => $price->id,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '?route=paiement-valide&id=' . $randomId,
            'cancel_url' => $YOUR_DOMAIN . '?route=paiement-invalide',
            'customer_email' => $_POST["email"]
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
    }

    public function generateTicket(array $posts):void{
        $tm = new TicketManager();

        $keys = ['friday', 'saturday', 'sunday', 'friday-saturday', 'friday-sunday', 'saturday-sunday', 'all-days', 'friday-reduce', 'saturday-reduce', 'sunday-reduce', 'friday-saturday-reduce', 'friday-sunday-reduce', 'saturday-sunday-reduce', 'all-days-reduce'];
        
        foreach($posts as $key=>$post){
            if(in_array($key, $keys) && (int) $post > 0){
                for($i = 0; $i< (int) $post; $i++){
                    $ticket = new Ticket($posts["last_name"] . " " . $posts["first_name"],$key, "./assets/img/other/QR_Barcode.png", $posts["email"]);
                    $tm->create($ticket);
                    if(isset($_SESSION["user"])){
                        $tm->createTicketUser();
                    }
                }
                
            }
        }
    }

    // public function keepPost(array $post_infos):array{
    //     $posts = [];
    //     $keys = ['friday', 'saturday', 'sunday', 'friday-saturday', 'friday-sunday', 'saturday-sunday', 'all-days', 'friday-reduce', 'saturday-reduce', 'sunday-reduce', 'friday-saturday-reduce', 'friday-sunday-reduce', 'saturday-sunday-reduce', 'all-days-reduce'];
    //     foreach($post_infos as $key=>$post){
    //         if(in_array($key, $keys) && (int) $post > 0){
    //             $posts[$key]=$post;
    //         }
    //     }
    //     $posts["last_name"] = $post_infos["last_name"];
    //     $posts["first_name"] = $post_infos["first_name"];
    //     $posts["email"] = $post_infos["email"];

    //     return $posts;
    // }

    public function showTicket(array $posts):array{
        $tm = new TicketManager();
        $tickets = $tm->findByEmail($posts["email"]);
        return $tickets;
    }



}