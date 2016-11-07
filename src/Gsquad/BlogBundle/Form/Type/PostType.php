<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 06/11/2016
 * Time: 23:53
 */

namespace Gsquad\BlogBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Titre',
            ));
    }
}