<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 10/11/2016
 * Time: 14:28
 */

namespace Gsquad\BlogBundle\Form\Extension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ImageTypeExtension extends AbstractTypeExtension
{
    /**
     * @return string
     */
    public function getExtendedType()
    {
        return FileType::class;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('image_path'));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        dump($options['image_path']);
        if(isset($options['image_path'])) {
            $parentData = $form->getParent()->getData();

            $imageUrl = null;

            if(null !== $parentData) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $imageUrl = $accessor->getValue($parentData, $options['image_path']);
            }
            dump($parentData, $imageUrl);
            $view->vars['image_url'] = $imageUrl;
        }
    }
}