<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\Demo\Shipments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomDataFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice',
                array(
                    'label' => 'Results Found From',
                    'label_attr' => array('class' => 'col-sm-2 control-label'),
                    'choices' => array(
                            'us_imports' => 'Imports',
                            'us_exports' => 'Exports'
                    ),
                    'attr' => array('class' => 'form-control col-md-5')
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\CustomData'
        ));
    }


    public function getName()
    {
        return 'demo_search';
    }
}
