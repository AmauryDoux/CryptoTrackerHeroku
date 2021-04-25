<?php
namespace App\Form\Crypto;

use App\Services\DatabaseService;
use App\Services\HttpClientService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class DeleteCryptoForm extends AbstractType
{
    private $dbService;
    private $httpClient;

    function __construct(DatabaseService $db_service, HttpClientService $http_client)
    {
        $this->dbService = $db_service;
        $this->httpClient = $http_client;
    }

    /**
     * @todo Appeler le service pour générer le tableau de crypto
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // appel au service dans une variable ensuite la passer dan la choices
        $choices = [];
        $cryptos = $this->dbService->getAllCryptos();
        foreach($cryptos as $key => $crypto) {
            $choices[$crypto['name']] = $key;
        }

        $builder
            ->add('cryptos', ChoiceType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false,
                'choices'=> $choices,
                'attr' => array(
                    'class' => 'fieldClass'
                )
            ])
            ->add('quantity', IntegerType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false,
                'attr' => array(
                    'placeholder' => 'Quantité',
                    'class' => 'fieldClass'
                )
            ])
            ->add('price', NumberType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false,
                'attr' => array(
                    'placeholder' => 'Prix de vente',
                    'class' => 'fieldClass'
                )
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'VALIDER',
                'attr' => array(
                    'class' => 'addCryptoBtn'
                )
            ])
        ;
    }
}