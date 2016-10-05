<?php

class sqlConnector implements DbInterface
{
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

    /* SAMPLE CODE
     * $result = $db->query('SELECT * FROM Dogs');
     * foreach ($result as $row) {
     * fwrite(STDOUT, $row['Breed'] . PHP_EOL);
     * }
    */



    function itemExists($itemName)
    {
        //I need to put some thought in whether any of the code can be done once rather than having it all inside every function over and over again.
        // Lastly, I could have an 'query' array that stores all my queries in one place. The keys should match the names of the methods inside this class, and the values would obviously be the queries.
        /*
         * Then, in each method I can prepare the relevant query to begin with.
         *
         * So it would go something like this...
         * ... $this->queries = ('itemExists' => ?);         *
         *
         * $itemExistsQuery = $this->db->prepare($this->queries['itemExists']);
         *
         * then when i'm ready...
         * ... $itemExistsQuery->bindParam(1, $itemName, PDO::PARAM_STR);
         *
         * then...
         * ...$result = $itemExistsQuery->execute();
         *
         * then...
         * ... if ($result = false) { //check if operation was successful
         *     $this->result = 'There was an error, cancelling operation';
         * } else {
         *     Something was returned, double check it equals the provided value by the user?
         * }
         *
         *
         * Instead of the error handling above I should probably make use of try/catch and PDOExceptions tbh...
         *
         */

//        //prepare the statement
//        if (!$preparedStatement = $this->db->prepare($sql)) {
//            fwrite(STDOUT, 'I failed');
//        } else {
//            fwrite(STDOUT, 'I worked.');
//        }
//        exit();





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
            fwrite(STDOUT, 'the item exists.' . PHP_EOL);
            //$this->result = true;
        } else { //false was returned, the item does not exist
            fwrite(STDOUT, 'the item does not exist.' . PHP_EOL);
            //$this->result = false;
        }
        exit();
        return $this->result;
    }

    function addItem($supplierName, $itemName, $itemPrice, $itemCount)
    {
        //add item name, price, count and supplier to a new element of the array

        $count = count($this->jsonData['items']); //this doesn't take into account starting from 0, therefore we don't have to add 1 we can just use this value immediately
        $this->jsonData['items'][$count] = array('itemName' => $itemName,
            'itemPrice' => $itemPrice,
            'itemCount' => $itemCount,
            'supplierName' => $supplierName
        );
    }

    function addRollingCash($itemPrice, $itemCount)
    {
        //Calculate the price received from sale, itemPrice * itemCount and add this to the 'rollingCash' figure.

        $sale = $itemPrice * $itemCount;
        $this->jsonData['cash'][0]['rollingCash'] += $sale;
    }

    function subtractRollingCash($itemPrice, $itemCount)
    {
        //Calculate the price spent, itemPrice * itemCount and take this away from the 'rollingCash' figure in the DB (this is allowed to go into negative as we are a business)

        $purchase = $itemPrice * $itemCount;
        $this->jsonData['cash'][0]['rollingCash'] -= $purchase;

    }

    function updateDB()
    {
        $encodedJson = json_encode($this->jsonData, JSON_PRETTY_PRINT); //put array back into JSON format
        file_put_contents(dirname(dirname(__FILE__)) . '/Databases/Inventory.json', $encodedJson); //replace the JSON file with the new content
    }

    function checkStockLevels($itemName, $itemCount)
    {
        foreach ($this->jsonData['items'] as $key => $value) {

            if ($value['itemName'] === $itemName) { //find item
                if ($value['itemCount'] >= $itemCount) { //check we have enough stock
                    $this->result = true;
                    break;
                } else { //we don't have enough of this item
                    $this->result = false;
                    break;
                }
            }
        }

        return $this->result;
    }

    function reduceStockLevels($itemName, $itemCount)
    {
        foreach ($this->jsonData['items'] as $key => $value) {
            if ($value['itemName'] === $itemName) { //find item
                $this->jsonData['items'][$key]['itemCount'] -= $itemCount; //reduce stock level
            }
        }
    }

    function increaseStockLevels($itemName, $itemCount)
    {
        foreach ($this->jsonData['items'] as $key => $value) {
            if ($value['itemName'] === $itemName) { //find item
                $this->jsonData['items'][$key]['itemCount'] += $itemCount; //increase stock level
            }
        }
    }

    function updatePricePaid($itemName, $itemPrice)
    {
        foreach ($this->jsonData['items'] as $key => $value) {
            if ($value['itemName'] === $itemName) { //find item
                $this->jsonData['items'][$key]['itemPrice'] = $itemPrice; //update price paid
            }
        }
    }

    function updateProfit($itemName, $itemPrice, $itemCount)
    {
        //1. Calculate the difference between the itemPrice already stored in the array, and the itemPrice supplied
        //2. Multiply the result by the itemCount and add to the 'profit' figure ('profit' can also be a negative so have to bear that in mind)

        $pricePaid = 0;
        $priceReceived = $itemPrice;

        foreach ($this->jsonData['items'] as $key => $value) {
            if ($value['itemName'] === $itemName) { //find item
                $pricePaid = $value['itemPrice']; //store price we paid for the item
            }
        }

        $profit = ($priceReceived - $pricePaid) * $itemCount; //calculate profit
        $this->jsonData['cash'][0]['profit'] += $profit; //add profit to the profit value
    }

    function getProfit()
    {
        $currentProfit = $this->jsonData['cash'][0]['profit']; //get current profit
        return $currentProfit;
    }
}