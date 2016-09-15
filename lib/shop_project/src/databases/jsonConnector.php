<?php

//implements not extends DbAbstract? Need to test this... As with implements I can make sure the definition of each DB connector is virtually the same.

class jsonConnector extends DbAbstract implements DbInterface
{
    //JSON specific variables
    private $json;
    private $jsonData;
    //JSON specific variables

    public function __construct()
    {
        $this->json = file_get_contents(dirname(dirname(__FILE__)) . '/databases/inventory.json'); //read in json file
        $this->jsonData = json_decode($this->json, true); //create assoc array from data
    }


    function itemExists($itemName)
    {
        foreach ($this->jsonData['items'] as $key => $value) {

            if ($value['itemName'] === $itemName) { //check to see if item already exists in inventory.
                $this->result = true;
                break;
            } else {
                $this->result = false;
            }
        }

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
        file_put_contents(dirname(dirname(__FILE__)) . '/databases/inventory.json', $encodedJson); //replace the JSON file with the new content
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