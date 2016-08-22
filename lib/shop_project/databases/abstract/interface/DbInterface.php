<?php

interface DbInterface
{
    public function __construct();

    public function itemExists($itemName);
    public function addItem($supplierName, $itemName, $itemPrice, $itemCount);
    public function addRollingCash($itemPrice, $itemCount);
    public function subtractRollingCash($itemPrice, $itemCount);
    public function updateDB();
    public function checkStockLevels($itemName, $itemCount);
    public function increaseStockLevels($itemName, $itemCount);
    public function reduceStockLevels($itemName, $itemCount);
    public function updateProfit($itemName, $itemPrice, $itemCount);
    public function getProfit();
    public function updatePricePaid($itemName, $itemPrice);
}