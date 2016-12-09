<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 07/12/2016
 * Time: 11:42
 */

namespace Gsquad\BlogBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Titre de la catégorie'
            ))

        ;
    }
}
