<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HistorySubsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('ch', 'choice',
            array(
                'label_attr' => array('style' => 'display:none'),
                'attr' => array('style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                'choices' => array(
                    1 => '1 Month',
                    6=> '6 Months',
                    12 => '12 Month',
                    18 => '18 Month',
                    24 => '24 Month',
                    36 => '36 Month',
                    60 => '60 Month',
                    ),
                'multiple' => true,
                'expanded' => true,
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\CheckBoxSubs'
            ));
    }


    public function getName()
    {
        return 'history_widgets_addons';
    }
}
