<?php

namespace App\Tests\Service;

//use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @see php ./vendor/bin/phpunit
 */
class CryptoTest extends WebTestCase
{
    /** @test */
    public function indexPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div', 'Crypto Tracker');
    }

    /** @test */
    public function clickAddCryptoLink()
    {   
        $client = static::createClient();
    
        $crawler = $client->request('GET', '/');
        
        $link = $crawler->filter('#add-button')->link();
        $client->click($link);

        // $this->assertResponseIsSuccessful();
        $crawler = new Crawler($client->getResponse()->getContent());
        $this->assertEquals(1, $crawler->filter('span:contains(\'Ajouter une transaction\')')->count());
    }

    /** @test */
    public function clickDeleteCryptoLink()
    {   
        $client = static::createClient();
    
        $crawler = $client->request('GET', '/');
        
        $link = $crawler->filter('#add-button')->link();
        $client->click($link);

        // $this->assertResponseIsSuccessful();
        $crawler = new Crawler($client->getResponse()->getContent());
        $this->assertEquals(1, $crawler->filter('span:contains(\'Supprimer un montant\')')->count());
    }

    /** @test */
    public function addPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/crypto/add');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('span', 'Ajouter une transaction');
    }

    /** @test */
    public function addCrypto(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/crypto/add');

        $buttonCrawlerNode = $crawler->selectButton('AJOUTER');

        $form = $buttonCrawlerNode->form();
        $form['add_crypto_form[cryptos]']->select(1);
        $form['add_crypto_form[quantity]'] = 10;
        $form['add_crypto_form[price]'] = 600;
        $crawler = $client->submit($form);
        
        $this->assertEquals(1, $crawler->filter('span:contains(\'Votre devise a bien été ajoutée\')')->count());
        $this->assertResponseIsSuccessful();
    }

    /** @test */
    public function deletePageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/crypto/delete');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('span', 'Supprimer un montant');
    }

    /** @test */
    public function deleteCrypto(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/crypto/delete');

        $buttonCrawlerNode = $crawler->selectButton('VALIDER');

        $form = $buttonCrawlerNode->form();
        $form['delete_crypto_form[cryptos]']->select(1);
        $form['delete_crypto_form[quantity]'] = 10;
        $form['delete_crypto_form[price]'] = 600;
        $crawler = $client->submit($form);

        $this->assertEquals(1, $crawler->filter('span:contains(\'Votre devise a bien été supprimée\')')->count());
        $this->assertResponseIsSuccessful();
    }

    /** @test */
    public function evolutionPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/crypto/evolution');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('span', 'Vos gains');
    }
}