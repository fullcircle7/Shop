<?php

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

        $transaction->goodsIn('Misco', 'PS2 Mouse', 0.01, 1);

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

        $transaction->goodsOut('Misco', 'Butter', 1, 1);

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

