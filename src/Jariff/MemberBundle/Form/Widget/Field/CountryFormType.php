<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\Widget\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array();
        foreach ($options['dataCountry'] as $dc) {
           
            $choices[$dc['key']] = $dc['key'].'|'.$dc['doc_count'];
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
        return 'country_widgets';
    }
}
