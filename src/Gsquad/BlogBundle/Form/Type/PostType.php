<?php

namespace Gsquad\BlogBundle\Form\Type;

use Gsquad\BlogBundle\Form\Type\CategoryType;
use Gsquad\BlogBundle\Form\Type\TagType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Titre de l\'article'
            ))
            ->add('content', CKEditorType::class, array(
                'label' => 'Votre article'
            ))
            /*->add('tags', TagType::class, array(
                'label' => 'Tags associés'
            ))*/
            ->add('category', EntityType::class, array(
                'class' => 'Gsquad\BlogBundle\Entity\Category',
                'choice_label' => 'name',
                'label' => 'Dans quelle catégorie souhaitez-vous ajouter votre article ?'
            ))
            /*->add('image', ImageType::class)*/
            ->add('submit', SubmitType::class, array(
                'label' => 'Publier l\'article'
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gsquad\BlogBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gsquad_blogbundle_post';
    }


}
