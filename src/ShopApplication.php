<?php

class ShopApplication extends Symfony\Component\Console\Application
{
    private $shop;

    public function __construct($shop)
    {
        parent::__construct();
        $this->shop = $shop;
    }

    public function getShop()
    {
        return $this->shop;
    }
}