<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\Widget\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                    'choices' => array('importer' => 'Importer','exporter' => 'Exporter','product' => 'Product'),
                    'multiple' => true,
                    'expanded' => true,
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\Category'
        ));
    }


    public function getName()
    {
        return 'category_widget';
    }
}
