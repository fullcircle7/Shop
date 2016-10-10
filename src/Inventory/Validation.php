<?php

namespace Inventory;

use Exception;

class Validation
{
    public function supplierName($supplierName)
    {
        if (!isset($supplierName)) {
            Throw new Exception('Supplier name is not set.');
        }

        if (!is_string($supplierName)) {
            Throw new Exception('Supplier name is not a string.');
        }

        //strip valid characters
        $supplierName = str_replace(array(' ', "'", '-', '_', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), '', $supplierName);

        if (!ctype_alpha($supplierName)) { //check that only letters remain.
            Throw new Exception('Supplier name has invalid characters.');
        }
    }

    public function itemName($itemName)
    {
        if (!isset($itemName)) {
            Throw new Exception('Item name is not set.');
        }

        if (!is_string($itemName)) {
            Throw new Exception('Item name is not a string.');
        }

        //strip valid characters
        $itemName = str_replace(array(' ', "'", '-', '_', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), '', $itemName);

        if (!ctype_alpha($itemName)) { //check that only letters remain.
            Throw new Exception('Item name has invalid characters.');
        }
    }

    public function itemPrice($itemPrice)
    {
        if (!isset($itemPrice)) {
            Throw new Exception('Item price is not set.');
        }

        if (!is_int($itemPrice) && !is_float($itemPrice)) { //Item price can be either int or float
            Throw new Exception('Item price is not an int or a float.');
        }

        if ($itemPrice <= 0) {
            Throw new Exception('Item price is less than or equal to zero.');
        }
    }

    public function itemCount($itemCount)
    {
        if (!isset($itemCount)) {
            Throw new Exception('Item count is not set.');
        }

        if (!is_int($itemCount)) { //item price can only be an int
            Throw new Exception('Item count is not an int.');
        }

        if ($itemCount < 1) {
            Throw new Exception('Item count must be at least 1.');
        }
    }
}