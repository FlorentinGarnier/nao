<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 05/12/2016
 * Time: 14:41
 */

namespace Gsquad\AdminBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MailingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, array(
                'label' => 'Destinataire(s)',
            ))
            ->add('subject', TextType::class, array(
                'label' => 'Sujet',
                'required' => true,
                'constraints' => [
                    new NotBlank(array("message" => "Merci d'indiquer l'objet de votre message.")),
                    new Length(array(
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Minimum {{ limit }} caractères',
                        'maxMessage' => 'Maximum {{ limit }} caractères',
                    ))
                ]
            ))
            ->add('message', TextareaType::class, array(
                'label' => 'Votre message',
                'required' => true,
                'constraints' => [
                    new NotBlank(array("message" => "Merci de renseigner votre message.")),
                    new Length(array(
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'Minimum {{ limit }} caractères',
                        'maxMessage' => 'Maximum {{ limit }} caractères',
                    ))
                ]
            ))
            ->add('envoyer', SubmitType::class);
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
    }

    public function getName()
    {
        return 'mailing_form';
    }
}