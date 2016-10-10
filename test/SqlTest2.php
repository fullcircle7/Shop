<?php
//$db;
//$result;

if (!file_exists(dirname(__FILE__) . '/mydb.sq3')) { //does DB file NOT already exist?

    fwrite(STDOUT, "it doesn't, create it." . PHP_EOL);


    $db = new PDO('sqlite:' . dirname(__FILE__) . '/mydb.sq3');

    //create the tables
    $db->exec("CREATE TABLE items (Id INTEGER PRIMARY KEY, itemName TEXT, itemPrice FLOAT, itemCount INTEGER, supplierName TEXT)");
    $db->exec("CREATE TABLE cash (Id INTEGER PRIMARY KEY, rollingCash float, profit float)");

    //Insert starting data
    $db->exec("INSERT INTO items (itemName, itemPrice, itemCount, supplierName) VALUES ('Baked Beans', 0.5, 1, 'Sainsburys');" .
              "INSERT INTO items (itemName, itemPrice, itemCount, supplierName) VALUES ('Washing Up Liquid', 0.72, 1, 'Sainsburys');" .
              "INSERT INTO items (itemName, itemPrice, itemCount, supplierName) VALUES ('Rubber Gloves', 1.50, 1, 'Sainsburys');" .
              "INSERT INTO items (itemName, itemPrice, itemCount, supplierName) VALUES ('Bread', 0.72, 1, 'Sainsburys');" .
              "INSERT INTO items (itemName, itemPrice, itemCount, supplierName) VALUES ('Butter', 0.83, 4, 'Sainsburys');");

    $db->exec("INSERT INTO cash (rollingCash, profit) VALUES (50000, 500);");




    //$result = $db->exec("SELECT itemName FROM items");
    //fwrite(STDOUT, var_dump($result));
    //exit();



} else { //DB file already exists.
    fwrite(STDOUT, "it exists, open it." . PHP_EOL);
    $db = new PDO('sqlite:mydb.sq3'); //Open the DB file
}

/*
$result = $db->query('SELECT * FROM items');

foreach ($result as $row) {
    fwrite(STDOUT, $row['itemName'] . PHP_EOL);
}
*/

/*
$result = $db->query("SELECT itemName FROM items WHERE itemName = 'Bread'");

foreach ($result as $row) {
    fwrite(STDOUT, $row['itemName'] . PHP_EOL);
}
exit();
*/


/////------------------------------


$sql = "SELECT `profit` FROM `cash` WHERE `id` = 1";
$result = $db->query($sql);
$result = $result->fetch();



fwrite(STDOUT, $result['profit'] . PHP_EOL);

exit();






$itemName = "Butter";
$sql = "SELECT `itemCount` FROM `items` WHERE `itemName` = :itemName";

//prepare the statement
$preparedStatement = $db->prepare($sql);

//bind the values
$preparedStatement->bindParam(':itemName', $itemName, PDO::PARAM_STR);

//run the query
$preparedStatement->execute();


$result = $preparedStatement->fetch();

fwrite(STDOUT, $result['itemCount']);

exit();

/*








$itemName = 'Baked Beans';

//write query with wildcard
$sql = "SELECT `itemName` FROM `items` WHERE `itemName` = :itemName";

//prepare the statement

if ($preparedStatement = $db->prepare($sql)) {
    fwrite(STDOUT, 'i worked!');
} else {
    fwrite(STDOUT, 'i did not work!');
}

//sanitise the data
//$itemName = $this->sanitise($itemName);

//bind the values
$preparedStatement->bin
$preparedStatement->bindParam(':itemName', $itemName, PDO::PARAM_STR);

//run the query

//$preparedStatement->execute(array(':itemName'=>$itemName));
$preparedStatement->execute();

//check the result


if ($preparedStatement->fetch()) { //if not false, in other words, the item was found.
    fwrite(STDOUT, 'the item exists.' . PHP_EOL);
} else {
    fwrite(STDOUT, 'the item does not exist.' . PHP_EOL);
}
exit();



fwrite(STDOUT, var_dump($preparedStatement->fetch()) . PHP_EOL);
exit();

if($preparedStatement->fetch() === false) { //fetchColumn returns false if there are no rows and a single column from the next row of a result set if true
    fwrite(STDOUT, 'the item does not exist' . PHP_EOL);
    //$result = false;
} else { //false was not returned, a result exists, we have the item
    fwrite(STDOUT, 'the item exists' . PHP_EOL);
    //$result = true;
}

//for other queries i may have to run $result = preparedStatement->execute(); instead and then loop through the result array to check values etc

*/
