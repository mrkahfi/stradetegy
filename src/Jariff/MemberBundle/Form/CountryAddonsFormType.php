<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryAddonsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array();
        foreach ($options['dataCountry'] as $key => $dc) {
            $keyExplode = explode('-',$key)[0];
            $choices[$key] = strtoupper($keyExplode .'|'. $dc);
        }

        $builder
        ->add('country', 'choice',
            array(
                'label_attr' => array('style' => 'display:none'),
                'attr' => array('style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                'choices' => $choices,
                'multiple' => true,
                'expanded' => true,
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\Country',
            'dataCountry' => null
            ));
    }


    public function getName()
    {
        return 'country_widgets_addons';
    }
}
