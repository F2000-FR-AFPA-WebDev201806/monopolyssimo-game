<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Form\Extension\Core\Type as FormType;

class UserType extends UserLoginType
{
    public function buildForm(FormBuilderInterface $oBuilder, array $options)
    {
       parent::buildForm($oBuilder, $options);
        
       $oBuilder
            ->add('password', RepeatedType::class, array(
                  'type' => PasswordType::class,
                  'invalid_message' => 'Erreur de saisie des passwords, ils doivent être identique',
                  'options' => array('attr' => array('class' => 'password-field')),
                  'required' => true,
                  'first_options'  => array('label' => 'Password'),
                  'second_options' => array('label' => 'Repeat Password'),
));
        ;
    }
}

