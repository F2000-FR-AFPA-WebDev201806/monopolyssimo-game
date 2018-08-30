<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType; //Pour la création de formulaires Symfony
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type as FormType;

class UserLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $oBuilder, array $options)
    {
        $oBuilder
            ->add('username')
            ->add('password', FormType\PasswordType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
