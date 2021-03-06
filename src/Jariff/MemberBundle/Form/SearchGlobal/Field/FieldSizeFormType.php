<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldSizeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('size', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'choices' => array(
                        '10' => '10',
                        '50' => '50',
                        '100' => '100',
                    ),
                    'attr' => array('class' => 'j-change'),
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\SearchFieldSize'
        ));
    }


    public function getName()
    {
        return 'member_search_global_field_size';
    }
}
