<?php

namespace App\Services;

class CryptoService
{
    /** */
    private $dbService;

    /**
     * 
     */
    function __construct(DatabaseService $db_service)
    {
        $this->dbService = $db_service;
    }

    /**
     * 
     */
    public function comparePrices($price_a, $price_b)
    {
        $percent = (($price_b/$price_a)*100)-100;
        $arrows = 0;
        if(abs($percent) < 30) {
            $arrows = 1;
        } else if (abs($percent) > 30 && abs($percent) < 100) {
            $arrows = 2;
        } else if (abs($percent) > 100) {
            $arrows = 3;
        }

        return ['negative' => ($percent < 0), 'arrows' => $arrows];
    }

    public function getBalance()
    {
        $balance = 0;

        foreach ($this->dbService->getAllOrders() as $order) {
            $balance += ($order->getQuantity() * $order->getPrice());
        }

        return $balance;
    }
}



