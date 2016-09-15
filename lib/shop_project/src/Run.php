<?php
require 'autoloader.inc.php';

//...Set DB type here...//

const DB_CON = 'jsonConnector';
//const DB_CON = 'sqlConnector';
//const DB_CON = 'otherDatabase';

//...Set DB type here...//

$dbType = DB_CON;
$db = new $dbType;

$shop = new Shop($db);
$shop->handle($argv[1]);