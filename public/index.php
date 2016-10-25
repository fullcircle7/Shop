<?php

// include the composer autoloader
//require_once __DIR__ . '/../vendor/autoload.php';

//the above doesn't work for some reason... sooo.....requiring directly for now...

require_once __DIR__ . '/../router/Router.php';
require_once __DIR__ . '/../controllers/BuyController.php';
require_once __DIR__ . '/../controllers/SellController.php';
require_once __DIR__ . '/../controllers/ProfitController.php';

use Router\Router;
use Controllers\BuyController;
use Controllers\SellController;
use Controllers\ProfitController;

$router = new Router();

$router->setUri($_SERVER['REQUEST_URI']);

$router->addController('BuyController', new BuyController);
$router->addController('SellController', new SellController);
$router->addController('ProfitController', new ProfitController);

$router->setRoute('/shop/buy', 'BuyController');
$router->setRoute('/shop/sell', 'SellController');
$router->setRoute('/shop/profit', 'ProfitController');

$router->run();