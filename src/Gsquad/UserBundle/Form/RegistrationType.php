<?php
// src/AppBundle/Form/RegistrationType.php

namespace Gsquad\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class,
                [
                    'label' => 'form.first_name',
                    'translation_domain' => 'FOSUserBundle',
                    'required' => true,
                    'constraints' => [
                        new NotBlank()
                    ]
                ]
            )
            ->add('lastName', TextType::class,
                [
                    'label' => 'form.last_name',
                    'translation_domain' => 'FOSUserBundle',
                    'required' => true,
                    'constraints' => [
                        new NotBlank()
                    ]
                ]
            )
            ->add('adress1', TextType::class,
                [
                    'label' => 'form.adress1',
                    'translation_domain' => 'FOSUserBundle',
                    'required' => true,
                    'constraints' => [
                        new NotBlank()
                    ]
                ]
            )
            ->add('adress2', TextType::class,
                [
                    'label' => 'form.adress2',
                    'translation_domain' => 'FOSUserBundle'
                ]
            )
            ->add('zipCode', TextType::class,
                [
                    'label' => 'form.zip_code',
                    'translation_domain' => 'FOSUserBundle',
                    'required' => true,
                    'constraints' => [
                        new NotBlank()
                    ]
                ]
            )
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}