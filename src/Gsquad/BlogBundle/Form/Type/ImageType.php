<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 09/11/2016
 * Time: 22:09
 */

namespace Gsquad\BlogBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, array(
                'label' => 'Associer une image',
                'image_path' => 'webPath'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gsquad\BlogBundle\Entity\Post',
        ));
    }
}