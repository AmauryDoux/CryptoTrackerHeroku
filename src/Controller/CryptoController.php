<?php

namespace App\Controller;

use App\Form\Crypto\AddCryptoForm;
use App\Form\Crypto\DeleteCryptoForm;
use App\Services\CryptoService;
use App\Services\DatabaseService;
use App\Services\HttpClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class CryptoController extends AbstractController
{
    /**  */
    private $client;

    /**  */
    private $dbService;

    /**  */
    private $cryptoService;

    /**
     * 
     */
    public function __construct(
        HttpClientService $client,
        DatabaseService $db_service,
        CryptoService $crypto_service
    ) {
        $this->client = $client;
        $this->totalWallet = 0;
        $this->dbService = $db_service;
        $this->cryptoService = $crypto_service;
    }

    /**
     * 
     */
    public function evolution(ChartBuilderInterface $chart_builder) 
    {
        $labels = [];
        $balances = [];
        $datas = $this->dbService->getAllHistoryEntry();

        foreach($datas as $data) {
            $newDate = gmdate("d-m-Y", $data['date']);
            array_push($labels, $newDate);
        }
        
        foreach($datas as $balance) {
            array_push($balances, $balance['balance']);
        }
        
        $chart = $chart_builder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Valeur investissement en €',
                    'backgroundColor' => 'rgb(80, 215, 255)',
                    'borderColor' => 'rgb(80, 215, 255)',
                    'data' => $balances,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 50000]],
                ],
            ],
        ]);
        
        return $this->render('/crypto/evolution.html.twig', [
            'chart' => $chart,
        ]);
    }

    /**
     * 
     */
    public function index(): Response
    {
        $cryptos = [];

        $allCryptos = $this->dbService->getAllCryptos();

        foreach($allCryptos as $crypto) {
            $orders = $this->dbService->getOrdersByCryptoId($crypto['id']);

            $datas = $this->client->getCryptoInfos($crypto['id']);
            /** @todo récupérer le prix */
            $currentPrice = $datas['price'];

            $ordersPrice = 0;
            $ordersPriceCount = 1;

            foreach($orders as $order) {
                $ordersPrice += $order->getPrice();
                $ordersPriceCount++;
            }

            $averageOrdersPrice = $ordersPrice/$ordersPriceCount;
            $comparePrices = $this->cryptoService->comparePrices($averageOrdersPrice, $currentPrice);
    
            /** @todo Sur le nom et le symbol faire l'API call dans le service */
            $cryptos[] = [
                'logo' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/'.$crypto['id'].'.png',
                'symbol' => $datas['symbol'],
                'name' => $datas['name'],
                'arrows' => $comparePrices['arrows'],
                'negative' => $comparePrices['negative']
            ];
            
        }

        return $this->render('/crypto/index.html.twig',[
            'cryptos' => $cryptos,
            'totalWallet' => $this->cryptoService->getBalance(),
        ]);
    }

    /**
     * 
     */
    public function add(Request $request): Response
    {
        $validMessage = '';
        $addForm = new AddCryptoForm($this->client);
        $form = $this->createForm(AddCryptoForm::class, $addForm, [
            'action' => $this->generateUrl('crypto.add'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dump($form->get('cryptos')->getData());die;
            $this->dbService->addCrypto(
                $form->get('cryptos')->getData(),
                $form->get('quantity')->getData(),
                $form->get('price')->getData()
            );
            $validMessage = 'Votre devise a bien été ajoutée';
        }

        return $this->render('/crypto/addCrypto.html.twig',[
            'form' => $form->createView(),
            'valid_message' => $validMessage
        ]);
    }

    /**
     * 
     */
    public function delete(Request $request): Response {
        $validMessage = '';
        $deleteForm = new DeleteCryptoForm($this->dbService, $this->client);
        $form = $this->createForm(DeleteCryptoForm::class, $deleteForm, [
            'action' => $this->generateUrl('crypto.delete'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dbService->deleteCrypto(
                $form->get('cryptos')->getData(),
                $form->get('quantity')->getData(),
                $form->get('price')->getData()
            );
            $validMessage = 'Votre devise a bien été supprimée';
        }
        return $this->render('/crypto/deleteCrypto.html.twig',[
            'form' => $form->createView(),
            'valid_message' => $validMessage
        ]);
    }

    /**
     * 
     */
    public function cron(Request $request) 
    {    
        if($request->query->has('id') && $request->query->get('id') == '145896321') {
            $this->dbService->addHistoryEntry($this->cryptoService->getBalance());
        }
        die;
    }
}
