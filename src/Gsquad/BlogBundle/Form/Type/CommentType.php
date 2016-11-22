<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 07/11/2016
 * Time: 12:57
 */

namespace Gsquad\BlogBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, array(
                'label' => 'Votre commentaire'
            ))
            ->add('Commenter', SubmitType::class)
        ;
    }
}
