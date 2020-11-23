<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', 'choice',
                array(
                    'label_attr' => array('style' =>'display:none'),
                    'choices'  => array('buyers' => 'Buyers',
                        'suppliers' => 'Suppliers', 'logistics' => 'Logistics', 'shipments' => 'Shipments'),
                    'expanded' => true,
                    'multiple' => false,
                    'preferred_choices' => array('buyers'),
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\SearchFieldCategory'
        ));
    }


    public function getName()
    {
        return 'member_search_global_category';
    }
}
