<?php

//implements not extends DbAbstract? Need to test this... As with implements I can make sure the definition of each DB connector is virtually the same.

class jsonConnector extends DbAbstract
{
    //JSON specific variables
    private $json;
    private $jsonData;
    //JSON specific variables

    public function __construct($supplierName, $itemName, $itemPrice, $itemCount)
    {
        $this->supplierName = $supplierName;
        $this->itemName = $itemName;
        $this->itemPrice = $itemPrice;
        $this->itemCount = $itemCount;

        $this->json = file_get_contents(dirname(dirname(__FILE__)) . '\databases\inventory.json'); //read in json file
        $this->jsonData = json_decode($this->json, true); //create assoc array from data
    }


    function itemExists()
    {
        foreach ($this->jsonData['items'] as $key => $value) {

            if ($value['itemName'] === $this->itemName) { //check to see if item already exists in inventory.
                $this->result = true;
                break;
            } else {
                $this->result = false;
            }
        }

        return $this->result;
    }

    function addItem()
    {
        //add item name, price, count and supplier to a new element of the array

        $count = count($this->jsonData['items']); //this doesn't take into account starting from 0, therefore we don't have to add 1 we can just use this value immediately
        $this->jsonData['items'][$count] = array('itemName' => $this->itemName,
                                                 'itemPrice' => $this->itemPrice,
                                                 'itemCount' => $this->itemCount,
                                                 'supplierName' => $this->supplierName
                                                );
    }

    function addRollingCash()
    {
        //Calculate the price received from sale, itemPrice * itemCount and add this to the 'rollingCash' figure.

        $sale = $this->itemPrice * $this->itemCount;
        $this->jsonData['cash'][0]['rollingCash'] += $sale;
    }

    function subtractRollingCash()
    {
        //Calculate the price spent, itemPrice * itemCount and take this away from the 'rollingCash' figure in the DB (this is allowed to go into negative as we are a business)

        $purchase = $this->itemPrice * $this->itemCount;
        $this->jsonData['cash'][0]['rollingCash'] -= $purchase;

    }

    function updateDB()
    {
        $encodedJson = json_encode($this->jsonData, JSON_PRETTY_PRINT); //put array back into JSON format
        file_put_contents(dirname(dirname(__FILE__)) . '\databases\inventory.json', $encodedJson); //replace the JSON file with the new content
    }

    function checkStockLevels()
    {
        foreach ($this->jsonData['items'] as $key => $value) {

            if ($value['itemName'] === $this->itemName) { //find item
                if ($value['itemCount'] >= $this->itemCount) { //check we have enough stock
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

    function reduceStockLevels()
    {
        foreach ($this->jsonData['items'] as $key => $value) {
            if ($value['itemName'] === $this->itemName) { //find item
                $this->jsonData['items'][$key]['itemCount'] -= $this->itemCount; //reduce stock level
            }
        }
    }

    function increaseStockLevels()
    {
        foreach ($this->jsonData['items'] as $key => $value) {
            if ($value['itemName'] === $this->itemName) { //find item
                $this->jsonData['items'][$key]['itemCount'] += $this->itemCount; //increase stock level
            }
        }
    }

    function updatePricePaid()
    {
        foreach ($this->jsonData['items'] as $key => $value) {
            if ($value['itemName'] === $this->itemName) { //find item
                $this->jsonData['items'][$key]['itemPrice'] = $this->itemPrice; //update price paid
            }
        }
    }

    function updateProfit()
    {
        //1. Calculate the difference between the itemPrice already stored in the array, and the itemPrice supplied
        //2. Multiply the result by the itemCount and add to the 'profit' figure ('profit' can also be a negative so have to bear that in mind)

        $pricePaid = 0;
        $priceReceived = $this->itemPrice;

        foreach ($this->jsonData['items'] as $key => $value) {
            if ($value['itemName'] === $this->itemName) { //find item
                $pricePaid = $value['itemPrice']; //store price we paid for the item
            }
        }

        $profit = ($priceReceived - $pricePaid) * $this->itemCount; //calculate profit
        $this->jsonData['cash'][0]['profit'] += $profit; //add profit to the profit value
    }

    function getProfit()
    {
        $currentProfit = $this->jsonData['cash'][0]['profit']; //get current profit
        return $currentProfit;
    }
}