<?php

namespace Databases;

use Databases\DbInterfaces\DbInterface;
use PDO;

class sqlConnector implements DbInterface
{
    /*
    * Write Exception handling using try/catch and PDOExceptions for the whole class...
    * ...This would require modification to my 'Transaction' class logic to deal with all the extra returns.
    */

    protected $result;
    protected $db;

    public function __construct()
    {
        if (!file_exists(dirname(__FILE__) . '/mydb.sq3')) { //does DB file NOT already exist?

            //it doesn't, create it.
            $this->db = new PDO('sqlite:' . dirname(__FILE__) . '/mydb.sq3');

            //create the tables
            $this->db->exec("CREATE TABLE items (Id INTEGER PRIMARY KEY, itemName TEXT, itemPrice FLOAT, itemCount INTEGER, supplierName TEXT)");
            $this->db->exec("CREATE TABLE cash (Id INTEGER PRIMARY KEY, rollingCash float, profit float)");

            //Insert starting data
            $this->db->exec("INSERT INTO items (itemName, itemPrice, itemCount, supplierName) VALUES ('Baked Beans', 0.5, 1, 'Sainsburys');" .
                            "INSERT INTO items (itemName, itemPrice, itemCount, supplierName) VALUES ('Washing Up Liquid', 0.72, 1, 'Sainsburys'); " .
                            "INSERT INTO items (itemName, itemPrice, itemCount, supplierName) VALUES ('Rubber Gloves', 1.50, 1, 'Sainsburys'); " .
                            "INSERT INTO items (itemName, itemPrice, itemCount, supplierName) VALUES ('Bread', 0.72, 1, 'Sainsburys'); " .
                            "INSERT INTO items (itemName, itemPrice, itemCount, supplierName) VALUES ('Butter', 0.83, 4, 'Sainsburys');");

            $this->db->exec("INSERT INTO cash (rollingCash, profit) VALUES (50000, 500);");

        } else { //DB file already exists.
            $this->db = new PDO('sqlite:' . dirname(__FILE__) . '/mydb.sq3'); //Open the DB file
        }
    }

    function itemExists($itemName)
    {
        //write query with wildcard
        $sql = "SELECT `itemName` FROM `items` WHERE `itemName` = :itemName";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':itemName', $itemName, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();

        //check the result
        if($preparedStatement->fetch()) { //if item has been found (fetch returns false if no results and an array if there are results)
            $this->result = true;
        } else { //false was returned, the item does not exist
            $this->result = false;
        }
        return $this->result;
    }

    function addItem($supplierName, $itemName, $itemPrice, $itemCount)
    {
        //write query with wildcard
        $sql = "INSERT INTO `items` (itemName, itemPrice, itemCount, supplierName) VALUES (:itemName, :itemPrice, :itemCount, :supplierName)";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':itemName', $itemName, PDO::PARAM_STR);
        $preparedStatement->bindParam(':itemPrice', $itemPrice, PDO::PARAM_STR);
        $preparedStatement->bindParam(':itemCount', $itemCount, PDO::PARAM_INT);
        $preparedStatement->bindParam(':supplierName', $supplierName, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();
    }

    function addRollingCash($itemPrice, $itemCount)
    {
        $getRollingCash = $this->db->query("SELECT `rollingCash` FROM `cash` WHERE `id` = 1");
        $getRollingCash = $getRollingCash->fetch();

        //Calculate the price received from sale, itemPrice * itemCount and add this to the 'rollingCash' figure.
        $sale = $itemPrice * $itemCount;
        $newRollingCash = $getRollingCash['rollingCash'] += $sale;

        //write insert query with wildcard
        $sql = "UPDATE `cash` SET `rollingCash` = :newRollingCash WHERE `id` = 1";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':newRollingCash', $newRollingCash, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();
    }

    function subtractRollingCash($itemPrice, $itemCount)
    {
        $getRollingCash = $this->db->query("SELECT `rollingCash` FROM `cash` WHERE `id` = 1");
        $getRollingCash = $getRollingCash->fetch();

        //Calculate the price received from sale, itemPrice * itemCount and add this to the 'rollingCash' figure.
        $sale = $itemPrice * $itemCount;
        $newRollingCash = $getRollingCash['rollingCash'] -= $sale;

        //write update query with wildcard
        $sql = "UPDATE `cash` SET `rollingCash` = :newRollingCash WHERE `id` = 1";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':newRollingCash', $newRollingCash, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();
    }

