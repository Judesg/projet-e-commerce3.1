<?php

namespace Amandine\ProjetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('note', IntegerType::class, array(
                                                    'attr' => array(
                                                            'max' => '5',
                                                            'min' => '1',
                                                            'placeholder' => 'Attribuez une note de 1 à 5'
                                                            )
                                                ))
        	->add('title', TextType::class, array('label' => 'Titre'))
        	->add('content', TextareaType::class, array('label' => 'Commentaire'))
        ;
    }

    public function getName()
    {
        return 'comment';
    }
}