<?php

class sqlConnector extends DbAbstract
{
    //SQL specific variables
    private $sql_db_handle;
    //SQL specific variables

    public function __construct($supplierName, $itemName, $itemPrice, $itemCount)
    {
        $this->supplierName = $supplierName;
        $this->itemName = $itemName;
        $this->itemPrice = $itemPrice;
        $this->itemCount = $itemCount;

        //establish database connection object and store in sql_db_handle
    }

    public function itemExists()
    {

    }

    public function addItem()
    {

    }

    public function addRollingCash()
    {

    }

    public function subtractRollingCash()
    {

    }

    public function updateDB()
    {

    }

    public function checkStockLevels()
    {

    }

    public function increaseStockLevels()
    {

    }

    public function reduceStockLevels()
    {

    }

    public function updateProfit()
    {

    }

    public function getProfit()
    {

    }

    public function updatePricePaid()
    {

    }
}