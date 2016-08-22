<?php

abstract class DbAbstract implements DbInterface
{
    //Shared variables
    protected $result;
    //Shared variables

    abstract public function __construct();

    abstract public function itemExists($itemName);
    abstract public function addItem($supplierName, $itemName, $itemPrice, $itemCount);
    abstract public function addRollingCash($itemPrice, $itemCount);
    abstract public function subtractRollingCash($itemPrice, $itemCount);
    abstract public function updateDB();
    abstract public function checkStockLevels($itemName, $itemCount);
    abstract public function increaseStockLevels($itemName, $itemCount);
    abstract public function reduceStockLevels($itemName, $itemCount);
    abstract public function updateProfit($itemName, $itemPrice, $itemCount);
    abstract public function getProfit();
    abstract public function updatePricePaid($itemName, $itemPrice);
}