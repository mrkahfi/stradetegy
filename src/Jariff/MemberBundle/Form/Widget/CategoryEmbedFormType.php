<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\Widget;

use Jariff\MemberBundle\Form\Demo\Field\FieldConditionFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jariff\MemberBundle\Form\Widget\Field\CategoryFormType;


class CategoryEmbedFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', 'collection',
                array(
                    'label_attr' => array('style' =>'display:none'),
                    'type' => new CategoryFormType(),
                    'by_reference' => false,
                    'allow_delete' => true,
                    'allow_add'    => true,
                ))
            

        ;


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\CategoryEmbed'
        ));
    }


    public function getName()
    {
        return 'widget_category_embed';
    }
}
