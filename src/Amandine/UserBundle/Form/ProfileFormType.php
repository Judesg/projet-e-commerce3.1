<?php
namespace Amandine\UserBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
 
class ProfileFormType extends AbstractType {
 
    public function buildForm(FormBuilderInterface $builder, array $options) {
 
        // custom field      
        $builder->add('userFirstName', TextType::class, array(
                    'label' => 'Prénom'
                    ));
        $builder->add('userLastName', TextType::class, array(
                    'label' => 'Nom'
                    ));
        $builder->add('address', TextType::class, array(
                    'label' => 'Adresse'
                    ));
        $builder->add('pc', IntegerType::class, array(
                    'label' => 'Code postal',
                    'attr' => array('min' => '01000',
                                    'max' => '95999')
                    ));
        $builder->add('city', TextType::class, array(
                    'label' => 'Ville'
                    ));
        $builder->add('phone', IntegerType::class, array(
                    'label' => 'Téléphone (+33)',
                    'attr' => array('min' => '0100000000',
                                    'max' => '0999999999')
                    ));
    }
 
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    public function getName()
   {
       return $this->getBlockPrefix();
   }
 
}