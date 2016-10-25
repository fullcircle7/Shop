<?php

//require_once dirname(__FILE__) . '/../controllers/BuyController.php';


echo 'Hello! You are in the /var/www/shop/public directory.' . '<br><br>';
echo 'The original request URL was... ' . '<b>' . $_SERVER['REQUEST_URI'] . '</b>';

$requestURL = $_SERVER['REQUEST_URI'];


if ($requestURL === '/shop/buy') {
    //buy stuff
    $shop = new ShopController($requestURL);
    $shop->execute();
} else if ($requestURL === '/shop/sell') {
    //sell stuff
    $shop = new ShopController($requestURL);
    $shop->execute();
} else if ($requestURL === '/shop/profit') {
    //profit stuff
    $shop = new ShopController($requestURL);
    $shop->execute();
} else {
    //feedback to user incorrect parameter
    echo "<br><br> <b> You have not entered a correct parameter, please try again.</b>";
}