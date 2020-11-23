<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TBPSubsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('ch', 'choice',
            array(
                'label_attr' => array('style' => 'display:none'),
                'attr' => array('style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                'choices' => array(
                    1 => 'Yes',
                    0=> 'No'
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
        return 'tbp_widgets_addons';
    }
}
