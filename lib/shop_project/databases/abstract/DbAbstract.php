<?php

abstract class DbAbstract implements DbInterface
{
    //Shared variables
    protected $supplierName;
    protected $itemName;
    protected $itemPrice;
    protected $itemCount;
    protected $result;
    //Shared variables

    abstract public function __construct($supplierName, $itemName, $itemPrice, $itemCount);

    abstract public function itemExists();
    abstract public function addItem();
    abstract public function addRollingCash();
    abstract public function subtractRollingCash();
    abstract public function updateDB();
    abstract public function checkStockLevels();
    abstract public function increaseStockLevels();
    abstract public function reduceStockLevels();
    abstract public function updateProfit();
    abstract public function getProfit();
    abstract public function updatePricePaid();
}