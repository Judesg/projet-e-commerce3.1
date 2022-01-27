<?php

namespace Amandine\ProjetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FilterType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('name', TextType::class, array('label' => 'Nom'))
        	/*->add('material', TextType::class, array('label' => 'Matiere'))
            ->add('color', TextType::class, array('label' => 'Couleur'))
        	->add('htPrice', ChoiceType::class, array(
                'label' => 'Prix',
                'choise' => [
                        new htPrice('0-20'),
                        new htPrice('20-30'),
                        new htPrice('30-40'),
                        new htPrice('40-50'),
                        new htPrice('50-60'),
                        new htPrice('60-70'),
                        new htPrice('70-80'),
                        new htPrice('80-90'),
                        new htPrice('90-100'),
                        new htPrice('100-110'),
                    ]
            ))*/
        ;
    }

    public function getName()
    {
        return 'filter';
    }
}