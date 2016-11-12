<?php

namespace Gsquad\BlogBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', EntityType::class, array(
            'class' => 'Gsquad\BlogBundle\Entity\Tag',
            'choice_label' => 'title',
            'expanded' => true,
            'multiple' => true
        ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    /*public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gsquad\BlogBundle\Entity\Tag'
        ));
    }*/

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gsquad_blogbundle_tag';
    }


}
