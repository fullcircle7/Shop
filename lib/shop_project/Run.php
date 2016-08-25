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

//...Set DB type here...//

const DB_CON = 'jsonConnector';
//const DB_CON = 'sqlConnector';
//const DB_CON = 'otherDatabase';

//...Set DB type here...//

class Shop
{
    const BUY = 'buy';
    const SELL = 'sell';
    const PROFIT = 'profit';
    const ERROR_MSG = 'You have mistyped your parameter, please try again';

    private $result;
    private $db;

    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }

    public function handle($action)
    {
        $transaction = new Transaction($this->db);

        $action = strtolower($action); //make sure case is always the same for comparison purposes

        if ($action === self::BUY) {
            $this->buy($transaction);
        } else if ($action === self::SELL) {
            $this->sell($transaction);
        } else if ($action === self::PROFIT) {
            $this->profit($transaction);
        } else {
            $this->output(self::ERROR_MSG);
            exit();
        }
    }

    public function buy($transaction) //buy stock
    {
        $transaction->goodsIn('Misco', 'PS2 Mouse', 0.01, 1);

        if ($transaction->result === false) {
            $this->output('Transaction failed. ' . $transaction->errorMsg);
        } else {
            $this->output('The request succeeded.');
        }
    }

    public function sell($transaction) //sell stock
    {
        $transaction->goodsOut('Misco', 'Butter', 1, 1);

        if ($transaction->result === false) {
            $this->output('Transaction failed. ' . $transaction->errorMsg);
        } else {
            $this->output('The request succeeded.');
        }
    }

    public function profit($transaction) //display profit and cash in hand.
    {
        $transaction->getProfit();

        $this->result = 'Profit is currently at: £' . $transaction->result;

        $this->output($this->result);
    }

    public function output($output)
    {
        fwrite(STDOUT, $output);
    }

}

$dbType = DB_CON;
$db = new $dbType;

$shop = new Shop($db); //passes in argument to constructor.
$shop->handle($argv[1]);