<?php

namespace Gsquad\BlogBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Gsquad\BlogBundle\Entity\Category;
use Gsquad\BlogBundle\Form\EventListener\AddPostListener;
use Gsquad\Utils\Slugger;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostType extends AbstractType
{
    private $em;
    private $slugger;

    public function __construct(EntityManager $em, Slugger $slugger)
    {
        $this->em = $em;
        $this->slugger = $slugger;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Titre de l\'article',
                'required' => true,
                'constraints' => [
                    new NotBlank(array("message" => "Merci d'indiquer un titre")),
                    new Length(array(
                        'min' => 2,
                        'max' => 70,
                        'minMessage' => 'Minimum {{ limit }} caractères',
                        'maxMessage' => 'Maximum {{ limit }} caractères',
                    ))
                ]
            ))
            ->add('content', CKEditorType::class, array(
                'config' => array('toolbar' => 'standard'),
                'label' => 'Votre article',
                'required' => true,
                'constraints' => [
                    new NotBlank(array("message" => "Merci de rédiger votre article")),
                ]
            ))
            ->add('category', EntityType::class, array(
                'class'        => 'GsquadBlogBundle:Category',
                'choice_label' => 'name',
                'label' => 'Dans quelle catégorie souhaitez-vous ajouter votre article ?',
            ))
            ->add('imageFile', VichImageType::class, array(
                'label' => 'Associer une image',
                'required'      => false,
                'allow_delete'  => true,
                'download_link' => true,
            ))
            ->add('publish', SubmitType::class, array(
                'label' => 'Publier l\'article'
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Soumettre l\'article'
            ))
            ->addEventSubscriber(new AddPostListener($this->em, $this->slugger))
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
