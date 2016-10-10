<?php

use Databases\DbInterfaces\DbInterface;
use Inventory\Transaction;

class Shop
{
    const ERROR_MSG = 'You have mistyped your parameter, please try again';

    private $result;
    private $db;

    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }

    public function buy() //buy stock
    {
        $transaction = new Transaction($this->db);

        $transaction->goodsIn('Tesco', 'Bread', 0.85, 14);

        if ($transaction->result === false) {
            $this->result = 'Transaction failed. ' . $transaction->errorMsg;
            return $this->result;
        } else {
            $this->result = 'The request succeeded.';
            return $this->result;
        }
    }

    public function sell() //sell stock
    {
        $transaction = new Transaction($this->db);

        $transaction->goodsOut('Sainsburys', 'Rubber Gloves', 3, 3);

        if ($transaction->result === false) {
            $this->result = 'Transaction failed. ' . $transaction->errorMsg;
            return $this->result;
        } else {
            $this->result = 'The request succeeded.';
            return $this->result;
        }
    }

    public function profit() //display profit and cash in hand.
    {
        $transaction = new Transaction($this->db);

        $transaction->getProfit();

        $this->result = 'Profit is currently at: £' . $transaction->result;

        return $this->result;
    }
}

