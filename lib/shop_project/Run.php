<?php

/*
 *
There is no validation of user input yet in the program.. I wanted to keep it simple first of all to get the structure, functions and classes right
There is also no specific error messages yet either.. It's just succeeded or failed..

Remember... keep it simple for now, generally...

//Troubleshooting Code...
exit('I got here');
fwrite(STDOUT,'test');
//Troubleshooting Code...

*/

require 'autoloader.inc.php';

class Shop
{
    const BUY = 'buy';
    const SELL = 'sell';
    const PROFIT = 'profit';
    const ERROR_MSG = 'You have mistyped your parameter, please try again';

    const DB_CON = 'jsonConnector';
    //const DB_CON = 'sqlConnector';

    public function __construct($action)
    {
        $action = strtolower($action); //make sure case is always the same for comparison purposes

        if ($action === self::BUY) {
            self::BUY();
        } else if ($action === self::SELL) {
            self::SELL();
        } else if ($action === self::PROFIT) {
            self::PROFIT();
        } else {
            $this->output(self::ERROR_MSG);
            exit();
        }
    }

    public function buy() //buy stock
    {
        $transaction = new Transaction('Misco', 'USB Mouse', 4.50, 7);
        $transaction->goodsIn();

        if ($transaction->result === true) {
            $result = 'The request succeeded';
        } else {
            $result = 'The request failed';
        }

        $this->output($result);
    }

    public function sell() //sell stock
    {
        $transaction = new Transaction('Misco', 'Butter', 4.50, 1);
        $transaction->goodsOut();

        if ($transaction->result === true) {
            $result = 'The request succeeded';
        } else {
            $result = 'The request failed';
        }

        $this->output($result);
    }

    public function profit() //display profit and cash in hand.
    {
        $transaction = new Transaction('this','is','not','needed');
        $transaction->getProfit();
        $result = 'Profit is currently at: £' . $transaction->result;

        $this->output($result);
    }

    public function output($output)
    {
        fwrite(STDOUT, $output);
    }

}

$shop = new Shop($argv[1]); //passes in argument to constructor.