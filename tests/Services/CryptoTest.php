<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CryptoTest extends KernelTestCase
{
    public function testCompare()
    {
        self::bootKernel();

        $container = self::$container;

        $cryptoService = $container->get(CryptoService::class);
        $compared = $cryptoService->comparePrices(1.5, 0.2);
        $this->assertEquals(['negative' => false, 'arrows' => 3], $compared);
    }
}