    function updateDB() //NOT NEEDED FOR SQL as persistence updates are made piecemeal, not all at once like with JSON
    {

    }

    function checkStockLevels($itemName, $itemCount)
    {
        //write query with wildcard
        $sql = "SELECT `itemCount` FROM `items` WHERE `itemName` = :itemName";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':itemName', $itemName, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();

        //get results
        $result = $preparedStatement->fetch();


        if ($result['itemCount'] >= $itemCount) { //check we have enough stock
            $this->result = true;
        } else { //we don't have enough of this item
            $this->result = false;
        }

        return $this->result;
    }

    function reduceStockLevels($itemName, $itemCount)
    {
        //write query with wildcard
        $sql = "SELECT `itemCount` FROM `items` WHERE `itemName` = :itemName";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':itemName', $itemName, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();

        //get results
        $result = $preparedStatement->fetch();

        //calculate new stock level
        $newStockLevel = $result['itemCount'] -= $itemCount;

        //write query with wildcard
        $sql = "UPDATE `items` SET `itemCount` = :newStockLevel WHERE `itemName` = :itemName";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':newStockLevel', $newStockLevel, PDO::PARAM_STR);
        $preparedStatement->bindParam(':itemName', $itemName, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();
    }

    function increaseStockLevels($itemName, $itemCount)
    {
        //write query with wildcard
        $sql = "SELECT `itemCount` FROM `items` WHERE `itemName` = :itemName";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':itemName', $itemName, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();

        //get results
        $result = $preparedStatement->fetch();

        //calculate new stock level
        $newStockLevel = $result['itemCount'] += $itemCount;

        //write query with wildcard
        $sql = "UPDATE `items` SET `itemCount` = :newStockLevel WHERE `itemName` = :itemName";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':newStockLevel', $newStockLevel, PDO::PARAM_STR);
        $preparedStatement->bindParam(':itemName', $itemName, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();
    }

    function updatePricePaid($itemName, $itemPrice)
    {
        //write query with wildcard
        $sql = "UPDATE `items` SET `itemPrice` = :itemPrice WHERE `itemName` = :itemName";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':itemPrice', $itemPrice, PDO::PARAM_STR);
        $preparedStatement->bindParam(':itemName', $itemName, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();
    }

    function updateProfit($itemName, $itemPrice, $itemCount)
    {
        //1. Calculate the difference between the itemPrice already stored in the array, and the itemPrice supplied
        //2. Multiply the result by the itemCount and add to the 'profit' figure ('profit' can also be a negative so have to bear that in mind)


        //write query with wildcard
        $sql = "SELECT `itemPrice` FROM `items` WHERE `itemName` = :itemName";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':itemName', $itemName, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();

        //get results
        $result = $preparedStatement->fetch();
        $pricePaid = $result['itemPrice'];

        //calculate profit received from transaction (can be negative if we take a loss)
        $priceReceived = $itemPrice; //this is purely to make the below line easier to read
        $profit = ($priceReceived - $pricePaid) * $itemCount; //calculate profit

        //get profit in one query
        $sql = "SELECT `profit` FROM `cash` WHERE `id` = 1";
        $result = $this->db->query($sql);
        $currentProfit = $result->fetch();

        //calculate new profit value
        $newProfit = $currentProfit['profit'] += $profit;

        //write query with wildcard
        $sql = "UPDATE `cash` SET `profit` = :newProfit WHERE `id` = 1";

        //prepare the statement
        $preparedStatement = $this->db->prepare($sql);

        //bind the values
        $preparedStatement->bindParam(':newProfit', $newProfit, PDO::PARAM_STR);

        //run the query
        $preparedStatement->execute();
    }

    function getProfit()
    {
        //write query (no preparing required as no user input taken)
        $sql = "SELECT `profit` FROM `cash` WHERE `id` = 1";

        //run the query
        $result = $this->db->query($sql);

        //get the result
        $result = $result->fetch();

        //return the current profit
        return $result['profit'];
    }
}