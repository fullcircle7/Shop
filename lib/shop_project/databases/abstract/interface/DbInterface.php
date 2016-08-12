<?php

interface DbInterface
{
    public function __construct($supplierName, $itemName, $itemPrice, $itemCount);

    public function itemExists();
    public function addItem();
    public function addRollingCash();
    public function subtractRollingCash();
    public function updateDB();
    public function checkStockLevels();
    public function increaseStockLevels();
    public function reduceStockLevels();
    public function updateProfit();
    public function getProfit();
    public function updatePricePaid();
}