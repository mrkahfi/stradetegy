<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchGlobalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q','text',array(
                'label_attr' => array('style' => 'display:none'),
                'required'      => false,
                'property_path' => null,
                'data' => $options['q'],

                'attr' => array('placeholder' => 'Find suppliers or customers of..',
                    'class' => 'global-search')
            ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data' => null,
            'q' => null,
            'collect' => null,
            'range' => null,
            'rev' => null,

        ));
    }


    public function getName()
    {
        return 'search_form';
    }
}
