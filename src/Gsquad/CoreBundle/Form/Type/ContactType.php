<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 26/11/2016
 * Time: 22:36
 */

namespace Gsquad\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, array(
                'label' => 'Sujet du message',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(array(
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Minimum 2 caractères',
                        'maxMessage' => 'Maximum 255 caractères',
                    ))
                ]
            ))
            ->add('content', TextareaType::class, array(
                'label' => 'Votre message',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ))

            ->add('submit', SubmitType::class, array(
                'label' => 'Envoyer le message'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
