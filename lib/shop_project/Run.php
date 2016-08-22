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

    private $result = 'The request failed';

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
        //$db = new (self::DB_CON);

        $dbType = self::DB_CON;

        $db = new $dbType;
        $transaction = new Transaction($db);
        $transaction->goodsIn('Misco', 'PS2 Mouse', 4.60, 2);

        if ($transaction->result === true) {
            $this->result = 'The request succeeded';
        }

        $this->output($this->result);
    }

    public function sell() //sell stock
    {
        $dbType = self::DB_CON;

        $db = new $dbType;
        $transaction = new Transaction($db);
        $transaction->goodsOut('Misco', 'Butter', 4.50, 1);

        if ($transaction->result === true) {
            $this->result = 'The request succeeded';
        }

        $this->output($this->result);
    }

    public function profit() //display profit and cash in hand.
    {
        $dbType = self::DB_CON;

        $db = new $dbType;
        $transaction = new Transaction($db);
        $transaction->getProfit();

        $this->result = 'Profit is currently at: £' . $transaction->result;

        $this->output($this->result);
    }

    public function output($output)
    {
        fwrite(STDOUT, $output);
    }

}

$shop = new Shop($argv[1]); //passes in argument to constructor.