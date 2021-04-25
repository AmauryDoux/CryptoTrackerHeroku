<?php
namespace App\Form\Crypto;

use App\Services\HttpClientService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AddCryptoForm extends AbstractType
{

    /**  */
    private $httpService;

    /**
     * 
     */
    function __construct(HttpClientService $http_service)
    {
        $this->httpService = $http_service;
    }

    /**
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];
        if($cryptos = $this->httpService->getAllCryptos()) {
            foreach($cryptos as $crypto) {
                $choices[$crypto['name']] = $crypto['id'];
            }
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
                    'placeholder' => 'Prix d\'achat',
                    'class' => 'fieldClass'
                )
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'AJOUTER',
                'attr' => array(
                    'class' => 'addCryptoBtn'
                )
            ])
        ;
    }
}