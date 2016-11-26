<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 22/11/2016
 * Time: 15:05
 */

namespace Gsquad\BlogBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class);
    }
}