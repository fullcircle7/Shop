<?php

class Transaction
{
    private $db;
    public $result;
    public $errorMsg;

    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }

    public function goodsIn($supplierName, $itemName, $itemPrice, $itemCount)
    {
        /*  1. Check JSON array to see if 'itemName' already exists
                a. If so, update totals for this item
                b. If not, add the new item to the array
            2. Calculate the price spent, itemPrice * itemCount and take this away from the 'rollingCash' figure (this can go into negative as we are a business?)
            3. Dump the data from the array back into 'inventory.json' and end.


            Optional: Add 'supplierName' to 'list of suppliers?' Could have a sub array in the JSON of 'Supplier' and a zero indexed list of suppliers.
                1. Check if the supplier name exists and if it does, do nothing
                    a. If it doesn't then add the new name to the list.

        -----This process should either return true or false-----
        */

        $validate = new Validation;

        try {
            $validate->supplierName($supplierName);
            $validate->itemName($itemName);
            $validate->itemPrice($itemPrice);
            $validate->itemCount($itemCount);
        } catch (Exception $e) {
            $this->result = false;
            $this->errorMsg = $e;
            return;
        }

        if ($this->db->itemExists($itemName)) {
            $this->db->increaseStockLevels($itemName, $itemCount);
            $this->db->updatePricePaid($itemName, $itemPrice);
        } else { //we haven't got this item yet, add it, including price paid to the array.
            $this->db->addItem($supplierName, $itemName, $itemPrice, $itemCount);
        }

        $this->db->subtractRollingCash($itemPrice, $itemCount);
        $this->db->updateDB(); //this dumps the array data back to JSON.

        $this->result = true;
    }

    public function goodsOut($supplierName, $itemName, $itemPrice, $itemCount)
    {
        /*  1. Check JSON array to see if 'itemName' already exists
                a. If so, check stock levels are greater than requested sell amount. If not, cancel the transaction, we do not have enough of that item.
                b. If not, we do not own that item, cancel the transaction
            2. Reduce stock levels by amount specified
            3. Calculate the money received, itemPrice * itemCount and add this to the 'rollingCash' figure.
            4. Calculate the difference between the itemPrice already stored in the array, and the itemPrice supplied
                a. Multiply the result by the itemCount and add to the 'profit' figure ('profit' can also be a negative so have to bear that in mind)
            5. Dump the data from the array back into 'inventory.json' and end.

        -----This process should either return true or false-----
        */

        $validate = new Validation;

        try {
            $validate->supplierName($supplierName);
            $validate->itemName($itemName);
            $validate->itemPrice($itemPrice);
            $validate->itemCount($itemCount);
        } catch (Exception $e) {
            $this->result = false;
            $this->errorMsg = $e;
            return;
        }

        if ($this->db->itemExists($itemName)) {
            if ($this->db->checkStockLevels($itemName, $itemCount)) {

                $this->db->reduceStockLevels($itemName, $itemCount);
                $this->db->addRollingCash($itemPrice, $itemCount);
                $this->db->updateProfit($itemName, $itemPrice, $itemCount);
                $this->db->updateDB();

                $this->result = true; //goods in complete
            } else { //we do not have enough of that item, cancel the transaction
                $this->result = false;
                $this->errorMsg = 'We do not have enough of that item available to sell.';
            }
        } else { //we do not have that item, cancel the transaction
            $this->result = false;
            $this->errorMsg = 'We do not have that item in stock, so therefore cannot sell it.';
        }
    }

    public function getProfit()
    {
        $this->result = $this->db->getProfit();
    }

}