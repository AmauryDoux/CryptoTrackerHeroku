<?php

namespace App\Services;

use App\Entity\Crypto;
use App\Entity\Historic;
use App\Entity\Order;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseService
{
    /** */
    private $entityManager;

    /** */
    private $httpClient;

    /**
     * 
     */
    function __construct(EntityManagerInterface $entity_manager, HttpClientService $http_client)
    {
        $this->httpClient = $http_client;
        $this->entityManager = $entity_manager;
    }

    /**
     * 
     */
    public function getAllOrders()
    {
        return  $this->entityManager->getRepository(Order::class)->findAll();
    }

    /**
     * 
     */
    public function getAllCryptos()
    {
        $cryptos = [];
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        foreach($orders as $order) {
            $infos = $this->httpClient->getCryptoInfos($order->getCryptoId());
            $cryptos[$order->getCryptoId()] = [
                'id' => $order->getCryptoId(),
                'name' => $infos['name'],
                'symbol' => $infos['symbol'],
                'price' => $order->getPrice()
            ];
        }
        return  $cryptos;
        // $this->entityManager->getRepository(Order::class)->findAll();
    }

    /**
     * 
     */
    public function getOrdersByCryptoId($id)
    {
        return $this->entityManager->getRepository(Order::class)->findBy(['crypto_id' => $id]);
        // $this->entityManager->getRepository(Order::class)->findAll();
    }

    /** 
     * 
     */
    public function addCrypto($id, $quantity, $price) 
    {
        $order = new Order();
        $order->setCryptoId($id);
        $order->setPrice($price);
        $order->setQuantity($quantity);

        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    /** 
     * 
     */
    public function deleteCrypto($id, $quantity, $price) 
    {
        $order = new Order();
        $order->setCryptoId($id);
        $order->setPrice($price * (-1));
        $order->setQuantity($quantity);

        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }


    /** 
     * 
     */
    public function addHistoryEntry($balance) 
    {
        $order = new Historic();
        $order->setBalance($balance);
        $order->setDate(time());

        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}



