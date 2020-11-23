<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\Widget\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCountryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', 'checkbox',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('style' => 'margin: 0px 3px 3px 3px;', 'class' => 'col-md-8')
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\FilterCountry'
        ));
    }


    public function getName()
    {
        return 'country_widget';
    }
}
