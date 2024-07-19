<?php
use Dompdf\Dompdf;

class PayController extends AbstractController{
    // private array $post = [];
    private TicketManager $tm;

    public function __construct()
    {
        $this->tm = new TicketManager();
    }

    // public function getPost():array{
    //     return $this->post;
    // }

    // public function setPost(array $post):void{
    //     $this->post = $post;
    // }

    public function checkPay():void{
        $csrft = new CSRFTokenManager();
        if (!empty($_POST['csrf-token']) && $csrft->validateCSRFToken($_SESSION["csrf_token"])){
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
        }else{
            $this->render("index.php?route=error", []);
        }
    }

    public function generateTicket(array $posts):void{

        $keys = ['friday', 'saturday', 'sunday', 'friday-saturday', 'friday-sunday', 'saturday-sunday', 'all-days', 'friday-reduce', 'saturday-reduce', 'sunday-reduce', 'friday-saturday-reduce', 'friday-sunday-reduce', 'saturday-sunday-reduce', 'all-days-reduce'];
        $doc = new Dompdf();
        $barcode = new Picqer\Barcode\BarcodeGeneratorHTML();
        foreach($posts as $key=>$post){
            if(in_array($key, $keys) && (int) $post > 0){
                for($i = 0; $i< (int) $post; $i++){
                    $code = $barcode->getBarcode(random_int(100000000000,999999999999), $barcode::TYPE_CODE_128);
                    $content = "<img src='./assets/img/other/Logo.png'><p>" . $posts["last_name"] . " " . $posts["first_name"] . "</p><p>" . $key . "</p><p>" . $posts["email"] . "</p>" . $code;
                    $doc->loadHtml($content);
                    $doc->render();
                    $pdf = base64_encode($doc->output());
                    $ticket = new Ticket($posts["last_name"] . " " . $posts["first_name"],$key, $pdf, $posts["email"]);
                    $this->tm->create($ticket);
                    if(isset($_SESSION["user"])){
                        $this->tm->createTicketUser();
                    }
                }
                
            }
        }
    }

    public function showTicket(array $posts):array{
        $tickets = $this->tm->findByEmail($posts["email"]);
        return $tickets;
    }



}