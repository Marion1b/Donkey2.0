<?php
require_once '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/..");
$dotenv->load();
\Stripe\Stripe::setApiKey($_ENV["API_KEY"]);
header('Content-Type: application/json');

$YOUR_DOMAIN = 'http:http://donkey/index.php';
// $product = \Stripe\Product::create([
//   'name' => 'Total Tickets',
//   'id' => 'total_tickets'
// ]);

$price = \Stripe\Price::create([
  'unit_amount' => (int) $_POST["ticket-total-input"] * 100,
  'currency' => 'eur',
  'product' => 'total_tickets'
]);

$checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => [[
        # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
        'price' => $price->id,
        'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '?route=paiement-valide',
  'cancel_url' => $YOUR_DOMAIN . '?route=paiement-invalide',
  'customer_email' => $_POST["email"]
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);