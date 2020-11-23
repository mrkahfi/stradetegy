<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments;

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
                    'label' => 'Search',
                    
                    'attr' => array('class' => 'j-choice-as'),
                    'choices' => array(
                        'imports' => 'Imports',
                        'exports' => 'Exports'
                    )

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
        return 'search_data_custom';
    }
}
